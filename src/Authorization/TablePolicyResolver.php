<?php
declare(strict_types=1);

namespace App\Authorization;

use Authorization\Policy\Exception\MissingPolicyException;
use Authorization\Policy\ResolverInterface;
use Cake\Core\App;
use Cake\ORM\Query;
use Cake\ORM\Table;

class TablePolicyResolver
    implements ResolverInterface
{
    /**
     * Used by the RequestAuthorizationMiddleware.
     */
    public function getPolicy(mixed $resource): mixed
    {
        if ($resource instanceof Query) {
            $repo = $resource->getRepository();
            if ($repo === null) {
                throw new RuntimeException('No repository set for the query.');
            }
            $resource = $repo;
        }
        if (!($resource instanceof Table)) {
            throw new MissingPolicyException($resource);
        }

        $name = get_class($resource);
        $name = substr($name, strrpos($name, '\\') + 1);

        $policyClass = App::className($name, 'Policy/Table', 'Policy');
        if ($policyClass === null) {
            throw new MissingPolicyException($name);
        }

        return new $policyClass();
    }
}
