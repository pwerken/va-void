<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use Authentication\Authenticator\UnauthenticatedException;
use Authorization\IdentityInterface as User;
use Cake\Http\ServerRequest;

use App\Policy\AppPolicy;

class AppControllerPolicy
    extends AppPolicy
{
    public function canAccess(?User $identity, ServerRequest $request): bool
    {
        $this->setIdentity($identity);
        $action = $request->getParam('action');

        $allowed = $this->{$action}();
        if(!$allowed and is_null($identity)) {
            throw new UnauthenticatedException();
        }
        return $allowed;
    }
}
