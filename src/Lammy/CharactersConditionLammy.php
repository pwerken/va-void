<?php
namespace App\Lammy;

class CharactersConditionLammy
	extends LammyCard
{

	public function sides()
	{
		return 2;
	}

	public function draw($side, $data = null)
	{
		if(is_null($data)) {
			$data = [];
			$data['type'] = 'Condition';
			$data['key']  = 'COIN';
			$data['id']   = $this->entity->condition_id;
			$data['name'] = $this->entity->condition->name;
			$data['text'] = $this->entity->condition->player_text;
		}

		switch($side) {
		case 0:		$this->_drawFront($data);	break;
		case 1:		$this->_drawBack($data);	break;
		default:	user_error("unknown side '$side'", E_USER_ERROR);
		}
	}

	protected function _drawFront($data)
	{
		$this->border();
		$this->logo(68, 1);
		$this->title($data['type'].' Card');
		$this->footer('(c) Vortex Adventures');

		$this->pdf->SetTextColor(63);

		$this->font(5);
		$this->text(57.5, 2, 10, 'R', $data['key']);

		$this->font(6);
		$this->text( 0, 13, 12, 'R', $data['type']);
		$this->text( 0, 28, 12, 'R', 'PLIN');
		$this->text( 0, 33, 12, 'R', 'Character');
		$this->text( 0, 38, 12, 'R', 'Expiry');

		$plin = $this->entity->character->player_id
			. ' - ' . $this->entity->character->chin;
		$char = $this->entity->character->name;

		$expiry = $this->entity->expiry ?: 'Until death';
		if(!is_string($expiry)) $expiry = $expiry->jsonSerialize();

		$this->pdf->SetTextColor(0);

		$this->font(11, 'B');
		$this->text(57.5, 5, 60, 'L', $data['id']);
		$this->textblock(12, 13, 60, 'L', $data['name']);
		$this->text(12, 28, 60, 'L', $plin);
		$this->text(12, 33, 60, 'L', $char);
		$this->text(12, 38, 60, 'L', $expiry);
	}

	protected function _drawBack($data)
	{
		$this->border();
		$this->logo(1, 1);
		$this->title('Description');
		$this->footer(date('G:i d/m/Y'));

		$this->square(8, 5, 72, 42);

		$this->pdf->SetTextColor(0);
		$this->font(6);
		$this->textblock(8, 7, 64, 'L', $data['text']);
	}

}
