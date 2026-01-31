<?php
declare(strict_types=1);

namespace App\Lammy;

class ItemLammy extends LammyCard
{
    public function draw(int $side): void
    {
        switch ($side) {
            case 0:
                $this->_drawFront();
                break;
            case 1:
                $this->_drawPlayer();
                break;
            default:
                user_error("unknown side '$side'", E_USER_ERROR);
        }
    }

    protected function _drawFront(): void
    {
        $this->cardFront('Item Card');
        $this->QRcode();

        $this->pdf->SetTextColor(31);

        $this->font(5);
        $this->text(57.5, 2, 10, 'R', 'ITIN');

        $this->font(6);
        $this->text(0, 10, 12, 'R', 'Name');
        $this->text(0, 15, 12, 'R', 'Description');
        $this->text(0, 43, 12, 'R', 'Expiry');

        $this->font(4);
        $this->textblock(0, 37, 58, 'L', 'Contact CS on receiving item. '
            . 'Physrep must be returned to organisation. '
            . 'Not reporting card will be seen as foul play. '
            . 'Not reporting physrep will be seen as out of character theft.',);

        $this->pdf->SetTextColor(0);

        $expiry = $this->entity->get('expiry') ?: 'Permanent';
        if (!is_string($expiry)) {
            $expiry = (string)$expiry;
        }

        $this->font(11, 'B');
        $this->text(52, 5, 16, 'R', $this->entity->get('itin'));

        $this->font(8);
        $this->textarea(12, 15, 60, 24, $this->entity->get('description'));

        $this->font(8, 'B');
        $this->text(12, 10, 60, 'L', $this->entity->get('name'));
        $this->text(12, 43, 60, 'L', $expiry);

        $character = $this->entity->get('character');
        if ($character === null) {
            $owner = 'unknown';
        } else {
            $owner = $character->get('plin') . ' - ' . $character->get('chin');
        }
        $this->inMargin('owner: ' . $owner);
    }

    protected function _drawPlayer(): void
    {
        $this->cardBack('Item Explanation');

        $this->pdf->SetTextColor(0);
        $this->square(8, 5, 72, 42);
        $this->font(6);
        $this->textarea(8, 7, 64, 37, $this->entity->get('player_text'));

        $character = $this->entity->get('character');
        if ($character === null) {
            $char_id = 'PLIN';
        } else {
            $char_id = $character->get('plin') . ' - ' . $character->get('chin');
        }
        $this->pdf->SetTextColor(127);
        $this->font(5);
        $this->text(8, self::$HEIGHT - 1.3, self::$WIDTH, 'L', $char_id);
    }
}
