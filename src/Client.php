<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo;

/**
 * Class Client
 * @package Zhaqq\FastDubbo
 */
class Client extends SwooleClient
{
    protected static $clients = [];

    public function connect($host, $port, $timeout)
    {
        $domain = md5($host . $port);
        if (!isset(static::$clients[$domain]) || false === static::$clients[$domain]->isConnected()) {
            static::$clients[$domain] = $this->swooleClient;
            if (false === static::$clients[$domain]->connect($host, $port, $timeout)) {
                $this->tryReconnect($host, $port, $timeout);
            }
        }
        $this->client = static::$clients[$domain];
    }
}
