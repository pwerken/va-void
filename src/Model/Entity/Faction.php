<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * @property int                                $id
 * @property string                             $name
 * @property bool                               $deprecated
 * @property ?\Cake\I18n\DateTime               $modified
 * @property ?int                               $modifier_id
 *
 * Relations:
 * @property ?list<\App\Model\Entity\Character> $characters
 */
class Faction extends Entity
{
}
