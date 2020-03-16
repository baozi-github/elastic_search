<?php


namespace app\index\controller;

use Elasticsearch\ClientBuilder;


class SearchTest
{
    /**
     * @var \Elasticsearch\Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = ClientBuilder::create()->build();
    }

    // 保存索引
    public function index()
    {
        try{
            $params = [
                'index' => 'myindex',
                'type' => 'mytype',
                'id' => 'my_id',
                'body' => ['testField' => 'abc']
            ];
            $res = $this->client->index($params);
            print_r($res);
        }catch (\Exception $e){
            return $e->getMessage();
        }

        return '1';
    }

    // 获取指定id 的内容
    public function searchGet($id, $index)
    {
        try{
            $res = $this->client->get([
                'index' => $index,
                'type' => 'mytype',
                'id' => $id
            ]);
            print_r($res);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
    }

    // 获取改索引下的所有内容
    public function search($index)
    {
        try{
            $res = $this->client->search([
                'index' => $index,
                'type' => 'mytype',
            ]);
            print_r($res);
        }catch (\Exception $e){
            print_r($e->getMessage());
        }
    }

    public function searchDoc($index, $doc)
    {
        $params = [
            'index' => $index,
            'body' => [
                'query' => [
                    'match' => [
                        'testField' => $doc
                    ]
                ],
            ],
        ];
        $res = $this->client->search($params);
        print_r($res);
    }
}