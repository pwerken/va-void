<?php
declare(strict_types=1);

namespace App\View\Helper;

use Authorization\AuthorizationServiceInterface;
use Authorization\Exception\ForbiddenException;
use Authorization\IdentityInterface;
use Cake\View\Helper;
use InvalidArgumentException;
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
     * @var \Authorization\IdentityInterface|null
     */
    protected $identity;

    /**
     * Authorization Object
     *
     * @var \Authorization\AuthorizationServiceInterface|null
     */
    protected $authorize;

    /**
     * Constructor hook method.
     *
     * Implement this method to avoid having to overwrite the constructor and call parent.
     *
     * @param array $config The configuration settings provided to this helper.
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
            $message = 'Identity found in request does not implement %s';
            throw new RuntimeException(sprintf($message, IdentityInterface::class));
        }
        if (!$this->authorize instanceof AuthorizationServiceInterface) {
            $message = 'Authorization service found in request does not implement %s';
            throw new RuntimeException(sprintf($message, AuthorizationServiceInterface::class));
        }
    }

    /**
     * Check whether the current identity can perform an action.
     *
     * @param string $action The action/operation being performed.
     * @param mixed $resource The resource being operated on.
     * @return bool
     */
    public function can(string $action, mixed $resource): bool
    {
        if (empty($this->authorize)) {
            return false;
        }
        if (empty($resource)) {
            throw new InvalidArgumentException('No resource passed to "can" function.');
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
     * @return mixed
     */
    public function applyScope(string $action, mixed $resource): mixed
    {
        if (empty($this->authorize)) {
            throw new RuntimeException('Authorization service not present');
        }
        if (empty($resource)) {
            throw new InvalidArgumentException('No resource passed to "can" function.');
        }

        return $this->authorize->applyScope($this->identity, $action, $resource);
    }

    /**
     * Gets the identity itself
     *
     * @param string|null $key Key of something you want to get from the identity data
     * @return \Authorization\IdentityInterface
     */
    public function getIdentity(): IdentityInterface
    {
        return $this->identity->getOriginalData();
    }
}
