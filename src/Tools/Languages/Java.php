<?php
/**
 * Idiot
 *  - Dubbo Client in Zookeeper.
 *
 * Licensed under the Massachusetts Institute of Technology
 *
 * For full copyright and license information, please see the LICENSE file
 * Redistributions of files must retain the above copyright notice.
 *
 * @author   Lorne Wang < post@lorne.wang >
 * @link     https://github.com/lornewang/idiot
 */
namespace Zhaqq\FastDubbo\Tools\Languages;

use Zhaqq\FastDubbo\Tools\JavaType;

class Java extends AbstractLanguage
{
    private $typeRefsMap = [
        JavaType::SHORT => 'S',
        JavaType::INT => 'I',
        JavaType::LONG => 'J',
        JavaType::DOUBLE => 'D',
        JavaType::BOOLEAN => 'Z',
        JavaType::STRING => 'Ljava/lang/String;'
    ];

    public function typeRef($type)
    {
        return (strpos($type, '.') === FALSE ? $this->typeRefsMap[$type] : 'L' . str_replace('.', '/', $type) . ';');
    }
}