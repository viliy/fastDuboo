<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Tools;


class JavaAdapter
{
    private static $instances = [];

    /**
     * Load adapter instance
     * @param  string $dir
     * @param  string $class
     * @return object
     * @throws \Exception
     */
    public static function load($dir, $class)
    {
        if (isset(self::$instances[$dir . $class])) {
            return self::$instances[$dir . $class];
        }

        $class = ucfirst($class);
        $class = __NAMESPACE__ . "\\{$dir}\\{$class}";

        if (!class_exists($class)) {
            throw new \Exception("Can not match the class according to adapter {$class}");
        }

        return (self::$instances[$dir . $class] = new $class);
    }

    /**
     * Load adapter instance of language
     *
     * @param $class
     * @return object
     * @throws \Exception
     */
    public static function language($class)
    {
        return self::load('Languages', $class);
    }

    /**
     * Load adapter instance of protocol
     *
     * @param $class
     * @return object
     * @throws \Exception
     */
    public static function protocol($class)
    {
        return self::load('Protocols', $class);
    }
}
