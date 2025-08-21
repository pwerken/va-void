<?php
declare(strict_types=1);

namespace App\Lammy;

use App\Utility\SkillNameGroup;

class CharacterLammy extends LammyCard
{
    public function draw(int $side): void
    {
        switch ($side) {
            case 0:
                $this->_drawFront();
                break;
            case 1:
                $this->_drawBack();
                break;
            default:
                user_error("unknown side '$side'", E_USER_ERROR);
        }
    }

    protected function _drawFront(): void
    {
        $this->cardFront('Character Card');
        $this->QRcode();

        $this->pdf->SetTextColor(31);

        $this->font(5);
        $this->text(52, 2, 10, 'R', 'PLIN');
        $this->text(61, 2, 7, 'C', 'CHIN');

        $this->font(6);
        $this->text(0, 10, 12, 'R', 'Name');
        $this->text(0, 16, 12, 'R', 'Character');
        $this->text(0, 23, 12, 'R', 'Faction');
        $this->text(0, 28, 12, 'R', 'Belief');
        $this->text(0, 33, 12, 'R', 'Group');
        $this->text(0, 38, 12, 'R', 'World');

        $this->pdf->SetTextColor(0);
        $this->font(11, 'B');
        $this->text(52, 5, 10, 'R', $this->entity->get('plin'));
        $this->text(61, 5, 7, 'C', sprintf('%02d', $this->entity->get('chin')));
        $this->text(12, 10, 60, 'L', $this->entity->get('player')->get('name'));
        $this->text(12, 16, 60, 'L', $this->entity->get('name'));
        $this->text(12, 23, 60, 'L', $this->entity->get('faction'));

        $this->text(12, 28, 44, 'L', $this->entity->get('belief'));
        $this->text(12, 33, 44, 'L', $this->entity->get('group'));
        $this->text(12, 38, 44, 'L', $this->entity->get('world'));
    }

    protected function _drawBack(): void
    {
        $data = [];
        $data['xp'] = 0;
        $data['imbue'] = [];
        $data['mana'] = [];
        $data['skills'] = [];

        $data['imbue']['Glyph'] = 0;
        $data['imbue']['Rune'] = 0;
        foreach ($this->entity->get('glyphimbues') as $imbue) {
            $relation = $imbue->_joinData;
            $data['imbue']['Glyph'] += $imbue->get('cost') * $relation->get('times');
        }
        foreach ($this->entity->get('runeimbues') as $imbue) {
            $relation = $imbue->_joinData;
            $data['imbue']['Rune'] += $imbue->get('cost') * $relation->get('times');
        }

        $data['mana']['Elemental'] = $data['mana']['Elemental Ritual'] = 0;
        $data['mana']['Spiritual'] = $data['mana']['Spiritual Ritual'] = 0;
        $data['mana']['Inspiration'] = $data['mana']['Willpower'] = 0;
        $data['mana']['Glyph Imbue Cap'] = $data['mana']['Rune Imbue Cap'] = 0;
        $data['mana'] = $this->entity->get('mana') + $data['mana'];

        foreach ($this->entity->get('skills') as $skill) {
            $relation = $skill->_joinData;
            $times = $relation->get('times');

            $data['xp'] += $times * $skill->get('cost');
            $data['skills'][] = $relation->printableName($skill);
        }
        $data['skills'] = SkillNameGroup::group($data['skills']);

        $this->cardBack('Skills');

        $this->square(8, 5, 72, 33);
        $this->square(8, 33, 72, 42);
        $this->square(40, 33, 57, 42);

        $this->font(6);
        $this->pdf->SetTextColor(0);

        $this->textarea(8, 7, 63.5, 28, implode(', ', $data['skills']));

        $this->text(57, 34.7, 15, 'C', 'Experience');
        $this->text(57, 39.0, 15, 'C', $data['xp'] . ' / ' . $this->entity->get('xp'));

        $g = 159;
        $this->pdf->SetTextColor($data['mana']['Elemental'] > 0 ? 0 : $g);
        $this->text(7.5, 34.7, 6.5, 'R', $data['mana']['Elemental']);
        $this->text(12.5, 34.7, 10, 'L', 'Elemental');
        $this->pdf->SetTextColor($data['mana']['Elemental Ritual'] > 0 ? 0 : $g);
        $this->text(7.5, 37.7, 6.5, 'R', $data['mana']['Elemental Ritual']);
        $this->text(12.5, 37.7, 10, 'L', 'Elem.Rit.');
        $this->pdf->SetTextColor($data['mana']['Inspiration'] > 0 ? 0 : $g);
        $this->text(7.5, 40.7, 6.5, 'R', $data['mana']['Inspiration']);
        $this->text(12.5, 40.7, 10, 'L', 'Inspiration');

        $this->pdf->SetTextColor($data['mana']['Spiritual'] > 0 ? 0 : $g);
        $this->text(23.5, 34.7, 6.5, 'R', $data['mana']['Spiritual']);
        $this->text(28.5, 34.7, 10, 'L', 'Spiritual');
        $this->pdf->SetTextColor($data['mana']['Spiritual Ritual'] > 0 ? 0 : $g);
        $this->text(23.5, 37.7, 6.5, 'R', $data['mana']['Spiritual Ritual']);
        $this->text(28.5, 37.7, 10, 'L', 'Spir.Rit.');
        $this->pdf->SetTextColor($data['mana']['Willpower'] > 0 ? 0 : $g);
        $this->text(23.5, 40.7, 6.5, 'R', $data['mana']['Willpower']);
        $this->text(28.5, 40.7, 10, 'L', 'Willpower');

        /** @var int $glyph */
        $glyph = $data['mana']['Glyph Imbue Cap'];
        /** @var int $rune */
        $rune = $data['mana']['Rune Imbue Cap'];

        $this->pdf->SetTextColor($glyph + $rune > 0 ? 0 : $g);
        $this->pdf->SetTextColor(0);
        $this->text(40, 34.7, 17, 'C', 'Imbue Cap.');
        $this->pdf->SetTextColor($glyph > 0 ? 0 : $g);
        $this->text(42, 37.7, 4, 'R', $data['imbue']['Glyph']);
        $this->text(47, 37.7, 4, 'R', $glyph);
        $this->text(45, 37.7, 1.5, 'C', '/');
        $this->text(49.5, 37.7, 6, 'L', 'Glyph');
        $this->pdf->SetTextColor($rune > 0 ? 0 : $g);
        $this->text(42, 40.7, 4, 'R', $data['imbue']['Rune']);
        $this->text(47, 40.7, 4, 'R', $rune);
        $this->text(45, 40.7, 1.5, 'C', '/');
        $this->text(49.5, 40.7, 6, 'L', 'Rune');
    }
}
