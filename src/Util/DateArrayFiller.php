<?php

namespace C3\Util;

use C3\Exception\DateArrayFillerException;
use DateInterval;
use DateTime;
use Exception;

class DateArrayFiller
{
    /**
     * @param array $array
     * @param int $precision
     * @return array
     * @throws DateArrayFillerException
     */
    public function fillGapsWithAverages(array $array, int $precision = 1): array
    {
        $dates = array_keys($array);

        try {
            /** @var DateTime $currentDate */
            $currentDate = new DateTime(current($dates));
            /** @var DateTime $toDate */
            $toDate = new DateTime(end($dates));

            $lastFoundIndex = 0;
            while($currentDate < $toDate) {
                if(!in_array($currentDate->format('Y-m-d'), $dates)) {
                    $lastDate = new DateTime($dates[$lastFoundIndex]);
                    $lastValue = $array[$dates[$lastFoundIndex]];
                    $nextDate = new DateTime($dates[$lastFoundIndex + 1]);
                    $nextValue = $array[$dates[$lastFoundIndex + 1]];

                    $days = $nextDate->diff($lastDate)->days - 1;
                    $step = ($nextValue - $lastValue) / ($days + 1);

                    for($i = 1; $i <= $days; $i++) {
                        $newDate = (clone $lastDate)->add(new DateInterval('P' . $i . 'D'))->format('Y-m-d');
                        $newValue = round($lastValue + ($step * $i), $precision);

                        $array[$newDate] = $newValue;
                    }

                    $currentDate = $nextDate;
                } else {
                    $lastFoundIndex = array_search($currentDate->format('Y-m-d'), $dates);

                    $currentDate->add(new DateInterval('P1D'));
                }
            }
        } catch (Exception $e) {
            throw new DateArrayFillerException($e->getMessage(), $e->getCode(), $e);
        }

        ksort($array);

        return $array;
    }

    /**
     * @param array $array
     * @return array
     * @throws DateArrayFillerException
     */
    public function fillGapsWithNulls(array $array): array
    {
        $dates = array_keys($array);

        try {
            /** @var DateTime $currentDate */
            $currentDate = new DateTime(current($dates));
            /** @var DateTime $toDate */
            $toDate = new DateTime(end($dates));

            while($currentDate < $toDate) {
                if(!in_array($currentDate->format('Y-m-d'), $dates)) {
                    $array[$currentDate->format('Y-m-d')] = null;
                }

                $currentDate->add(new DateInterval('P1D'));
            }
        } catch (Exception $e) {
            throw new DateArrayFillerException($e->getMessage(), $e->getCode(), $e);
        }

        ksort($array);

        return $array;
    }
}