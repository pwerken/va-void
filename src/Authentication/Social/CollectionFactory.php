<?php
declare(strict_types=1);

namespace App\Authentication\Social;

use SocialConnect\Auth\CollectionFactory as BaseCollectionFactory;
use SocialConnect\OpenIDConnect\Provider\Apple as AppleProvider;
use SocialConnect\OpenIDConnect\Provider\Google as GoogleProvider;

class CollectionFactory extends BaseCollectionFactory
{
    // phpcs:ignore SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingAnyTypeHint
    protected $providers = [
        AppleProvider::NAME => AppleProvider::class,
        DiscordProvider::NAME => DiscordProvider::class,
        GitLabProvider::NAME => GitLabProvider::class,
        GoogleProvider::NAME => GoogleProvider::class,
    ];

    public function getProviders(): array
    {
        return $this->providers;
    }
}
