<?php
declare(strict_types=1);

namespace App\Lammy;

class CharactersConditionLammy extends LammyCard
{
    public function draw(int $side, ?array $data = null): void
    {
        if (is_null($data)) {
            $data = [];
            $data['type'] = 'Condition';
            $data['key'] = 'COIN';
            $data['id'] = $this->entity->condition_id;
            $data['name'] = $this->entity->condition->name;
            $data['text'] = $this->entity->condition->player_text;
            $data['plin'] = $this->entity->character->player_id . ' - ' . $this->entity->character->chin;
            $data['char'] = $this->entity->character->name;

            $expiry = $this->entity->expiry ?: 'Until death';
            if (!is_string($expiry)) {
                $expiry = (string)$expiry;
            }
            $data['expiry'] = $expiry;
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
        $this->cardFront($data['type'] . ' Card');
        $this->QRcode();

        $this->pdf->SetTextColor(31);

        $this->font(5);
        $this->text(57.5, 2, 10, 'R', $data['key']);

        $this->font(6);
        $this->text(0, 13, 12, 'R', $data['type']);
        $this->text(0, 28, 12, 'R', 'PLIN');
        $this->text(0, 33, 12, 'R', 'Character');
        $this->text(0, 38, 12, 'R', 'Expiry');

        $this->pdf->SetTextColor(0);

        $this->font(11, 'B');
        $this->text(57.5, 5, 60, 'L', $data['id']);
        $this->textarea(12, 13, 60, 17, $data['name']);
        $this->text(12, 28, 44, 'L', $data['plin']);
        $this->text(12, 33, 44, 'L', $data['char']);
        $this->text(12, 38, 44, 'L', $data['expiry']);
    }

    protected function _drawBack(array $data): void
    {
        $this->cardBack('Description');

        $this->pdf->SetTextColor(0);
        $this->square(8, 5, 72, 42);
        $this->font(6);
        $this->textarea(8, 7, 64, 37, $data['text']);
    }
}
