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

        $expiry = $this->entity->expiry ?: 'Permanent';
        if (!is_string($expiry)) {
            $expiry = $expiry->jsonSerialize();
        }

        $this->font(11, 'B');
        $this->text(57.5, 5, 10, 'R', $this->entity->id);

        $this->font(8);
        $this->textarea(12, 15, 60, 24, $this->entity->description);

        $this->font(8, 'B');
        $this->text(12, 10, 60, 'L', $this->entity->name);
        $this->text(12, 43, 60, 'L', $expiry);

        if ($this->entity->character) {
            $owner = $this->entity->character->player_id;
            $owner .= ' - ' . $this->entity->character->chin;
        } else {
            $owner = 'unknown';
        }
        $this->inMargin('owner: ' . $owner);
    }

    protected function _drawPlayer(): void
    {
        $this->cardBack('Item Explanation');

        $this->pdf->SetTextColor(0);
        $this->square(8, 5, 72, 42);
        $this->font(6);
        $this->textarea(8, 7, 64, 37, $this->entity->player_text);

        if (!is_null($this->entity->character)) {
            $char_id = $this->entity->character->player_id
                        . ' - ' . $this->entity->character->chin;
        } else {
            $char_id = 'PLIN';
        }
        $this->pdf->SetTextColor(191);
        $this->font(5);
        $this->text(8, self::$HEIGHT - 1.3, self::$WIDTH, 'L', $char_id);
    }
}
