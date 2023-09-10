<?php
namespace App\Lammy;

class CharacterLammy
	extends LammyCard
{

	public function draw($side)
	{
		switch($side) {
		case 0:		$this->_drawFront();		break;
		case 1:		$this->_drawBack();		break;
		default:	user_error("unknown side '$side'", E_USER_ERROR);
		}
	}

	protected function _drawFront()
	{
		$this->cardFront('Character Card');
		$this->QRcode();

		$this->pdf->SetTextColor(31);

		$this->font(5);
		$this->text(52,  2, 10, 'R', 'PLIN');
		$this->text(61,  2,  7, 'C', 'CHIN');

		$this->font(6);
		$this->text( 0, 10, 12, 'R', 'Name');
		$this->text( 0, 16, 12, 'R', 'Character');
		$this->text( 0, 23, 12, 'R', 'Faction');
		$this->text( 0, 28, 12, 'R', 'Belief');
		$this->text( 0, 33, 12, 'R', 'Group');
		$this->text( 0, 38, 12, 'R', 'World');

		$this->pdf->SetTextColor(0);
		$this->font(11, 'B');
		$this->text(52,  5, 10, 'R', $this->entity->player_id);
		$this->text(61,  5,  7, 'C', sprintf('%02d', $this->entity->chin));
		$this->text(12, 10, 60, 'L', $this->entity->player->fullName);
		$this->text(12, 16, 60, 'L', $this->entity->name);
		$this->text(12, 23, 60, 'L', $this->entity->faction);

		if(!empty($this->entity->soulpath)) {
			$this->text(56, 22, 18, 'L', $this->entity->soulpath);
		}
		$this->text(12, 28, 44, 'L', $this->entity->belief);
		$this->text(12, 33, 44, 'L', $this->entity->group);
		$this->text(12, 38, 44, 'L', $this->entity->world);
	}

	protected function _drawBack()
	{
		$data = [];
		$data['xp'] = 0;
		$data['mana'] = [];
		$data['skills'] = [];
		$data['spells'] = [[], []];

		foreach($this->entity->skills as $relation) {
			$skill = $relation->skill;

			$name = $skill->name;
			if($skill->loresheet && $skill->blanks) {
			$name .= '(lore & blanks)';
			} elseif($skill->loresheet) {
				$name .= '(lore)';
			} elseif($skill->blanks) {
				$name .= '(blanks)';
			}
			$name .= ' ('.$skill->cost.')';

			$data['xp'] += $skill->cost;
			$data['skills'][] = $name;

			if(!isset($skill->manatype))
				continue;

			if(!isset($data['mana'][$skill->manatype->name]))
				$data['mana'][$skill->manatype->name] = 0;
			$data['mana'][$skill->manatype->name] += $skill->mana_amount;
		}
		foreach($this->entity->spells as $relation) {
			$spell = $relation->spell;
			$descr = $spell->short.': '.$relation->level;
			$data['spells'][$spell->spiritual][] = $descr;
		}

		$this->cardBack('Skills');

		$this->square( 8,  5, 72, 36);
		$this->square( 8, 36, 56, 42);
		$this->square(56, 36, 72, 42);

		$this->font(6);
		$this->pdf->SetTextColor(0);

		// skills
		$this->textblock(8, 7, 64, 'L', implode(', ', $data['skills']));

		// xp
		$this->text(56, 37.7, 16, 'C', 'Experience');
		$this->text(56, 40.7, 16, 'C', $data['xp'].' / '.$this->entity->xp);

		$g = 159;

        // mana
		$this->pdf->SetTextColor(@$data['mana']['Elemental'] ? 0 : $g);
		$this->text( 8, 37.7, 13, 'R', 'Elemantal:');
		$this->text(20, 37.7,  5, 'C', @$data['mana']['Elemental'] ?: 0);
		$this->pdf->SetTextColor(@$data['mana']['Elemental Ritual'] ? 0 : $g);
		$this->text( 8, 40.7, 13, 'R', 'Elem.Rit.:');
		$this->text(20, 40.7,  5, 'C', @$data['mana']['Elemental Ritual'] ?: 0);

		$this->pdf->SetTextColor(@$data['mana']['Spiritual'] ? 0 : $g);
		$this->text(24, 37.7, 13, 'R', 'Spiritual:');
		$this->text(36, 37.7,  5, 'C', @$data['mana']['Spiritual'] ?: 0);
		$this->pdf->SetTextColor(@$data['mana']['Spiritual Ritual'] ? 0 : $g);
		$this->text(24, 40.7, 13, 'R', 'Spir.Rit.:');
		$this->text(36, 40.7,  5, 'C', @$data['mana']['Spiritual Ritual'] ?: 0);

		$this->pdf->SetTextColor(@$data['mana']['Inspiration'] ? 0 : $g);
		$this->text(39, 37.7, 13, 'R', 'Inspiration:');
		$this->text(51, 37.7,  5, 'C', @$data['mana']['Inspiration'] ?: 0);
		$this->pdf->SetTextColor(@$data['mana']['Willpower'] ? 0 : $g);
		$this->text(39, 40.7, 13, 'R', 'Willpower:');
		$this->text(51, 40.7,  5, 'C', @$data['mana']['Willpower'] ?: 0);
	}

}
