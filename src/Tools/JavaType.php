<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Tools;

use stdClass;
use Icecave\Flax\Object;

class JavaType
{
    const SHORT = 1;
    const INT = 2;
    const INTEGER = 2;
    const LONG = 3;
    const FLOAT = 4;
    const DOUBLE = 5;
    const STRING = 6;
    const BOOL = 7;
    const BOOLEAN = 7;

    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Short type
     *
     * @param $value
     * @return JavaType
     */
    public static function short($value)
    {
        return new self(self::SHORT, $value);
    }

    /**
     * Int type
     *
     * @param $value
     * @return JavaType
     */
    public static function int($value)
    {
        return new self(self::INT, $value);
    }

    /**
     * Integer type
     *
     * @param $value
     * @return JavaType
     */
    public static function integer($value)
    {
        return new self(self::INTEGER, $value);
    }

    /**
     * Long type
     *
     * @param $value
     * @return JavaType
     */
    public static function long($value)
    {
        return new self(self::LONG, $value);
    }

    /**
     * Float type
     *
     * @param $value
     * @return JavaType
     */
    public static function float($value)
    {
        return new self(self::FLOAT, $value);
    }

    /**
     * Double type
     *
     * @param $value
     * @return JavaType
     */
    public static function double($value)
    {
        return new self(self::DOUBLE, $value);
    }

    /**
     * String type
     *
     * @param $value
     * @return JavaType
     */
    public static function string($value)
    {
        return new self(self::STRING, $value);
    }

    /**
     * Bool type
     *
     * @param $value
     * @return JavaType
     */
    public static function bool($value)
    {
        return new self(self::BOOL, $value);
    }

    /**
     * Boolean type
     *
     * @param $value
     * @return JavaType
     */
    public static function boolean($value)
    {
        return new self(self::BOOLEAN, $value);
    }

    /**
     * Object type
     *
     * @param $class
     * @param $properties
     * @return Object
     */
    public static function object($class, $properties)
    {
        $std = new stdClass;

        foreach ($properties as $key => $value) {
            $std->$key = ($value instanceof JavaType) ? $value->value : $value;
        }

        return new Object($class, $std);
    }
}
