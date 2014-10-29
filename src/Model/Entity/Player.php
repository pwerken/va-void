<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Player Entity.
 */
class Player extends Entity {

/**
 * Fields that can be mass assigned using newEntity() or patchEntity().
 *
 * @var array
 */
	protected $_accessible = [
		'account_type' => true,
		'username' => true,
		'password' => true,
		'first_name' => true,
		'insertion' => true,
		'last_name' => true,
		'gender' => true,
		'date_of_birth' => true,
		'characters' => true,
	];

}
