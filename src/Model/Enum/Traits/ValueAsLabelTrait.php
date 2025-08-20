<?php
declare(strict_types=1);

namespace App\Model\Enum\Traits;

trait ValueAsLabelTrait
{
    public function label(): string
    {
        return $this->value;
    }
}
