<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Storage;

use Zhaqq\FastDubbo\Contracts\StorageInterface;
use Zhaqq\FastDubbo\Exceptions\StorageException;

class Storage implements StorageInterface
{
    /**
     * @var StorageInterface
     */
    protected $storage;

    public function __construct(string $storage = Zookeeper::class, array $options = [])
    {
        $this->setStorage($storage, $options);
    }

    /**
     * @param $storage
     */
    public function setStorage(string $storage, array $options)
    {
        if (class_exists($storage)) {
            $this->storage = new $storage($options);
        } else {
            throw new StorageException("the class:$storage is not exist");
        }
    }

    public function providers($projects, $time)
    {
        $this->storage->providers($projects, $time);
    }
}