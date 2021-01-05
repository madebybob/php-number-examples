<?php

declare(strict_types=1);

namespace Number\Example\Weight;

use Number\Example\Weight\Exception\InvalidWeightUnitException;
use Number\Number;

class WeightUnit
{
    // region Constants

    public const MEGATONNE = 'Mt';
    public const TONNE = 't';
    public const KILOGRAM = 'kg';
    public const HECTOGRAM = 'hg';
    public const DEKAGRAM = 'dkg';
    public const GRAM = 'g';
    public const DECIGRAM = 'dg';
    public const CENTIGRAM = 'cg';
    public const MILLIGRAM = 'mg';
    public const MICROGRAM = 'µg';
    public const NANOGRAM = 'ng';

    public const UNITS = [
        self::MEGATONNE,
        self::TONNE,
        self::KILOGRAM,
        self::HECTOGRAM,
        self::DEKAGRAM,
        self::GRAM,
        self::DECIGRAM,
        self::CENTIGRAM,
        self::MILLIGRAM,
        self::MICROGRAM,
        self::NANOGRAM,
    ];

    // endregion

    // region Static properties

    private static ?array $units = null;

    // endregion

    // region Properties

    private string $name;
    private string $abbreviation;
    private Number $numberOfGrams;

    // endregion

    public function __construct(string $name, string $abbreviation, Number $numberOfGrams)
    {
        $this->name = $name;
        $this->abbreviation = $abbreviation;
        $this->numberOfGrams = $numberOfGrams;
    }

    // region Static methods

    private static function units(): array
    {
        if (self::$units !== null) {
            return self::$units;
        }

        $units = [
            self::MEGATONNE => new WeightUnit('megatonne', self::MEGATONNE, Number::create('1000000000000')),
            self::TONNE => new WeightUnit('tonne', self::TONNE, Number::create('1000000')),
            self::KILOGRAM => new WeightUnit('kilogram', self::KILOGRAM, Number::create('1000')),
            self::HECTOGRAM => new WeightUnit('hectogram', self::HECTOGRAM, Number::create('100')),
            self::DEKAGRAM => new WeightUnit('dekagram', self::DEKAGRAM, Number::create('10')),
            self::GRAM => new WeightUnit('gram', self::GRAM, Number::create('1')),
            self::DECIGRAM => new WeightUnit('decigram', self::DECIGRAM, Number::create('0.1')),
            self::CENTIGRAM => new WeightUnit('centigram', self::CENTIGRAM, Number::create('0.01')),
            self::MILLIGRAM => new WeightUnit('milligram', self::MILLIGRAM, Number::create('0.001')),
            self::MICROGRAM => new WeightUnit('microgram', self::MICROGRAM, Number::create('0.000001')),
            self::NANOGRAM => new WeightUnit('nanogram', self::NANOGRAM, Number::create('0.000000001')),
        ];

        self::$units = $units;

        return $units;
    }

    public static function unit(string $unitAbbreviation): WeightUnit
    {
        self::validate($unitAbbreviation);

        return self::units()[$unitAbbreviation];
    }

    public static function validate(string $unitAbbreviation): void
    {
        if (in_array($unitAbbreviation, self::UNITS, true) === false) {
            throw new InvalidWeightUnitException($unitAbbreviation);
        }
    }

    // endregion

    // region Getters

    public function getName(): string
    {
        return $this->name;
    }

    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }

    public function getNumberOfGrams(): Number
    {
        return $this->numberOfGrams;
    }

    // endregion
}
