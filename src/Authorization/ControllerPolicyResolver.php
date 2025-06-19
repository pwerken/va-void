<?php
declare(strict_types=1);

namespace App\Authorization;

use Authorization\Policy\Exception\MissingPolicyException;
use Authorization\Policy\ResolverInterface;
use Cake\Core\App;
use Cake\Http\ServerRequest;

class ControllerPolicyResolver implements ResolverInterface
{
    /**
     * Used by the RequestAuthorizationMiddleware.
     * Returns the policy beloging to the controller of the ServerRequest.
     */
    public function getPolicy(mixed $resource): mixed
    {
        if (!($resource instanceof ServerRequest)) {
            throw new MissingPolicyException($resource ?? 'null');
        }

        $name = $resource->getParam('controller') . 'Controller';
        $type = 'Policy/Controller';

        $prefix = $resource->getParam('prefix');
        if (!empty($prefix)) {
            $type .= '/' . $prefix;
        }

        $policyClass = App::className($name, $type, 'Policy');
        if (is_null($policyClass)) {
            throw new MissingPolicyException($name);
        }

        return new $policyClass();
    }
}
