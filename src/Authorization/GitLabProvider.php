<?php
declare(strict_types=1);

namespace App\Authorization;

use SocialConnect\Common\ArrayHydrator;
use SocialConnect\Common\Entity\User;
use SocialConnect\OAuth2\Provider\GitLab;
use SocialConnect\Provider\AccessTokenInterface;

class GitLabProvider
    extends GitLab
{
    /**
     * {@inheritdoc}
     */
    public function getIdentity(AccessTokenInterface $accessToken)
    {
        $response = $this->request('GET', 'user', [], $accessToken);

        $hydrator = new ArrayHydrator([
            'id' => 'id',
            'name' => 'fullname',
            'email' => 'email',
        ]);

        return $hydrator->hydrate(new User(), $response);
    }
}
