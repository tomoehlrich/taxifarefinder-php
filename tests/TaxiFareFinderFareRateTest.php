<?php

namespace TomOehlrich\TaxiFareFinder\Tests;

use TomOehlrich\TaxiFareFinder\Exceptions\TffException;

class TaxiFareFinderFareRateTest extends TestCase
{

    public function testFareRateCanBeRetrieved()
    {
        $result = $this->taxiFareFinder->getTaxiFare(1.290270, 103.851959, 1.294582, 103.858568);

        $this->assertArrayHasKey('total_fare', $result);
        $this->assertArrayHasKey('initial_fare', $result);
        $this->assertArrayHasKey('metered_fare', $result);
        $this->assertArrayHasKey('distance', $result);
        $this->assertArrayHasKey('duration', $result);
    }


    public function testRetrievingTaxiFareWithoutValidCoordinatesThrowsAnException()
    {        
        $this->expectException(\Exception::class);

        $this->taxiFareFinder->getTaxiFare(91, 191, 91, 191);
    }


    public function testRetrievingTaxiFareWithAnInvalidMaxDistanceThrowsAnException()
    {        
        $this->expectException(\Exception::class);

        $this->taxiFareFinder->getTaxiFare(1.290270, 103.851959, 1.294582, 103.858568, 'medium', 99.99);
    }


    public function testRetrievingTaxiFareWithInvalidTrafficThrowsAnException()
    {        
        $this->expectException(\Exception::class);

        $this->taxiFareFinder->getTaxiFare(1.290270, 103.851959, 1.294582, 103.858568, 'very-heavy');
    }


    public function testRetrievingTaxiFareOutOfReachThrowsATffException()
    {        
        $this->expectException(TffException::class);

        $this->taxiFareFinder->getTaxiFare(-79.258355, 28.718684, -79.258355, 28.718684);
    }

}
