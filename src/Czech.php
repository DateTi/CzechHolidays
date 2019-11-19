<?php

namespace DateTi\Holidays;

use DateTi\DateTi;
use Nette\Neon\Neon;

class Czech implements HolidaysInterface
{
    /** @var EasterHolidayInterface */
    private $easterHoliday;

    /** @var array */
    private $config;

    /** @var bool */
    private $isAllowedEaster;

    /** @var bool */
    private $isAllowedGoodFriday;

    /** @var array */
    private $holidays;

    public function __construct(EasterHolidayInterface $easterHoliday)
    {
        $this->easterHoliday = $easterHoliday;
    }

    public function isHoliday(DateTi $dateTi): bool
    {
        $year = $dateTi->getYear();

        if ($this->isAllowedEaster()
            && $this->getEaster()::getMonday($year)->format('Y-m-d') === $dateTi->format('Y-m-d')
        ) {
            return true;
        }

        if ($this->isAllowedGoodFriday()
            && $this->getEaster()::getGoodFriday($year)->format('Y-m-d') === $dateTi->format('Y-m-d')
        ) {
            return true;
        }

        foreach ($this->getHollidays() as $holliday) {
            $yearHoliday = $year . '-' . $holliday;

            if ($dateTi->format('Y-m-d') === $yearHoliday) {
                return true;
            }
        }

        return false;
    }

    public function isAllowedEaster(): bool
    {
        if (!$this->isAllowedEaster) {
            $this->isAllowedEaster = $this->getConfig()['easter'];
        }

        return $this->isAllowedEaster;
    }

    public function isAllowedGoodFriday(): bool
    {
        if (!$this->isAllowedGoodFriday) {
            $this->isAllowedGoodFriday = $this->getConfig()['goodFriday'];
        }

        return $this->isAllowedGoodFriday;
    }

    public function getHollidays(): array
    {
        if (!$this->holidays) {
            $this->holidays = $this->getConfig()['holidays'];
        }

        return $this->holidays;
    }

    public function getEaster(): EasterHolidayInterface
    {
        return $this->easterHoliday;
    }

    private function getConfig()
    {
        if (!$this->config) {
            $file = __DIR__ . '/config.neon';
            $this->config = Neon::decode(file_get_contents($file), Neon::BLOCK);
        }

        return $this->config;
    }
}
