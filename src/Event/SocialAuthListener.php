<?php
declare(strict_types=1);

namespace App\Event;

use ADmad\SocialAuth\Middleware\SocialAuthMiddleware;
use Cake\Datasource\EntityInterface;
use Cake\Event\EventInterface;
use Cake\Event\EventListenerInterface;
use Cake\Http\ServerRequest;
use Cake\Http\Session;
use Cake\ORM\Locator\LocatorAwareTrait;

class SocialAuthListener
    implements EventListenerInterface
{
    use LocatorAwareTrait;

    public function implementedEvents(): array
    {
        return
            [ SocialAuthMiddleware::EVENT_CREATE_USER => 'createUser'
            , SocialAuthMiddleware::EVENT_BEFORE_REDIRECT => 'beforeRedirect'
            ];
    }

    public function beforeRedirect(EventInterface $event, $url, string $status, ServerRequest $request): void
    {
    }

    public function createUser(EventInterface $event, EntityInterface $profile, Session $session): EntityInterface
    {
        $email = $profile->email;
        if(!$email) {
            return $this->unknownPlayer();
        }

        # New login, try to find Player based on email.
        $players = $this->getTableLocator()->get('Players');
        $player = $players->find()->where(['email' => $email])->first();
        if($player) {
            return $player;
        }

        # Fallback, is there another social profile with the same email ?
        $profiles = $this->getTableLocator()->get('ADmad/SocialAuth.SocialProfiles');
        $result = $profiles->find()
                    ->select('user_id', true)
                    ->where(['email' => $email, 'user_id IS NOT NULL'])
                    ->disableHydration()
                    ->first();
        if($result) {
            return $players->get($result['user_id']);
        }

        # unknown email / unlinked social profile login
        return $this->unknownPlayer();
    }

    private function unknownPlayer()
    {
        $players = $this->getTableLocator()->get('Players');
        $unknown = ['first_name' => 'Onbekende', 'last_name' => 'Speler'];
        return $players->newEntity($unknown);
    }
}
