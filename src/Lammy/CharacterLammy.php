<?php
namespace App\Lammy;

use FPDF;
use App\Model\Entity\Character;

class CharacterLammy
	extends Lammy
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
		$this->text( 0, 12, 15, 'R', 'PLIN');
		$this->text(20, 12, 15, 'R', 'CHIN');

		$this->pdf->SetFont('Arial', '', 6);
		$this->text( 0, 20, 15, 'R', 'Name');
		$this->text( 0, 28, 15, 'R', 'Character');
		$this->text( 0, 34, 15, 'R', 'Group');
		$this->text( 0, 40, 15, 'R', 'Faction');

		$this->pdf->SetTextColor(0);
		$this->pdf->SetFont('Arial', 'B', 11);
		$this->text(15, 12, 20, 'L', $this->entity->player_id);
		$this->text(35, 12, 20, 'L', $this->entity->chin);
		$this->text(15, 20, 60, 'L', $this->entity->player->fullName);
		$this->text(15, 28, 60, 'L', $this->entity->name);
		$this->text(15, 34, 60, 'L', $this->entity->group->name);
		$this->text(15, 40, 60, 'L', $this->entity->faction->name);
	}

	private function drawBack()
	{
		$data = [];
		$data['xp'] = 0;
		$data['skill'] = [];
		$data['mana'] = [];

		foreach($this->entity->skills as $skill) {
			$data['xp'] += $skill->cost;
			$data['skills'][] = $skill->name.' ('.$skill->cost.')';

			if(!isset($skill->manatype))
				continue;

			if(!isset($data['mana'][$skill->manatype->name]))
				$data['mana'][$skill->manatype->name] = 0;
			$data['mana'][$skill->manatype->name] += $skill->mana_amount;
		}

		$this->border();
		$this->logo(1, 1);
		$this->title('Character Card');
		$this->footer(date('G:i d/m/Y'));

		$this->square(8,  5, 72, 39);
		$this->square(8, 39, 72, 42);

		$this->pdf->SetFont('Arial', '', 6);
		$this->pdf->SetTextColor(0);

		// xp
		$this->text( 9, 40.7, 21, 'R', 'Spent Points:');
		$this->text(30, 40.7, 10, 'L', $data['xp']);
		$this->text(40, 40.7, 21, 'R', 'Total Points:');
		$this->text(61, 40.7, 10, 'L', $this->entity->xp);

		// skills: max 6 regels
		$this->textblock(8, 5.5, 64, 'L', implode(', ', $data['skills']));

		// mana: max 2 regels
		$this->textblock(8, 24.5, 64, 'L'
			,   "xx Elemental"
			. ", xx Spiritual"
			. ", xx Healing"
			. ", xx Ritualist (Elemental)"
			. ", xx Ritualist (Spiritual)"
			. ", xx Demonology"
			. ", xx Necromancy"
		);
		// spheres: max 3 regels
		$this->textblock(8, 31, 64, 'C'
			,   "xx Body/Life"
			. ", xx Chaos/Destruction"
			. ", xx Civilization/Order"
			. ", xx Death/Soul"
			. ", xx Evil/Darkness"
			. ", xx Good/Light"
			. ", xx Mind/Knowledge"
			. ", xx Nature/Creation"
		);
	}

#	Mana
#		Elemental
#		Elemental Ritualist
#		Spiritual
#		Spiritual Ritualist
#		Healing
#		Demonology
#		Necromancy
#
#	Spheres
#		Body/Life
#		Chaos/Destruction
#		Civilization/Order
#		Death/Soul
#		Evil/Darkness
#		Good/Light
#		Mind/Knowledge
#		Nature/Creation
#
#	Elements
#		Air
#		Earth
#		Fire
#		Metal
#		Water
#		Wood
}
