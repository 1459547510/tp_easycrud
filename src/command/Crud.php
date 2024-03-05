<?php

namespace Easycrud\command;

use Easycrud\Create\Controller;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;

/**
 * php think create index/controller/User:index/model/UserModel
 */
class Crud extends Command
{
    protected function configure()
    {
        $this->setName('create')
            ->addArgument('name', Argument::REQUIRED, "Controller namespace and model namespace")
            ->setDescription('创建一个crud类');
    }

    protected function execute(Input $input, Output $output)
    {
        $param = $input->getArgument('name');
        $controller = new Controller($param);
        $controller->run();
        $output->writeln("Success");
    }
}
