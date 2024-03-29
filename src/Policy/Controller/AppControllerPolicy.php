<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use Authorization\IdentityInterface as User;
use Cake\Http\ServerRequest;
use Cake\Http\Exception\NotFoundException;

use App\Authentication\UnauthenticatedException;
use App\Policy\AppPolicy;

class AppControllerPolicy
    extends AppPolicy
{
    public function canAccess(?User $identity, ServerRequest $request): bool
    {
        $this->setIdentity($identity);
        $action = $request->getParam('action');

        if(method_exists($this, $action)) {
            $allowed = $this->{$action}();
            if(!$allowed and is_null($identity)) {
                throw new UnauthenticatedException();
            }
            return $allowed;
        }
        if(is_null($identity)) {
            throw new UnauthenticatedException();
        }
        throw new NotFoundException();
    }
}
