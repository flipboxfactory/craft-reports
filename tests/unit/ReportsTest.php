<?php

namespace flipbox\craft\reports\tests;

use Codeception\Test\Unit;
use flipbox\craft\reports\cp\Cp;
use flipbox\craft\reports\Reports;
use flipbox\craft\reports\services\Formats as FormatsService;
use flipbox\craft\reports\services\Reports as ReportsService;

class ReportsTest extends Unit
{
    /**
     * Test the 'Reports' component is set correctly
     */
    public function testReportsComponent()
    {
        $this->assertInstanceOf(
            ReportsService::class,
            Reports::getInstance()->getReports()
        );

        $this->assertInstanceOf(
            ReportsService::class,
            Reports::getInstance()->reports
        );
    }

    /**
     * Test the 'Formats' component is set correctly
     */
    public function testFormatsComponent()
    {
        $this->assertInstanceOf(
            FormatsService::class,
            Reports::getInstance()->getFormats()
        );

        $this->assertInstanceOf(
            FormatsService::class,
            Reports::getInstance()->formats
        );
    }

    /**
     * Test the 'CP' module is set correctly
     */
    public function testCpModule()
    {
        $this->assertInstanceOf(
            Cp::class,
            Reports::getInstance()->getCp()
        );
    }
}
