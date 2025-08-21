<?php
declare(strict_types=1);

namespace App\Lammy;

abstract class CharactersImbueLammy extends LammyCard
{
    public function draw(int $side, ?array $data = null): void
    {
        $character = $this->entity->get('character');
        $imbue = $this->entity->get('imbue');

        $type = $this->entity->get('type');

        $data = [];
        $data['type'] = ucfirst($type);
        $data['key'] = 'ID';
        $data['id'] = sprintf('%04d', $imbue->get('id'));
        $data['name'] = $imbue->get('name');
        $data['text'] = $imbue->get('description');
        $data['plin'] = $character->get('plin') . ' - ' . $character->get('chin');
        $data['char'] = $character->get('name');

        $data['times'] = $this->entity->get('times');
        $data['cost'] = $imbue->get('cost');
        $data['total'] = $data['times'] * $data['cost'];

        if ($data['times'] > 1) {
            $data['total'] .= ' (' . $data['times'] . 'x' . $data['cost'] . ')';
            $data['text'] = ''
                . 'This imbue takes up ' . $data['total'] . ' of your '
                . $type . ' imbue cap.'
                . PHP_EOL
                . 'You have taken this imbue ' . $data['times'] . ' times, '
                . 'for each time taken:'
                . PHP_EOL . PHP_EOL
                . $data['text'];
            $data['times'] .= ' x';
        } else {
            $data['text'] = ''
                . 'This imbue takes up ' . $data['total'] . ' of your '
                . $type . ' imbue cap.'
                . PHP_EOL . PHP_EOL
                . $data['text'];
            $data['times'] = '';
        }

        switch ($side) {
            case 0:
                $this->_drawFront($data);
                break;
            case 1:
                $this->_drawBack($data);
                break;
            default:
                user_error("unknown side '$side'", E_USER_ERROR);
        }
    }

    protected function _drawFront(array $data): void
    {
        $this->cardFront($data['type'] . ' Imbue Power Card');
        $this->QRcode();

        $this->pdf->SetTextColor(31);

        $this->font(5);
        $this->text(57.5, 2, 10, 'R', $data['key']);

        $this->font(6);
        $this->text(0, 28, 12, 'R', 'PLIN');
        $this->text(0, 33, 12, 'R', 'Character');
        $this->text(0, 38, 12, 'R', 'Cost');

        $this->pdf->SetTextColor(0);

        $this->font(11, 'B');
        $this->text(0, 13, 12, 'R', $data['times']);
        $this->text(52, 5, 16, 'R', $data['id']);
        $this->textarea(12, 13, 60, 17, $data['name']);
        $this->text(12, 28, 44, 'L', $data['plin']);
        $this->text(12, 33, 44, 'L', $data['char']);
        $this->text(12, 38, 44, 'L', $data['total']);
    }

    protected function _drawBack(array $data): void
    {
        $this->cardBack('Description');

        $this->pdf->SetTextColor(0);
        $this->square(8, 5, 72, 42);
        $this->font(6);
        $this->textarea(8, 7, 63.5, 37, $data['text']);
    }
}
