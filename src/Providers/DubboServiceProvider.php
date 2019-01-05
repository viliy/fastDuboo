<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Providers;

use FastD\Container\ServiceProviderInterface;
use Zhaqq\FastDubbo\Client;
use Zhaqq\FastDubbo\Storage\Storage;
use Zhaqq\FastDubbo\Tools\Dubbo;

/**
 * Class DubboServiceProvider
 * @package Zhaqq\FastDubbo\Providers
 */
class DubboServiceProvider implements ServiceProviderInterface
{

    /**
     * @param \FastD\Container\Container $container
     */
    public function register(\FastD\Container\Container $container)
    {
        $dubbo = array_merge(
            load(app()->getPath() . '/config/dubbo.php'),
            config()->get('dubbo', [])
        );

        config()->merge(['dubbo' => $dubbo]);

        $container->add(Storage::class, new Storage(config()->get('dubbo.driver'), config()->get('dubbo.options')));
        $container->add(Dubbo::class, new Dubbo());
        $container->add(Client::class, new Client());
    }
}
