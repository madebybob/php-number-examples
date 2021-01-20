<?php

declare(strict_types=1);

namespace Number\Example\Weight;

use Number\AbstractNumber;
use Number\Exception\DivisionByZeroError;
use Number\Formatter\Formatter;

class Weight extends AbstractNumber
{
    protected const INTERNAL_SCALE = 18;
    private WeightUnit $unit;

    /**
     * @param string|float|int $value
     */
    public function __construct($value, string $unit, ?self $parent = null)
    {
        parent::__construct($value, $parent);
        $this->unit = WeightUnit::unit($unit);
    }

    /**
     * @param string|float|int $value
     */
    public static function create($value, string $unitAbbreviation): self
    {
        return new self($value, $unitAbbreviation);
    }

    public function init(string $value, ?string $unitAbbreviation = null): self
    {
        if ($unitAbbreviation === null) {
            $unitAbbreviation = $this->getUnit()->getAbbreviation();
        }

        return new static($value, $unitAbbreviation, $this);
    }

    // region Custom

    public function getUnit(): WeightUnit
    {
        return $this->unit;
    }

    public function convert(string $unitAbbreviation): Weight
    {
        $unit = WeightUnit::unit($unitAbbreviation);
        $unitInGrams = $unit->getNumberOfGrams()->toString(self::INTERNAL_SCALE);
        if (bccomp($unitInGrams, '0', self::INTERNAL_SCALE) === 0) {
            throw new DivisionByZeroError();
        }

        $quantityInGrams = bcmul($this->get(), $this->getUnit()->getNumberOfGrams()->toString(self::INTERNAL_SCALE), self::INTERNAL_SCALE);
        $convertedQuantity = bcdiv($quantityInGrams, $unitInGrams, self::INTERNAL_SCALE);

        return $this->init($convertedQuantity, $unitAbbreviation);
    }

    public function format(int $minFractionDigits = 0, int $maxFractionDigits = 3): string
    {
        $formattedValue = Formatter::format($this->get(), $minFractionDigits, $maxFractionDigits);

        return sprintf('%s %s', $formattedValue, $this->getUnit()->getAbbreviation());
    }

    /**
     * @inheritDoc
     */
    protected function getNumberFromInput($value): Weight
    {
        if ($value instanceof self) {
            return $value->convert($this->getUnit()->getAbbreviation());
        }

        return parent::getNumberFromInput($value);
    }

    // endregion
}
