<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use App\Authentication\UnauthenticatedException;
use App\Model\Entity\Entity;
use App\Policy\Policy;
use Authorization\IdentityInterface as User;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\ServerRequest;

class ControllerPolicy extends Policy
{
    private ?ServerRequest $request;

    public function canAccess(?User $identity, ServerRequest $request): bool
    {
        $this->request = $request;
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

    protected function hasRoleUser(int $plin, ?Entity $obj): bool
    {
        return $this->request->getParam('plin') == $plin;
    }
}
