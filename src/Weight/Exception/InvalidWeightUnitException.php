<?php

declare(strict_types=1);

namespace Number\Example\Weight\Exception;

use InvalidArgumentException;

class InvalidWeightUnitException extends InvalidArgumentException
{
    public function __construct(string $abbreviation)
    {
        parent::__construct(sprintf('Invalid weight unit \'%s\' given.', $abbreviation), 0, null);
    }
}
