<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Tools;

use Icecave\Chrono\DateTime;
use Icecave\Collections\Map;
use Icecave\Collections\Vector;
use Icecave\Flax\Object;
use Icecave\Flax\Serialization\Encoder;
use stdClass;

class Buffer
{
    const DEFAULT_LANGUAGE = 'Java';
    private $encoder;


    private function recursive($data)
    {
        if ($data instanceof Vector) {
            return $this->recursive($data->elements());
        }

        if ($data instanceof Map) {
            $elements = $data->elements();
            $temp = [];
            foreach ($elements as $key => $value) {
                if (count($value) == 2) {
                    $temp[$value[0]] = $value[1];
                }
            }
            return $temp;
        }

        if ($data instanceof DateTime) {
            return $data->unixTime();
        }

        if ($data instanceof stdClass) {
            foreach ($data as $key => $value) {
                $data->$key = $this->recursive($value);
            }
        }

        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->recursive($value);
            }
        }

        return $data;
    }

    /**
     * @param $path
     * @param $method
     * @param $args
     * @param $group
     * @param $version
     * @param $dubboVersion
     * @return string
     * @throws \Icecave\Flax\Exception\EncodeException
     * @throws \Exception
     */
    public function buffer($path, $method, $args, $group, $version, $dubboVersion)
    {
        $typeRefs = $this->typeRefs($args);
        $attachment = JavaType::object('java.util.HashMap', [
            'interface' => $path,
            'version' => $version,
            'group' => $group,
            'path' => $path,
            'timeout' => '3000'
        ]);

        $bufferBody = $this->bufferBody($path, $method, $typeRefs, $args, $attachment, $version, $dubboVersion);
        $bufferHead = $this->bufferHead(strlen($bufferBody));

        return $bufferHead . $bufferBody;
    }

    private function bufferHead($length)
    {
        $head = [0xda, 0xbb, 0xc2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $i = 15;

        if ($length - 256 < 0) {
            $head[$i] = $length - 256;
        } else {
            while ($length - 256 > 0) {
                $head[$i--] = $length % 256;
                $length = $length >> 8;
            }

            $head[$i] = $length;
        }
        return JavaUtility::asciiArrayToString($head);
    }

    /**
     * @param $path
     * @param $method
     * @param $typeRefs
     * @param $args
     * @param $attachment
     * @param $version
     * @param $dubboVersion
     * @return string
     * @throws \Icecave\Flax\Exception\EncodeException
     */
    private function bufferBody($path, $method, $typeRefs, $args, $attachment, $version, $dubboVersion)
    {
        if (empty($this->encoder)) {
            $this->encoder = new Encoder();
        }
        $body = '';
        $body .= $this->encoder->encode($dubboVersion);
        $body .= $this->encoder->encode($path);
        $body .= $this->encoder->encode($version);
        $body .= $this->encoder->encode($method);
        $body .= $this->encoder->encode($typeRefs);

        foreach ($args as $arg) {
            $body .= $this->encoder->encode($arg);
        }
        $body .= $this->encoder->encode($attachment);
        $this->encoder->reset();
        return $body;
    }

    /**
     * @param $args
     * @return string
     * @throws \Exception
     */
    private function typeRefs(&$args)
    {
        $typeRefs = '';

        if (count($args)) {
            $lang = JavaAdapter::language(self::DEFAULT_LANGUAGE);

            foreach ($args as &$arg) {
                if ($arg instanceof JavaType) {
                    $type = $arg->type;
                    $arg = $arg->value;
                } else {
                    $type = $this->argToType($arg);
                }

                $typeRefs .= $lang->typeRef($type);
            }
        }

        return $typeRefs;
    }

    /**
     * @param Object $arg
     * @return int|string
     * @throws \Exception
     */
    private function argToType(Object $arg)
    {
        switch (gettype($arg)) {
            case 'integer':
                return $this->numToType($arg);
            case 'boolean':
                return JavaType::BOOLEAN;
            case 'double':
                return JavaType::DOUBLE;
            case 'string':
                return JavaType::STRING;
            case 'object':
                return $arg->className();
            default:
                throw new \Exception("Handler for type {$arg} not implemented");
        }
    }

    private function numToType($value)
    {
        if (JavaUtility::isBetween($value, -32768, 32767)) {
            return JavaType::SHORT;
        } elseif (JavaUtility::isBetween($value, -2147483648, 2147483647)) {
            return JavaType::INT;
        }

        return JavaType::LONG;
    }
}
