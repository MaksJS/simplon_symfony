<?php

namespace AppBundle\Util;

class Calculator
{
    public function add($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new \InvalidArgumentException();
        }
        return $a + $b;
    }

    public function divide($a, $b)
    {
        if (!is_numeric($a) || !is_numeric($b)) {
            throw new \InvalidArgumentException();
        }
        if ($b === 0) {
            throw new \Exception("Cannot divide by 0");
        }
        return $a / $b;
    }
}