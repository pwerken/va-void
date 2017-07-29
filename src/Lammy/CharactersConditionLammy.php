<?php
namespace App\Lammy;

class CharactersConditionLammy
	extends LammyCard
{

	public function draw($side, $data = null)
	{
		if(is_null($data)) {
			$data = [];
			$data['type'] = 'Condition';
			$data['key']  = 'COIN';
			$data['id']   = $this->entity->condition_id;
			$data['name'] = $this->entity->condition->name;
			$data['text'] = $this->entity->condition->player_text;
			$data['plin'] = $this->entity->character->player_id
							. ' - ' . $this->entity->character->chin;
			$data['char'] = $this->entity->character->name;

			$expiry = $this->entity->expiry ?: 'Until death';
			if(!is_string($expiry)) $expiry = $expiry->jsonSerialize();
			$data['expiry'] = $expiry;
		}

		switch($side) {
		case 0:		$this->_drawFront($data);	break;
		case 1:		$this->_drawBack($data);	break;
		default:	user_error("unknown side '$side'", E_USER_ERROR);
		}
	}

	protected function _drawFront($data)
	{
		$this->cardFront($data['type'].' Card');
		$this->QRcode();

		$this->pdf->SetTextColor(31);

		$this->font(5);
		$this->text(57.5, 2, 10, 'R', $data['key']);

		$this->font(6);
		$this->text( 0, 13, 12, 'R', $data['type']);
		$this->text( 0, 28, 12, 'R', 'PLIN');
		$this->text( 0, 33, 12, 'R', 'Character');
		$this->text( 0, 38, 12, 'R', 'Expiry');

		$this->pdf->SetTextColor(0);

		$this->font(11, 'B');
		$this->text(57.5, 5, 60, 'L', $data['id']);
		$this->textblock(12, 13, 60, 'L', $data['name']);
		$this->text(12, 28, 60, 'L', $data['plin']);
		$this->text(12, 33, 60, 'L', $data['char']);
		$this->text(12, 38, 60, 'L', $data['expiry']);
	}

	protected function _drawBack($data)
	{
		$this->cardBack('Description');

		$this->pdf->SetTextColor(0);
		$this->square(8, 5, 72, 42);
		$this->font(6);
		$this->textblock(8, 7, 64, 'L', $data['text']);
	}

}
