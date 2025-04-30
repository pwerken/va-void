<?php
declare(strict_types=1);

namespace App\Error\Exception;

use Cake\Datasource\EntityInterface;
use Cake\Http\Exception\BadRequestException;
use Cake\Utility\Hash;
use Throwable;

class ValidationException extends BadRequestException
{
    private array $_validationErrors = [];

    public function __construct(EntityInterface $entity, int $code = 422, ?Throwable $previous = null)
    {
        $this->_validationErrors = $entity->getErrors();

        $flat = Hash::flatten($this->_validationErrors);
        $count = count($flat);

        if ($count == 1) {
            $message = 'A validation error occurred';
        } else {
            $message = "{$count} validation errors occurred";
        }

        parent::__construct($message, $code, $previous);
    }

    public function getValidationErrors(): array
    {
        return $this->_validationErrors;
    }
}
