<?php

declare(strict_types=1);

namespace Number\Example\Tests;

use Locale;
use Number\Example\Weight\Exception\InvalidWeightUnitException;
use Number\Example\Weight\Weight;
use Number\Example\Weight\WeightUnit;
use PHPUnit\Framework\TestCase;

class WeightTest extends TestCase
{
    public function testCanInitializeFromString(): void
    {
        $weight = new Weight('65.98', WeightUnit::KILOGRAM);
        $this->assertEquals('65.9800', $weight->toString());
    }

    public function testCanConvertGramsToKilograms(): void
    {
        $grams = new Weight('644275358.98', WeightUnit::GRAM);
        $kilograms = $grams->convert(WeightUnit::KILOGRAM);

        $this->assertEquals('644275.3589', $kilograms->toString());
    }

    public function testCanFormatWeight(): void
    {
        Locale::setDefault('nl_NL');

        $weight = new Weight('215.20281', WeightUnit::MICROGRAM);

        $this->assertEquals('215,203 µg', $weight->format());
    }

    public function testCanCalculateWithDeviatingUnits(): void
    {
        $grams = new Weight('421289.55', WeightUnit::GRAM);
        $kilograms = new Weight('39.4476', WeightUnit::KILOGRAM);
        $sumKilograms = $grams->add($kilograms);

        $this->assertEquals('460737.1500', $sumKilograms->toString());

        $nanograms = new Weight(5, WeightUnit::NANOGRAM);
        $sumNanograms = $sumKilograms->add($nanograms);

        $this->assertEquals('460737.150000005', $sumNanograms->toString(9));
    }

    public function testCannotUseInvalidWeightUnit(): void
    {
        $this->expectException(InvalidWeightUnitException::class);
        new Weight('500', 'invalid');
    }
}
