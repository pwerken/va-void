<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Authentication\UnauthenticatedException;
use App\Policy\Policy;
use Authorization\IdentityInterface as User;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\ServerRequest;

class ControllerPolicy extends Policy
{
    public function canAccess(?User $identity, ServerRequest $request): bool
    {
        $this->setIdentity($identity);
        $action = $request->getParam('action');

        if (method_exists($this, $action)) {
            $allowed = $this->{$action}();
            if (!$allowed && is_null($identity)) {
                throw new UnauthenticatedException();
            }

            return $allowed;
        }
        if (is_null($identity)) {
            throw new UnauthenticatedException();
        }
        throw new NotFoundException();
    }
}
