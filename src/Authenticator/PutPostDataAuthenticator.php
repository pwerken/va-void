<?php
declare(strict_types=1);

namespace App\Authenticator;

use Authentication\Authenticator\FormAuthenticator;
use Psr\Http\Message\ServerRequestInterface;

/**
 * PutPostData Authenticator
 *
 * Authenticates an identity based on the PUT or POST data of the request.
 */
class PutPostDataAuthenticator extends FormAuthenticator
{
    /**
     * Checks the fields to ensure they are supplied.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request The request that contains login information.
     * @return array|null Username and password retrieved from a request body.
     */
    protected function _getData(ServerRequestInterface $request): ?array
    {
        $fields = $this->_config['fields'];
        /** @var array $body */
        $body = $request->getParsedBody();

        $data = [];
        foreach ($fields as $key => $field) {
            if (!isset($body[$field])) {
                return null;
            }

			# FIXED: cast $value to string
            $value = (string)$body[$field];
            if (!is_string($value) || !strlen($value)) {
                return null;
            }

            $data[$key] = $value;
        }

        return $data;
    }
}
