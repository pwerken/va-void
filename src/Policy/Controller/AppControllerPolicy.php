<?php
declare(strict_types=1);

namespace App\Policy\Controller;

use Authorization\IdentityInterface;
use Cake\Http\ServerRequest;

use App\Policy\AppPolicy;

class AppControllerPolicy
    extends AppPolicy
{

    public function canAccess(?IdentityInterface $identity, ServerRequest $request)
    {
        $this->identity = $identity;
        $action = $request->getParam('action');
        return $this->$action();
    }
}
