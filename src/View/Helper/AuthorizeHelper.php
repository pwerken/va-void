<?php
namespace App\View\Helper;

use Authorization\AuthorizationServiceInterface;
use Authorization\Exception\ForbiddenException;
use Authorization\IdentityInterface;
use Cake\Utility\Hash;
use Cake\View\Helper;
use RuntimeException;

/**
 * Authorize Helper
 *
 * A convenience helper to access authorization functions of the identity
 */
class AuthorizeHelper extends Helper
{
    /**
     * Identity Object
     *
     * @var null|\Authorization\IdentityInterface
     */
    protected $identity;

    /**
     * Authorization Object
     *
     * @var null|\Authorization\AuthorizationServiceInterface
     */
    protected $authorize;

    /**
     * Constructor hook method.
     *
     * Implement this method to avoid having to overwrite the constructor and call parent.
     *
     * @param array $config The configuration settings provided to this helper.
     *
     * @return void
     */
    public function initialize(array $config): void
    {
        $request = $this->getView()->getRequest();
        $this->identity = $request->getAttribute('identity');
        $this->authorize = $request->getAttribute('authorization');

        if (empty($this->identity)) {
            return;
        }

        if (!$this->identity instanceof IdentityInterface) {
            throw new RuntimeException(sprintf('Identity found in request does not implement %s', IdentityInterface::class));
        }
        if (!$this->authorize instanceof AuthorizationServiceInterface) {
            throw new RuntimeException(sprintf('Authorization service found in request does not implement %s', AuthorizationServiceInterface::class));
        }
    }

    /**
     * Check whether the current identity can perform an action.
     *
     * @param string $action The action/operation being performed.
     * @param mixed $resource The resource being operated on.
     * @return bool
     */
    public function can(string $action, $resource): bool
    {
        if (empty($this->authorize)) {
            return false;
        }
        if (empty($resource)) {
            throw new \InvalidArgumentException('No resource passed to "can" function.');
        }

        try {
            return $this->authorize->can($this->identity, $action, $resource);
        } catch (ForbiddenException $ex) {
            return false;
        }
    }

    /**
     * Check whether the current identity can perform an action.
     *
     * @param string $action The action/operation being performed.
     * @param mixed $resource The resource being operated on.
     * @return
     */
    public function applyScope(string $action, $resource)
    {
        if (empty($this->authorize)) {
            throw new RuntimeException('Authorization service not present');
        }
        if (empty($resource)) {
            throw new \InvalidArgumentException('No resource passed to "can" function.');
        }

        return $this->authorize->applyScope($this->identity, $action, $resource);
    }

    /**
     * Gets the identity itself
     *
     * @param string|null $key Key of something you want to get from the identity data
     * @return IdentityInterface
     */
    public function getIdentity()
    {
        return $this->identity->getOriginalData();
    }
}
