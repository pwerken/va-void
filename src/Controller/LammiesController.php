<?php
declare(strict_types=1);

namespace App\Controller;

class LammiesController
    extends AppController
{
    use \App\Controller\Trait\View;

    public function index()
    {
        $query = $this->Lammies->find()
                    ->select([], true)
                    ->select('Lammies.id')
                    ->select('Lammies.status')
                    ->select('Lammies.entity')
                    ->select('Lammies.key1')
                    ->select('Lammies.key2')
                    ->select('Lammies.modified');
        $content = [];
        foreach($this->doRawQuery($query) as $row) {
            $content[] =
                [ 'class' => 'Lammy'
                , 'url' => '/lammies/'.$row[0]
                , 'status' => $row[1]
                , 'entity' => $row[2]
                , 'key1' => (int)$row[3]
                , 'key2' => (int)$row[4]
                , 'modified' => $row[5]
                ];
        }
        $this->set('_serialize',
            [ 'class' => 'List'
            , 'url' => rtrim($this->request->getPath(), '/')
            , 'list' => $content
            ]);
    }

    public function queue()
    {
    }

    public function pdfSingle()
    {
        $this->uptoId(key($this->request->getData()));
        $this->pdfOutput(false);
    }

    public function pdfDouble()
    {
        $this->uptoId(key($this->request->getData()));
        $this->pdfOutput(true);
    }

    public function printed()
    {
        $this->uptoId(key($this->request->data));
    }

    private function uptoId($id)
    {
    }

    private function pdfOutput($double = false)
    {
    }
}
