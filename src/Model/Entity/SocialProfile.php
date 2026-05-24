<?php
declare(strict_types=1);

namespace App\Model\Entity;

/**
 * @property int                    $plin
 * @property ?int                   $user_id
 * @property bool                   $hidden
 * @property string                 $provider
 * @property string                 $identifier
 * @property ?string                $username
 * @property ?string                $full_name
 * @property ?string                $email
 * @property ?\Cake\I18n\DateTime   $created
 * @property ?\Cake\I18n\DateTime   $modified
 */
class SocialProfile extends Entity
{
    protected array $_defaults = [ 'hidden' => false ];

    public function __construct(array $properties = [], array $options = [])
    {
        parent::__construct($properties, $options);

        $this->setCompact(['id', 'provider', 'email', 'created', 'modified']);

        $this->setHidden(['identifier', 'hidden'], true);
    }

    public function getUrl(): string
    {
        return '/players/' . $this->user_id . '/socials/' . $this->id;
    }
}
