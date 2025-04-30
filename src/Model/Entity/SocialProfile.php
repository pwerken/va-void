<?php
declare(strict_types=1);

namespace App\Model\Entity;

class SocialProfile extends Entity
{
    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['id', 'provider', 'email', 'created', 'modified']);

        $this->setHidden(['identifier'], true);
    }

    public function getUrl(): string
    {
        return '/players/' . $this->get('user_id') . '/socials/' . $this->id;
    }
}
