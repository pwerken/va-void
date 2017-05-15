<?php
namespace App\Lammy;

class CharacterLammy
	extends LammyCard
{

	public function sides()
	{
		return 2;
	}

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

		$this->pdf->SetTextColor(63);

		$this->font(5);
		$this->text(52,  2, 10, 'R', 'PLIN');
		$this->text(61,  2,  7, 'C', 'CHIN');

		$this->font(6);
		$this->text( 0, 10, 12, 'R', 'Name');
		$this->text( 0, 16, 12, 'R', 'Character');
		$this->text( 0, 23, 12, 'R', 'Faction');
		$this->text( 0, 28, 12, 'R', 'Belief');
		$this->text( 0, 33, 12, 'R', 'Group');
		$this->text( 0, 38, 12, 'R', 'Faction');

		$this->pdf->SetTextColor(0);
		$this->font(11, 'B');
		$this->text(52,  5, 10, 'R', $this->entity->player_id);
		$this->text(61,  5,  7, 'C', sprintf('%02d', $this->entity->chin));
		$this->text(12, 10, 60, 'L', $this->entity->player->fullName);
		$this->text(12, 16, 60, 'L', $this->entity->name);
		$this->text(12, 23, 60, 'L', $this->entity->faction);

		$belief = $this->entity->belief;
		if(!empty($this->entity->soulpath)) {
			$belief .= ' (' . $this->entity->soulpath . ')';
		}
		$this->text(12, 28, 60, 'L', $belief);
		$this->text(12, 33, 60, 'L', $this->entity->group);
		$this->text(12, 38, 60, 'L', $this->entity->world);
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
			$data['xp'] += $skill->cost;
			$data['skills'][] = $skill->name.' ('.$skill->cost.')';

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

		// elements/spheres
		if(!empty($data['spells'][0])) {
			$str = 'Elements: ' . implode(', ', $data['spells'][0]) ."\n";
		} else {
			$str = "\n";
		}
		if(empty($data['spells'][1])) {
			$str = "\n".$str;
		} else {
			$str .= 'Spheres: ' . implode(', ', $data['spells'][1]);
		}
		$this->textblock(8, 31.2, 64, 'L', $str);

		// xp
		$this->text(56, 37.7, 16, 'C', 'Experience');
		$this->text(56, 40.7, 16, 'C', $data['xp'].' / '.$this->entity->xp);

		$g = 159;

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

		$this->pdf->SetTextColor(@$data['mana']['Healing'] ? 0 : $g);
		$this->text(39, 37.7, 13, 'R', 'Healing:');
		$this->text(51, 37.7,  5, 'C', @$data['mana']['Healing'] ?: 0);
		$this->pdf->SetTextColor(@$data['mana']['Special'] ? 0 : $g);
		$this->text(39, 40.7, 13, 'R', 'Special:');
		$this->text(51, 40.7,  5, 'C', @$data['mana']['Special'] ?: 0);
	}

}
