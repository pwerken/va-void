<?php
declare(strict_types=1);

namespace App\Authentication\Social;

use SocialConnect\OAuth2\Provider\Discord;

class DiscordProvider extends Discord
{
    protected bool $pkce = true;
}
