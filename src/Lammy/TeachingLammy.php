<?php
declare(strict_types=1);

namespace App\Lammy;

class TeachingLammy extends LammyCard
{
    public function cards(): int
    {
        return 1;
    }

    public function sides(): int
    {
        return 1;
    }

    public function draw(int $side): void
    {
        switch ($side) {
            case 0:
                $this->_drawFront();
                break;
            default:
                user_error("unknown side '$side'", E_USER_ERROR);
        }
    }

    protected function _drawFront(): void
    {
        $this->cardFront();
        $this->QRcode();

        $this->pdf->SetTextColor(0);
        $this->font(9);
        $this->text(48, 3, 20, 'C', 'Teaching');
        $this->text(48, 7, 20, 'C', 'Card');

        $this->pdf->SetTextColor(63);
        $this->font(5);
        $this->text(10, 16.5, 12, 'R', 'PLIN');
        $this->text(21, 16.5, 7, 'C', 'CHIN');
        $this->text(28, 16.5, 47, 'L', 'Name');

        $this->pdf->SetTextColor(31);
        $this->font(6);
        $this->text(0, 13, 12, 'R', 'Teacher');
        $this->text(0, 20, 12, 'R', 'Student');
        $this->text(0, 27, 12, 'R', 'Skill Name');
        $this->text(0, 33, 12, 'R', 'Started');

        $this->font(8, 'B');
        $this->text(0, 4, 8, 'C', 'TXP');
        $this->text(0, 41, 8, 'C', 'XP');

        $this->square(0, 0, 16, 8);
        $this->square(0, 37, 16, 45);

        $this->pdf->SetTextColor(63);
        $this->font(26, 'B');

        [$txp, $xp] = $this->entity->progress();
        for ($i = 0; $i < 6; $i++) {
            $x = $i * 8;
            $this->square($x, 0, 8 + $x, 8);
            $this->square($x, 37, 8 + $x, 45);

            if ($i < $txp) {
                $this->text(8 + $x, 4.6, 8, 'C', 'X');
            }
            if ($i < $xp) {
                $this->text(8 + $x, 41.5, 8, 'C', 'X');
            }
        }

        $this->pdf->SetTextColor(0);
        $this->font(11, 'B');

        $p = $this->entity->teacher;
        $this->text(10, 13, 12, 'R', $p->player_id);
        $this->text(21, 13, 7, 'C', sprintf('%02d', $p->chin));
        $this->text(28, 13, 47, 'L', $p->name);
        $p = $this->entity->student;
        $this->text(10, 20, 12, 'R', $p->player_id);
        $this->text(21, 20, 7, 'C', sprintf('%02d', $p->chin));
        $this->text(28, 20, 47, 'L', $p->name);

        $this->text(12, 27, 60, 'L', $this->entity->skill->name);
        $this->text(12, 33, 60, 'L', $this->entity->started);
    }
}
