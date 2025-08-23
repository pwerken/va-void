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
        $this->cardFront('Teaching Card');
        $this->QRcode();

        $this->pdf->SetTextColor(31);

        $this->font(5);
        $this->text(52, 2, 10, 'R', 'PLIN');
        $this->text(61, 2, 7, 'C', 'CHIN');

        $this->font(6);
        $this->text(0, 13, 12, 'R', 'Teacher');
        $this->text(0, 20, 12, 'R', 'Student');
        $this->text(0, 27, 12, 'R', 'Skill Name');
        $this->text(0, 34, 12, 'R', 'XP');

        $this->pdf->SetTextColor(0);
        $this->font(11, 'B');

        $p = $this->entity->get('teacher');
        $this->text(10, 13, 12, 'R', $p->plin);
        $this->text(21, 13, 7, 'C', sprintf('%02d', $p->chin));
        $this->text(28, 13, 47, 'L', $p->name);

        $p = $this->entity->get('student');
        $this->text(52, 5, 10, 'R', $p->plin);
        $this->text(61, 5, 7, 'C', sprintf('%02d', $p->chin));
        $this->text(10, 20, 12, 'R', $p->plin);
        $this->text(21, 20, 7, 'C', sprintf('%02d', $p->chin));
        $this->text(28, 20, 47, 'L', $p->name);

        $s = $this->entity->get('skill');
        $this->text(12, 27, 60, 'L', $s->name);
        $progress = $this->entity->get('progress') . ' of ' . $s->cost;
        $this->text(12, 34, 60, 'L', $progress);
    }
}
