<?php
declare(strict_types=1);

namespace App\Authorization;

use Authorization\Policy\Exception\MissingPolicyException;
use Authorization\Policy\ResolverInterface;
use Cake\Core\App;
use Cake\Datasource\EntityInterface;

class EntityPolicyResolver implements ResolverInterface
{
    /**
     * Used by the RequestAuthorizationMiddleware.
     */
    public function getPolicy(mixed $resource): mixed
    {
        if (!($resource instanceof EntityInterface)) {
            throw new MissingPolicyException($resource ?? 'null');
        }

        $name = get_class($resource);
        $name = substr($name, strrpos($name, '\\') + 1);

        $policyClass = App::className($name, 'Policy/Entity', 'Policy');
        if (is_null($policyClass)) {
            throw new MissingPolicyException($name);
        }

        return new $policyClass();
    }
}
