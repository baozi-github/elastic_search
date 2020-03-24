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

class gate_start extends Command
{
    protected function configure()
    {
        $this->setName('gate_start')
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
        $this->startGateWay();

        Worker::runAll();
    }

    protected function startGateWay()
    {
        $gateway = new Gateway('websocket://0.0.0.0:12360');
        $gateway->name = 'gateway';

        $gateway->count = 1;
        $gateway->lanIp = '127.0.0.1';

        $gateway->startPort = 2300;
        $gateway->pingInterval = 30;
        $gateway->pingNotResponseLimit = 0;
        $gateway->pingData = '{"mode":"heart"}';
        $gateway->registerAddress = '127.0.0.1:12360';

    }

}