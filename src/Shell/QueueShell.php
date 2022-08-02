<?php
declare(strict_types=1);

namespace App\Shell;

use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Filesystem\File;

use App\View\PdfView;

class QueueShell extends App
{

    public function startup(): void
    {
        $this->loadModel('Lammies');
    }

    public function main(): void
    {
        $result = $this->Lammies->find('lastInQueue')->all();
        if($result->count() == 0)
            $this->out(0);
        else
            $this->out($result->first()->id);
    }

    public function single($id = 0, $filename = NULL)
    {
        $this->createPdf($id, $filename);
    }

    public function double($id = 0, $filename = NULL)
    {
        $this->createPdf($id, $filename, true);
    }

    private function createPdf($id, $filename, $double = false)
    {
        $query = $this->Lammies->find('Queued');
        $query->where(["Lammies.id <=" => $id]);
        $lammies = $query->all();
        if($lammies->count() == 0)
            return;

        $this->Lammies->setStatuses($lammies, 'Printing');

        $pdf = (new PdfView())->createPdf($lammies, $double);
        if(is_null($pdf))
            return;

        if(empty($filename)) {
            $this->out($pdf);
            return;
        }

        $file = new File($filename, true);
        $file->write($pdf);
        $file->close();
    }

    public function printed($id)
    {
        $query = $this->Lammies->find('printing');
        $query->where(["Lammies.id <=" => $id]);
        $lammies = $query->all();

        $this->Lammies->setStatuses($lammies, 'Printed');

        $this->out($lammies->count());
    }

    public function getOptionParser(): ConsoleOptionParser
    {
        $parser = parent::getOptionParser();
        $parser->setDescription(
                [ 'Handle lammy printing queue and pdf generation.', ''
                , 'Returns the <id> of the last unprinted lammy in the queue'
                . ' when called with out any parameters.'
                ])
            ->addSubcommand('single',
                [ 'help' => 'Create a PDF for single-sided printing.'
                , 'parser' =>
                    [ 'arguments' =>
                        [ 'id' =>
                            [ 'help' => '<id> of the last lammy to include.'
                            , 'required' => true
                            ]
                        , 'filename' =>
                            [ 'help' => 'It can be an absolute path.'
                            , 'required' => false
                ]   ]   ]   ])
            ->addSubcommand('double',
                [ 'help' => 'Create a PDF for double-sided printing.'
                , 'parser' =>
                    [ 'arguments' =>
                        [ 'id' =>
                            [ 'help' => '<id> of the last lammy to include.'
                            , 'required' => true
                            ]
                        , 'filename' =>
                            [ 'help' => 'It can be an absolute path.'
                            , 'required' => false
                ]   ]   ]   ])
            ->addSubcommand('printed',
                [ 'help' => 'Mark queued lammies as printed.'
                , 'parser' =>
                    [ 'arguments' =>
                        [ 'id' =>
                            [ 'help' => 'Mark lammies as printed'
                                        . ' up to and including <id>.'
                            , 'required' => true
                ]   ]   ]   ])
            ->removeOption('quiet')
            ->removeOption('verbose');

        foreach($parser->subcommands() as $sub) {
            $sub = $sub->parser();
            if(!$sub) continue;

            $sub->removeOption('quiet');
            $sub->removeOption('verbose');
        }

        return $parser;
    }

}
