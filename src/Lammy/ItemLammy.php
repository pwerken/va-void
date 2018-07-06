<?php
namespace App\Lammy;

class ItemLammy
	extends LammyCard
{

	public function cards()
	{
		return 2;
	}

	public function sides()
	{
		return 3;
	}

	public function draw($side)
	{
		switch($side) {
		case 0:		$this->_drawFront();	break;
		case 1:		$this->_drawCodes();	break;
		case 2:		$this->_drawPlayer();	break;
		default:	user_error("unknown side '$side'", E_USER_ERROR);
		}
	}

	protected function _drawFront()
	{
		$this->cardFront('Item Card');
		$this->QRcode();

		$this->pdf->SetTextColor(31);

		$this->font(5);
		$this->text(57.5, 2, 10, 'R', 'ITIN');

		$this->font(6);
		$this->text( 0, 10, 12, 'R', 'Name');
		$this->text( 0, 15, 12, 'R', 'Description');
		$this->text( 0, 43, 12, 'R', 'Expiry');

		$this->font(4);
		$this->textblock(0, 37, 58, 'L', "Contact CS on receiving item. "
			. "Physrep must be returned to organisation. "
			. "Not reporting card will be seen as foul play. "
			. "Not reporting physrep will be seen as out of character theft."
			);

		$this->pdf->SetTextColor(0);

		$expiry = $this->entity->expiry ?: 'Permanent';
		if(!is_string($expiry)) $expiry = $expiry->jsonSerialize();

		$this->font(11, 'B');
		$this->text(57.5, 5, 10, 'R', $this->entity->id);

		$this->font(8);
		$this->textblock(12, 15, 60, 'L', $this->entity->description);

		$this->font(8, 'B');
		$this->text(12, 10, 60, 'L', $this->entity->name);
		$this->text(12, 43, 60, 'L', $expiry);
	}

	protected function _drawCodes()
	{
		$this->cardBack('Lore codes');

		$this->pdf->SetFont('Courier', 'B', 11);
		$this->pdf->SetTextColor(0);
		$this->square(8, 5, 72, 42);

		$codes = $this->entity->codes();
		for($row = 0; $row < 3; $row++) {
			for($col = 0; $col < 4; $col++) {
				$code = $codes[$col + $row * 4];
				$this->text(9.5 + 15 * $col, 14 + 9 * $row, 15, 'C', $code);
			}
		}
	}

	protected function _drawPlayer()
	{
		$this->cardFront('Item Explanation');

		$this->pdf->SetTextColor(31);
		$this->font(5);
		$this->text(57.5, 2, 10, 'R', 'ITIN');
		$this->font(6);
		$this->text( 2, 10, 30, 'L', 'Explanation for Player');

		$this->pdf->SetTextColor(0);
		$this->square( 3, 12.5, 72, 42);
		$this->font(11, 'B');
		$this->text(57.5, 5, 10, 'R', $this->entity->id);
		$this->font(6);
		$this->textblock( 3, 15, 69, 'L', $this->entity->player_text);

		if(!is_null($this->entity->character)) {
			$char_id = $this->entity->character->player_id
						. ' - ' . $this->entity->character->chin;
		} else {
			$char_id = '';
		}
		$this->pdf->SetTextColor(191);
		$this->font(5);
		$this->text(3, self::$HEIGHT - 1.3, self::$WIDTH, 'L', $char_id);
	}

}
