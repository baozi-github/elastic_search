<?php


namespace app\common\command;


use app\common\gateway\event;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\Output;
use Workerman\Worker;

class start extends Command
{
    protected function configure()
    {
        $this->setName('gate_work')
            ->addArgument('action', Argument::OPTIONAL, "action")
            ->setDescription('start gateworker');
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
        $this->startBusinessworker();
        $this->startGateWay();
        Worker::runAll();
    }

    protected function startRegister()
    {
        new Register('text://0.0.0.0:22360');
    }

    protected function startBusinessworker()
    {
        $worker = new BusinessWorker();
        $worker->name = "businessWorker";
        $worker->count = 1;
        $worker->registerAddress = '127.0.0.1:12360';
        $worker->eventHandler = event::class;
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