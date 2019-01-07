<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo;

use Zhaqq\FastDubbo\Contracts\ClientInterface;
use swoole_client;

/**
 * Class SwooleClient
 * @package Zhaqq\FastDubbo
 */
class SwooleClient implements ClientInterface
{
    /**
     * @var $client swoole_client
     */
    protected $client;

    /**
     * @var $swooleClient swoole_client
     */
    protected $swooleClient;

    protected $maxTryCount = 3;

    protected $tryCount = 0;

    protected $retry = 3;

    public function __construct($async = false)
    {
        $this->swooleClient = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);
        if ($async) {
            $this->onEvent();
        }
    }

    public function connect($host, $port, $timeout)
    {
        if (false === $this->client->connect($host, $port, $timeout)) {
            $this->tryReconnect($host, $port, $timeout);
        }
    }

    public function onEvent()
    {
        $this->swooleClient->on('connect', [$this, 'onConnect']);
        $this->swooleClient->on('receive', [$this, 'onReceive']);
        $this->swooleClient->on('error', [$this, 'onError']);
        $this->swooleClient->on('close', [$this, 'onClose']);
    }

    /**
     * @param string $provider
     * @param array $params
     * @return string
     * @throws \Icecave\Flax\Exception\EncodeException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function invoke(string $provider, array $params)
    {
        $data = duboo_buffer($provider, $params);
        $this->client->send($data);

        $response = $this->receive();


        return $response;
    }

    /**
     * @return string
     */
    public function receive()
    {
        $data = $this->client->recv(100000, 1);

        return $data;
    }

    /**
     * @param $host
     * @param $port
     * @param $timeout
     */
    public function tryReconnect($host, $port, $timeout)
    {
        if ($this->tryCount <= $this->maxTryCount) {
            $this->connect($host, $port, $timeout);
        }
    }

    /**
     * @param swoole_client $client
     * @return mixed
     */
    public function onConnect(swoole_client $client)
    {
    }

    /**
     * @param swoole_client $client
     * @param string $data
     * @return mixed
     */
    public function onReceive(swoole_client $client, $data)
    {
        return $data;
    }

    /**
     * @param swoole_client $client
     * @return mixed
     */
    public function onError(swoole_client $client)
    {
    }

    /**
     * @param swoole_client $client
     * @return mixed
     */
    public function onClose(swoole_client $client)
    {
    }

    /**
     * @param $name
     * @param $arguments
     * @return null|string|string[]
     * @throws \Icecave\Flax\Exception\EncodeException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function __call($name, $arguments)
    {
        return $this->invoke($name, $arguments[0]);
    }
}
