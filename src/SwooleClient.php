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

    protected $maxTryCount = 3;

    protected $tryCount = 0;

    public function __construct($async = false)
    {
        $this->client = new swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_SYNC);
//        $this->client->set(
//            [
//                'open_eof_check' => true,
//                'package_eof' => true,
//                'package_max_length' => -1,
//                'socket_buffer_size' => -1
//
//            ]
//        );
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
        $this->client->on('connect', [$this, 'onConnect']);
        $this->client->on('receive', [$this, 'onReceive']);
        $this->client->on('error', [$this, 'onError']);
        $this->client->on('close', [$this, 'onClose']);
    }

    /**
     * @param string $provider
     * @param array $params
     * @throws \Icecave\Flax\Exception\EncodeException
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function invoke(string $provider, array $params)
    {
        $this->client->send(duboo_buffer($provider, $params));
    }

    public function receive()
    {
        $data = $this->client->recv(655350, 1 );

        $data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        return preg_replace('/(^.*?\{)/', '{', $data);
    }

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
}
