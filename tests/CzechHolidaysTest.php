<?php

namespace DateTiTests\Holidays\Czech;

use DateTi\DateTi;
use DateTi\Holidays\Czech;
use DateTiTests\Holidays\AbstractTest;

class CzechHolidaysTest extends AbstractTest
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->holiday = new Czech($this->easterHoliday);
    }

    /**
     * @test
     */
    public function getHolidays()
    {
        $actual = $this->holiday->getHollidays();
        $expected = [
            '01-01',
            '05-01',
            '05-08',
            '07-05',
            '07-06',
            '09-28',
            '10-28',
            '11-17',
            '12-24',
            '12-25',
            '12-26',
        ];

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function isHolidayGoodFriday()
    {
        $this->easterHoliday->expects('getGoodFriday')->andReturn(new \DateTime('2018-04-02'));
        $this->easterHoliday->expects('getMonday')->andReturn(new \DateTime('2018-03-30'));
        $date = new DateTi('2018-03-30', new \DateTimeZone('Europe/Prague'));
        $this->assertEquals(true, $this->holiday->isHoliday($date));
    }

    /**
     * @test
     */
    public function isHolidayEasterMonday()
    {
        $this->easterHoliday->expects('getGoodFriday')->andReturn(new \DateTime('2018-04-02'));
        $this->easterHoliday->expects('getMonday')->andReturn(new \DateTime('2018-03-30'));
        $date = new DateTi('2018-04-02', new \DateTimeZone('Europe/Prague'));
        $this->assertEquals(true, $this->holiday->isHoliday($date));
    }

    /**
     * @test
     */
    public function isHolidayNewYear()
    {
        $this->easterHoliday->expects('getGoodFriday')->andReturn(new \DateTime('2018-04-02'));
        $this->easterHoliday->expects('getMonday')->andReturn(new \DateTime('2018-03-30'));
        $date = new DateTi('2020-01-01', new \DateTimeZone('Europe/Prague'));
        $this->assertEquals(true, $this->holiday->isHoliday($date));
    }

    /**
     * @test
     */
    public function isHolidayWorkDay()
    {
        $this->easterHoliday->expects('getGoodFriday')->andReturn(new \DateTime('2018-04-02'));
        $this->easterHoliday->expects('getMonday')->andReturn(new \DateTime('2018-03-30'));
        $date = new DateTi('2020-01-02', new \DateTimeZone('Europe/Prague'));
        $this->assertEquals(false, $this->holiday->isHoliday($date));
    }
}
