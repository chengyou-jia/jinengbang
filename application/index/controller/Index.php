<?php
namespace app\index\controller;

class Index
{
    public function index()
    {

        echo '首页';
    }

    public function miss()
    {
        echo "路由错误";
    }

    //xdebug演示
    public function  Xdebug()
    {
        $arr = array();
        for ($i = 0; $i < 10; $i++) {
            array_push($arr,'a');
            session('arr',$arr);
        }
        echo 111;


    }


}
