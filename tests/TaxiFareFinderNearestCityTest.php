<?php

namespace TomOehlrich\TaxiFareFinder\Tests;

use TomOehlrich\TaxiFareFinder\Exceptions\TffException;

class TaxiFareFinderNearestCityTest extends TestCase
{
    
    public function testANearestCityCanBeRetrieved()
    {
        $result = $this->taxiFareFinder->getNearestCity(3.140853, 101.693207);

        $this->assertArrayHasKey('name', $result);
        $this->assertArrayHasKey('full_name', $result);
        $this->assertArrayHasKey('handle', $result);
        $this->assertArrayHasKey('locale', $result);
    }


    public function testTheCorrectNearestCityCanBeRetrieved()
    {
        $result = $this->taxiFareFinder->getNearestCity(1.290270, 103.851959);

        $this->assertEquals('Singapore', $result['name']);
    }

    
    public function testRetrievingTheNearestCityWithoutValidCoordinatesThrowsAnException()
    {        
        $this->expectException(\Exception::class);

        $this->taxiFareFinder->getNearestCity(91, 191);
    }


    public function testRetrievingTheNearestCityWithAnInvalidMaxDistanceThrowsAnException()
    {        
        $this->expectException(\Exception::class);

        $this->taxiFareFinder->getNearestCity(1.290270, 103.851959, 99.99);
    }


    public function testRetrievingTheNearestCityOutOfReachThrowsATffException()
    {        
        $this->expectException(TffException::class);

        $this->taxiFareFinder->getNearestCity(-79.258355, 28.718684);
    }
}
