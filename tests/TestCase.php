<?php

namespace TomOehlrich\TaxiFareFinder\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use TomOehlrich\TaxiFareFinder\TaxiFareFinder;

class TestCase extends PHPUnitTestCase
{

    /** @var \TomOehlrich\TaxiFareFinder\TaxiFareFinder */
    protected $taxiFareFinder;

    /** @var string */
    protected $apiKey = '<YOUR API KEY>';


    public function setUp(): void
    {
        parent::setUp();

        $this->taxiFareFinder = new TaxiFareFinder($this->apiKey);

    }
}
