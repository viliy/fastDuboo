<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Tools;


class JavaUtility
{
    /**
     * ASCII array to string
     *
     * @param  array $asciis
     * @return string
     */
    public static function asciiArrayToString($asciis)
    {
        $chars = '';
        foreach ($asciis as $ascii) {
            $chars .= chr($ascii);
        }
        return $chars;
    }

    /**
     * Whether or not in between the two values
     *
     * @param  integer $value
     * @param  integer $min
     * @param  integer $max
     * @return boolean
     */
    public static function isBetween($value, $min, $max)
    {
        return $min <= $value && $value <= $max;
    }

    /**
     * Slice string to array
     *
     * @param  string $str
     * @param  integer $start
     * @param  integer $length
     * @return array
     */
    public static function sliceToArray($str, $start, $length = false)
    {
        $arr = [];
        for ($i = 0; $i < strlen($str); $i++) {
            if ($length !== false && ($i - $start) >= $length) {
                break;
            }

            if ($i >= $start) {
                $arr[] = ord($str[$i]);
            }
        }

        return $arr;
    }
}
