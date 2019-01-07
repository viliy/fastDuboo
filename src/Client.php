<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo;

use Zhaqq\FastDubbo\Exceptions\ClientException;
use swoole_client;


/**
 * Class Client
 * @package Zhaqq\FastDubbo
 */
class Client extends SwooleClient
{
    protected static $clients = [];

    protected $retry = 2;

    protected $domain;

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

    protected function resetConnected()
    {
        $this->client->close();
        $this->swooleClient = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);
        if (isset(static::$clients[$this->domain])) {
            unset(static::$clients[$this->domain]);
        }
    }

    /**
     * @param string $provider
     * @param array $params
     * @return string
     * @throws ClientException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function invoke(string $provider, array $params)
    {
        try {
            $data = duboo_buffer($provider, $params);
            $this->client->send($data);

            $response = $this->receive();
        } catch (\Exception $exception) {
            if (0 <= $this->retry) {
                --$this->retry;
                $this->resetConnected();
                $response = $this->invoke($provider, $params);
            } else {
                throw new ClientException($exception->getMessage(), 502);
            }
        }

        $this->retry = 2;

        return $response;
    }
}
