<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\Database\Schema\TableSchemaInterface;
use Cake\ORM\Table;

class SocialProfilesTable
    extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->addBehavior('Timestamp');
    }
}
