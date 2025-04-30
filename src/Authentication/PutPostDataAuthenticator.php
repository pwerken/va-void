<?php
declare(strict_types=1);

namespace App\Authentication;

use Authentication\Authenticator\FormAuthenticator;
use Authentication\Identifier\AbstractIdentifier;
use Psr\Http\Message\ServerRequestInterface as Request;

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
    protected function _getData(Request $request): ?array
    {
        $fields = $this->_config['fields'];
        /** @var array $body */
        $body = $request->getParsedBody();

        $data = [];
        foreach ($fields as $key => $field) {
            if (!isset($body[$field])) {
                return null;
            }

            // Authenticator\Identifier\PasswordIdentifier requires both the
            // username and password to be of type string
            $value = (string)$body[$field];
            if (!strlen($value)) {
                return null;
            }
            if ($key === AbstractIdentifier::CREDENTIAL_USERNAME) {
                // special case, as our db expects a int
                if (!ctype_digit($value)) {
                    return null;
                }
            }
            $data[$key] = $value;
        }

        return $data;
    }
}
