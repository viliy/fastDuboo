<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Storage;

use Zhaqq\FastDubbo\Contracts\StorageInterface;
use Zhaqq\FastDubbo\Exceptions\StorageException;

/**
 * Class Zookeeper
 * @package Zhaqq\FastDubbo\Storage
 */
class Zookeeper implements StorageInterface
{

    /**
     * @var array
     */
    protected $options = [
        'host' => '127.0.0.1:2181',
        'path' => '/dubbo',
        'prefix' => 'zk.',
    ];

    /**
     * @var array
     */
    protected $projects = [];

    /**
     * @var ZookeeperF
     */
    protected $client;

    protected $colony = [];

    protected $maxTry = 20;

    public function __construct(array $options)
    {
        $this->setOptions($options);
        $this->initClient();
    }

    /**
     * @param $projects
     * @param $time
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function providers($projects, $time = 3600)
    {
        if (!$this->client instanceof \Zookeeper || 1 !== $this->client->getState()) {
            $this->initClient();
        }

        foreach ($projects as $key => $value) {
            $providers = $this->client->getChildren("{$this->options['path']}/{$value['uri']['path']}/providers", [$this, 'watcher']);

            foreach ($providers as $provider) {
                $info = parse_url(urldecode($provider));
                parse_str($info['query'], $args);
                if (isset($args['version']) && $value['uri']['version'] == $args['version']) {
                    $cacheKey = $this->options['prefix'] . $key;
                    $item = cache()->getItem($cacheKey);
                    $item->set($provider);

                    cache()->save($item);
                }
            }
        }
    }

    public function watcher($type, $state, $key)
    {
        echo "Watcher: $key", PHP_EOL;

        if ($type == 4) {
            $this->client->getChildren($key, [$this, 'watcher']);
        }
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        /*   zookeeper 重连机制存在问题暂不考虑这部分实现
        if (isset($options['colony'])) {
            $this->colony = $options['colony'];
        }
       */
        $this->options = array_merge($this->options, $options);
    }

    protected function initClient()
    {
        $this->client = new \Zookeeper($this->options['host'] . ':' . $this->options['port']);
    }
}