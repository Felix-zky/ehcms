<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 刘志淳 <chun@engineer.com>
// +----------------------------------------------------------------------

namespace think\console\command;

use think\App;
use think\Exception;

class Make extends Command
{

    // 创建文件
    protected static function buildFile($file, $content)
    {
        if (is_file($file)) {
            throw new Exception('file already exists');
        }

        if (!is_dir(dirname($file))) {
            mkdir(strtolower(dirname($file)), 0644, true);
        }

        file_put_contents($file, $content);
    }

    // 生成类库文件
    protected function build($namespace, $extend, $content = '')
    {
        $tpl = file_get_contents(THINK_PATH . 'tpl' . DS . 'make.tpl');

        // comminute namespace
        $namespace = explode('\\', $namespace);
        $className = array_pop($namespace);

        if ($extend) {
            $extend = 'extends \\' . ltrim($extend, '\\');
        }
        // 处理内容
        $content = str_replace(['{%extend%}', '{%className%}', '{%namespace%}', '{%content%}'],
            [$extend, $className, implode('\\', $namespace), $content],
            $tpl);

        // 处理文件名
        array_shift($namespace);
        $file = APP_PATH . implode(DS, $namespace) . DS . $className . '.php';
        // 生成类库文件
        self::buildFile($file, $content);

        return realpath($file);
    }

    protected function getResult($layer, $namespace, $module, $extend, $content = '')
    {

        // 处理命名空间
        if (!empty($module)) {
            $namespace = App::$namespace . "\\" . $module . "\\" . $layer . "\\" . $namespace;
        }

        // 处理继承
        if (empty($extend)) {
            switch ($layer) {
                case 'model':
                    $extend = '\\think\\Model';
                    break;
                case 'validate':
                    $extend = '\\think\\Validate';
                    break;
                case 'controller':
                default:
                    $extend = '';
                    break;
            }
        } else {
            if (!preg_match("/\\\/", $extend)) {
                if (!empty($module)) {
                    $extend = "\\" . App::$namespace . "\\" . $module . "\\" . $layer . "\\" . $extend;
                }
            }
        }

        return $this->build($namespace, $extend, $content);
    }
}
