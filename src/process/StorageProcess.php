<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Process;

use FastD\Swoole\Process;
use Zhaqq\FastDubbo\Storage\Storage;

class StorageProcess extends Process
{
    /**
     * @param swoole_process $swoole_process
     */
    public function handle(swoole_process $swoole_process)
    {
        timer_tick(config()->get('duboo.timer_tick'), function () {
            app()->get(Storage::class)->providers(
                config()->get('dubbo.projects'),
                config()->get('dubbo.project.time')
            );
        });
    }
}