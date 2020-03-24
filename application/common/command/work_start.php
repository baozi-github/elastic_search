<?php


namespace app\common\command;


use app\common\gateway\event;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use Workerman\Worker;

class work_start extends Command
{
    protected function configure()
    {
        $this->setName('work_start')
            ->addArgument('action', Argument::OPTIONAL, "action")
            //->addOption('city', null, Option::VALUE_REQUIRED, 'city name')
            ->setDescription('Say Hello');
    }

    protected function execute(Input $input, Output $output)
    {
        $action = trim($input->getArgument('action'));
        if (!in_array($action, ['start', 'stop'])) {
            $output->writeln("参数错误");
        }

        $this->start();
    }

    protected function start()
    {
        $this->startRegister();

        Worker::runAll();
    }

    protected function startRegister()
    {
        new Register('text://0.0.0.0:22360');
    }
}