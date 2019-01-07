<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */

namespace Zhaqq\FastDubbo\Process;

use FastD\Swoole\Process;
use Zhaqq\FastDubbo\Storage\Storage;
use swoole_process;

class StorageProcess extends Process
{
    /**
     * @param swoole_process $swoole_process
     */
    public function handle(swoole_process $swoole_process)
    {
        timer_tick(config()->get('duboo.timer_tick', 60), function () {
            app()->get(Storage::class)->providers(
                config()->get('dubbo.projects'),
                config()->get('dubbo.project.time', 60)
            );
        });
    }
}
