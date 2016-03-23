<?php
namespace App\Lammy;

use FPDF;
use App\Model\Entity\Character;

class CharacterLammy
	extends LammyCard
{

	public function __construct(Character $character)
	{
		parent::__construct($character);
	}

	public function sides()
	{
		return 2;
	}

	public function draw($side)
	{
		switch($side) {
		case 0:		$this->drawFront();		break;
		case 1:		$this->drawBack();		break;
		default:	user_error("unknown side '$side'", E_USER_ERROR);
		}
	}

	private function drawFront()
	{
		$this->border();
		$this->logo(68, 1);
		$this->title('Character Card');
		$this->footer('(c) Vortex Adventures');

		$this->pdf->SetTextColor(63);

		$this->pdf->SetFont('Arial', '', 5);
		$this->text(52,  2, 10, 'R', 'PLIN');
		$this->text(61,  2,  7, 'C', 'CHIN');

		$this->pdf->SetFont('Arial', '', 6);
		$this->text( 0, 10, 11.5, 'R', 'Name');
		$this->text( 0, 16, 11.5, 'R', 'Character');
		$this->text( 0, 23, 11.5, 'R', 'Faction');
		$this->text( 0, 28, 11.5, 'R', 'Belief');
		$this->text( 0, 33, 11.5, 'R', 'Group');
		$this->text( 0, 38, 11.5, 'R', 'Faction');

		$this->pdf->SetTextColor(0);
		$this->pdf->SetFont('Arial', 'B', 11);
		$this->text(52,  5, 10, 'R', $this->entity->player_id);
		$this->text(61,  5,  7, 'C', sprintf('%02d', $this->entity->chin));
		$this->text(11, 10, 60, 'L', $this->entity->player->fullName);
		$this->text(11, 16, 60, 'L', $this->entity->name);
		$this->text(11, 23, 60, 'L', $this->entity->faction->name);
		$this->text(11, 28, 60, 'L', $this->entity->belief->name);
		$this->text(11, 33, 60, 'L', $this->entity->group->name);
		$this->text(11, 38, 60, 'L', $this->entity->world->name);
	}

	private function drawBack()
	{
		$data = [];
		$data['xp'] = 0;
		$data['mana'] = [];
		$data['skills'] = [];
		$data['spells'] = [[], []];

		foreach($this->entity->skills as $skill) {
			$data['xp'] += $skill->cost;
			$data['skills'][] = $skill->name.' ('.$skill->cost.')';

			if(!isset($skill->manatype))
				continue;

			if(!isset($data['mana'][$skill->manatype->name]))
				$data['mana'][$skill->manatype->name] = 0;
			$data['mana'][$skill->manatype->name] += $skill->mana_amount;
		}
		foreach($this->entity->spells as $spell) {
			$descr = $spell->short.': '.$spell->_joinData['level'];
			$data['spells'][$spell->spiritual][] = $descr;
		}

		$this->border();
		$this->logo(1, 1);
		$this->title('Character Card');
		$this->footer(date('G:i d/m/Y'));

		$this->square( 8,  5, 72, 36);
		$this->square( 8, 36, 56, 42);
		$this->square(56, 36, 72, 42);

		$this->pdf->SetFont('Arial', '', 6);
		$this->pdf->SetTextColor(0);

		// skills
		$this->textblock(8, 5.5, 64, 'L', implode(', ', $data['skills']));

		// elements/spheres
		if(!empty($data['spells'][0])) {
			$str = 'Elements: ' . implode(', ', $data['spells'][0]) ."\n";
		} else {
			$str = "\n";
		}
		if(empty($data['spells'][1])) {
			$str .= "\n".$str;
		} else {
			$str .= 'Spheres: ' . implode(', ', $data['spells'][1]);
		}
		$this->textblock(8, 30.7, 64, 'L', $str);

		// xp
		$this->text(56, 37.7, 16, 'C', 'Experience');
		$this->text(56, 40.7, 16, 'C', $data['xp'].' / '.$this->entity->xp);

		$gray = 159;

		// mana
		$this->pdf->SetTextColor(@$data['mana']['Elemental'] ? 0 : $gray);
		$this->text( 8, 37.7, 13, 'R', 'Elemantal:');
		$this->text(19, 37.7,  5, 'C', @$data['mana']['Elemental'] ?: 0);

		$this->pdf->SetTextColor(@$data['mana']['Spiritual'] ? 0 : $gray);
		$this->text(24, 37.7, 13, 'R', 'Spiritual:');
		$this->text(35, 37.7,  5, 'C', @$data['mana']['Spiritual'] ?: 0);

		$this->pdf->SetTextColor(@$data['mana']['Healing'] ? 0 : $gray);
		$this->text(40, 37.7, 13, 'R', 'Healing:');
		$this->text(51, 37.7,  5, 'C', @$data['mana']['Healing'] ?: 0);

		$this->pdf->SetTextColor(@$data['mana']['Elemental Ritual'] ? 0 : $gray);
		$this->text( 8, 40.7, 13, 'R', 'Elem.Rit.:');
		$this->text(19, 40.7,  5, 'C', @$data['mana']['Elemental Ritual'] ?: 0);

		$this->pdf->SetTextColor(@$data['mana']['Healing'] ? 0 : $gray);
		$this->text(24, 40.7, 13, 'R', 'Spir.Rit.:');
		$this->text(35, 40.7,  5, 'C', @$data['mana']['Spiritual Ritual'] ?: 0);

		$this->pdf->SetTextColor(@$data['mana']['Special'] ? 0 : $gray);
		$this->text(40, 40.7, 13, 'R', 'Special:');
		$this->text(51, 40.7,  5, 'C', @$data['mana']['Special'] ?: 0);
	}

}
