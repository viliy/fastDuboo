<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Storage;

use Zhaqq\FastDubbo\Contracts\StorageInterface;

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
        'host' => '127.0.0.1',
        'port' => 2181,
        'path' => '/dubbo',
        'prefix' => 'zk.',
    ];

    /**
     * @var array
     */
    protected $projects = [];

    protected $client;

    public function __construct(array $options)
    {
        $this->setOptions($options);
    }

    /**
     * @param $projects
     * @param $time
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function providers($projects, $time)
    {
        if (!$this->client instanceof \Zookeeper) {
            $this->initClient();
        }

        foreach ($projects as $key => $value) {
            $providers = $this->client->getChildren("{$this->options['path']}/{$value['uri']['path']}/providers");
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

    public function watch()
    {

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
        $this->options = array_merge($this->options, $options);
    }

    protected function initClient()
    {
        $this->client = new \Zookeeper($this->options['host'] . ':' . $this->options['port']);
    }
}