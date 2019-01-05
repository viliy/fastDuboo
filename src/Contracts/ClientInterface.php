<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Contracts;


interface ClientInterface
{

    public function invoke(string $provider, array $params);
}