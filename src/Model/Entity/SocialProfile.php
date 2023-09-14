<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class SocialProfile
    extends AppEntity
{
    public function __construct($properties = [], $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['id', 'provider', 'email', 'created', 'modified']);

        $this->setHidden(['identifier'], true);
    }

    public function getUrl()
    {
        return '/players/'.$this->user_id.'/socials/'.$this->id;
    }
}
