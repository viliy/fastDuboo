<?php
/**
 * @author: ZhaQiu <34485431@qq.com>
 * @time: 2019/1/5
 */
return [
    'driver' => \Zhaqq\FastDubbo\Storage\Zookeeper::class,
    'options' => [
        /* zookeeper 的重连机制存在bug */
//        'colony' => [
//            'master' => [
//                'host' => '127.0.0.1:2181',
//            ],
//            'slave_1' => [
//                'host' => '127.0.0.1:2181',
//
//            ],
//            'slave_2' => [
//                'host' => '127.0.0.1:2181',
//            ]
//        ],
        'host' => '127.0.0.1',
        'port' => 2181,
        'path' => '/dubbo',
        'prefix' => 'zk.',
    ],
    'time_tick' => 60,
    'projects' => [  // 调用服务列表
        'time' => 60, // 缓存时间
        'the provider name' => [   // 调用服务昵称
            "object" => [
                "name" => "",
                "method" => "",
            ],
            "uri" => [
                "path" => "",
                "version" => "1.0.0",
            ]
        ]
    ]
];
