<?php
declare(strict_types=1);

namespace App\Authorization;

use Authorization\Policy\Exception\MissingPolicyException;
use Authorization\Policy\ResolverInterface;
use Cake\Core\App;
use Cake\Datasource\QueryInterface;
use Cake\ORM\Table;
use RuntimeException;

class TablePolicyResolver implements ResolverInterface
{
    /**
     * Used by the RequestAuthorizationMiddleware.
     */
    public function getPolicy(mixed $resource): mixed
    {
        if ($resource instanceof QueryInterface) {
            $resource = $resource->getRepository();
            if (is_null($resource)) {
                throw new RuntimeException('No repository set for the query.');
            }
        }
        if (!($resource instanceof Table)) {
            throw new MissingPolicyException($resource ?? 'null');
        }

        $class = getShortClassName($resource);
        $policyClass = App::className($class, 'Policy/Table', 'Policy');
        if (is_null($policyClass)) {
            throw new MissingPolicyException($class);
        }

        return new $policyClass();
    }
}
