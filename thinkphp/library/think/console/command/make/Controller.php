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

namespace think\console\command\make;

use think\console\command\Make;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;

class Controller extends Make
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('make:controller')
            ->setDescription('Create a new controller class')
            ->addArgument('namespace', Argument::REQUIRED)
            ->addOption('module', 'm', Option::VALUE_OPTIONAL, 'Module Name', null)
            ->addOption('extend', 'e', Option::VALUE_OPTIONAL, 'Base on Controller class', null);
    }

    protected function execute(Input $input, Output $output)
    {
        $namespace = $input->getArgument('namespace');
        $module    = $input->getOption('module');
        $extend    = $input->getOption('extend');
        $result    = $this->getResult('controller', $namespace, $module, $extend);
        $output->writeln("output:" . $result);
    }

}
