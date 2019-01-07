<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Tools;

/**
 * Class Json
 * @package Zhaqq\FastDubbo
 */
class Json
{

    /**
     * @param string $string
     * @return array
     */
    public static function decode(string $string)
    {
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        $string = preg_replace('/(^.*?\{)/', '{', $string);

        return json_decode($string, true);
    }
}
