<?php
/**
 * 2013-2016 MADEF IT.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@madef.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    MADEF IT <contact@madef.fr>
 *  @copyright 2013-2016 MADEF IT
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

class HelperNumber
{
    public static function commaFloatFormat($a)
    {
        $a = str_replace(' ', '', $a);
        $a = str_replace("'", '', $a);
        $a = str_replace(".", '', $a);
        $a = str_replace(',', '.', $a);
        return (float) $a;
    }

    public static function pointFormat($a)
    {
        $a = str_replace(' ', '', $a);
        $a = str_replace(",", '', $a);
        return (float) $a;
    }

    public static function momayyezFormat($a)
    {
        $a = str_replace('-', '.', $a);
        return (float) $a;
    }

    public static function calculate($value)
    {
        if (!preg_match('/^\s*([\(\s]*\d+[\)\s]*\s*[+*\/-]\s*)*[\(\s]*\d+[\)\s]*$/Usi', $value)) {
            return 0;
        }

        $value = str_replace(' ', '', $value);
        $value = '('.$value.')';

        while (preg_match('/\(([^\(\)]+)\)/', $value, $match)) {
            $result = self::caculateExpr($match[1]);
            $value = str_replace('('.$match[1].')', $result, $value);
        }

        return (float) $value;
    }

    protected static function caculateExpr($expr)
    {
        while (preg_match('/((\d+)([\*\\\\])(\d+))/', $expr, $match)) {
            switch ($match[3]) {
                case '*':
                    $expr = str_replace($match[1], $match[2] * $match[4], $expr);
                    break;
                case '/':
                    $expr = str_replace($match[1], $match[2] / $match[4], $expr);
                    break;
            }
        }
        while (preg_match('/((\d+)([+-])(\d+))/', $expr, $match)) {
            switch ($match[3]) {
                case '+':
                    $expr = str_replace($match[1], $match[2] + $match[4], $expr);
                    break;
                case '-':
                    $expr = str_replace($match[1], $match[2] - $match[4], $expr);
                    break;
            }
        }
        return (float) $expr;
    }

    public static function neg($a)
    {
        return -(float) $a;
    }

    public static function abs($a)
    {
        return abs((float) $a);
    }

    public static function sum($a, $b)
    {
        return round((float) $a + (float) $b, 2);
    }

    public static function sub($a, $b)
    {
        return round((float) $a - (float) $b, 2);
    }

    public static function multiply($a, $b)
    {
        return round((float) $a * (float) $b, 2);
    }

    public static function divide($a, $b)
    {
        return round((float) $a / (float) $b, 2);
    }

    public static function modulo($a, $b)
    {
        return round((float) $a % (float) $b, 2);
    }

    /**
     * $a * $b%
     */
    public static function percentage($a, $b)
    {
        return round((float) $a * ((float) $b / 100), 2);
    }

    /**
     * $a - $a * $b%
     */
    public static function percentageComplement($a, $b)
    {
        return round((float) $a * (1 - ((float) $b / 100)), 2);
    }

    /**
     * $a * (100% + $b%)
     * or
     * $a + $a * $b%
     */
    public static function addPercentage($a, $b)
    {
        return round((float) $a * (1 + ((float) $b / 100)), 2);
    }

    /**
     * $a / $b
     */
    public static function rate($a, $b)
    {
        return round(1 - (float) $a / (float) $b, 2);
    }
}
