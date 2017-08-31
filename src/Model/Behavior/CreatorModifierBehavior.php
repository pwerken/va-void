<?php
namespace App\Model\Behavior;

use App\Utility\AuthState;

class CreatorModifierBehavior
	extends \CreatorModifier\Model\Behavior\CreatorModifierBehavior
{
	public function getUserId()
	{
		return AuthState::getId();
	}
}
