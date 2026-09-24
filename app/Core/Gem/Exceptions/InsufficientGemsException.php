<?php

namespace App\Core\Gem\Exceptions;

use RuntimeException;

class InsufficientGemsException extends RuntimeException
{
    public function __construct(
        public readonly int $required,
        public readonly int $available,
    ) {
        parent::__construct("Insufficient gems: need {$required}, have {$available}.");
    }
}
