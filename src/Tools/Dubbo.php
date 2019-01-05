<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Tools;

class Dubbo
{
    private $host = '';
    private $port = '';
    private $path = '';
    private $group = '';
    private $version = '';
    private $dubboVersion = '2.6.2';

    /**
     * @var Buffer
     */
    protected $buffer;

    /**
     * 设置版本,路径
     * @param $version
     * @param $path
     */
    public function setVersionPath($path, $version)
    {
        $this->version = $version;
        $this->path = $path;

        $this->buffer = new Buffer();
    }

    /**
     * Parse the dubbo uri to this props
     * @param  string $uri
     * @throws \Exception
     */
    public function parseURItoProps($uri)
    {
        if (empty($this->version) || empty($this->path)) {
            throw new \Exception("请先执行setVersionPath()方法");
        }
        $info = parse_url(urldecode($uri));
        parse_str($info['query'], $params);
        isset($info['host']) AND $this->host = $info['host'];
        isset($info['port']) AND $this->port = $info['port'];
        isset($params['version']) AND $this->version = $params['version'];
        isset($params['dubbo']) AND $this->dubboVersion = $params['dubbo'];
    }

    /**
     * @param $method
     * @param $args
     * @return string
     * @throws \Icecave\Flax\Exception\EncodeException
     */
    public function buffer($method, $args)
    {
        return $this->buffer->buffer(
            $this->path,
            $method,
            $args,
            $this->group,
            $this->version,
            $this->dubboVersion
        );
    }

    public function host()
    {
        return $this->host;
    }

    public function port()
    {
       return $this->port;
    }
}
