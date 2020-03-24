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

class busi_start extends Command
{
    protected function configure()
    {
        $this->setName('busi_start')
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
        $this->startBusinessworker();


        Worker::runAll();
    }

    protected function startBusinessworker()
    {
        $worker = new BusinessWorker();
        $worker->name = "businessWorker";
        $worker->count = 1;
        $worker->registerAddress = '127.0.0.1:12360';
        $worker->eventHandler = event::class;
    }

}