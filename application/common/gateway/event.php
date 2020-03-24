<?php
namespace app\common\gateway;
use \GatewayWorker\Lib\Gateway;

class event
{

    public static function onWorkerStart($businessWorker)
    {
        echo "BusinessWorker    Start\n";
    }

    public static function onConnect($client_id)
    {
        Gateway::sendToClient($client_id, json_encode(['type' => 'init', 'client_id' => $client_id]));
    }

    protected function onMessage($client_id, $message)
    {
        Gateway::sendToClient($client_id, json_encode($message));
    }

}
