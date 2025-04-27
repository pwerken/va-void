<?php
declare(strict_types=1);

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Mailer\Mailer;
use Cake\Routing\Router;

use App\Model\Entity\SocialProfile;

class MailerComponent
    extends Component
{
    public function socialLogin(SocialProfile $profile): void
    {
        if(!$profile->isNew() || $profile->user_id) {
            return;
        }

        $provider = $profile['provider'];
        $user = $profile['username'] ?? $profile['full_name'];
        $email = $profile['email'];
        $auth = Router::pathUrl('Admin::authentication', [], true);

        $mailer = new Mailer('default');
        $mailer
            ->setReplyTo($email)
            ->setSubject('Social login has no associated plin')
            ->deliver( <<<MESSAGE
Social login attempt with new email adres.
 from: $provider
 user: $user
 mail: $email

Authenticate the login and associate it with a plin.
$auth

Afterwards reply to this message to inform the
player that the social login should now work.

-- VOID api
MESSAGE );
    }
}
