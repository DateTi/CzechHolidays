<?php

namespace DateTi\Holidays\Czech;

use DateTi\Holidays\EasterHolidayInterface;
use DateTi\Holidays\HolidaysInterface;
use DateTi\Time\DateTimeInterface;
use Nette\Neon\Neon;

class Holidays implements HolidaysInterface
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

    public function isHoliday(DateTimeInterface $date): bool
    {
        $year = $date->getYear();

        if ($this->isAllowedEaster()
            && $this->getEaster()->getMonday($year)->format('Y-m-d') === $date->format('Y-m-d')
        ) {
            return true;
        }

        if ($this->isAllowedGoodFriday()
            && $this->getEaster()->getGoodFriday($year)->format('Y-m-d') === $date->format('Y-m-d')
        ) {
            return true;
        }

        foreach ($this->getHollidays() as $holliday) {
            $yearHoliday = $year . '-' . $holliday;

            if ($date->format('Y-m-d') === $yearHoliday) {
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

    /**
     * @return array
     * @throws \RuntimeException
     */
    private function getConfig(): array
    {
        if (!$this->config) {
            $file = __DIR__ . '/config.neon';
            $content = file_get_contents($file);

            if ($content === false) {
                throw new \RuntimeException('Unavailable get content from file ' . $file);
            }
            $this->config = Neon::decode($content, Neon::BLOCK);
        }

        return $this->config;
    }
}
