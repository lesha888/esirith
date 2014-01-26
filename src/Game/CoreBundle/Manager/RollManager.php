<?php

namespace Game\CoreBundle\Manager;

use Game\CoreBundle\Model\Roll;

class RollManager
{
    /**
     * Roll a number of dices
     *
     * @param $number
     * @param $dice
     * @param int $critical
     * @return Roll
     */
    public function roll($number, $dice, $critical = 0)
    {
        $roll = new Roll();

        $total = 0;
        for ($i=0; $i<$number; $i++) {
            $total += rand(1, $dice);
        }

        if ($critical > 0) {
            $roll->setIsCritical($this->isCritical($total, $dice, $critical));
        }

        $roll->setRollResult($total);

        return $roll;
    }

    /**
     * Devuelve TRUE si es un critico
     *
     * @param $result
     * @param $dice
     * @param $chance
     * @return bool
     */
    public function isCritical($result, $dice, $chance)
    {
        return ($result >= $this->getCriticalLimit($dice, $chance));
    }

    /**
     * Devuelve el limite a superar para critico
     *
     * @param $dice
     * @param $chance
     * @return float
     */
    public function getCriticalLimit($dice, $chance)
    {
        return ($dice - ($dice * ($chance / 100)));
    }
}