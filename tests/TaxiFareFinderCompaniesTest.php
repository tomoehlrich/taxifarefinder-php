<?php

namespace TomOehlrich\TaxiFareFinder\Tests;

use TomOehlrich\TaxiFareFinder\Exceptions\TffException;

class TaxiFareFinderCompaniesTest extends TestCase
{

    public function testTaxiCompaniesCanBeRetrieved()
    {
        $result = $this->taxiFareFinder->getTaxiCompanies('Boston');

        $this->assertArrayHasKey('businesses', $result);

        $this->assertNotEmpty($result['businesses']);
    }


    public function testRetrievingTaxiCompaniesWithoutCityThrowsException()
    {
        $this->expectException(\Exception::class);

        $this->taxiFareFinder->getTaxiCompanies('');
    }


    public function testRetrievingTaxiCompaniesWithNonSupportedCityThrowsException()
    {
        $this->expectException(TffException::class);

        $this->taxiFareFinder->getTaxiCompanies('Antarctica');
    }

}
