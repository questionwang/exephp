<?php

/**
 * Created by PhpStorm.
 * User: wlz
 * Date: 2015/12/19
 * Time: 21:55
 */
header("Content-Type:text/plain;charset=utf-8");

//设置页面内容类型和编码格式
//header("Content-Type:application/json;charset=utf-8");
//header("Content-Type:application/javascript;charset=utf-8");
//header("Content-Type:text/xml;charset=utf-8");
//header("Content-Type:text/html;charset=utf-8");


/*第一部分：数据获得与处理*/
//1:静态数据
//2:通过数据库获得的数据


/*第二部分：定义后端本页面功能*/
//类中全局变量获得方式global $data;
class jsoncode
{

    private $thecodes=null;//接受的代码
    private $backcodes=null;//返回出去的代码-----应该是一个json的形式出去的
    public function getData(){
        //此时的参数应该文本形式的数据，在这里通过判断参数给出对一个的结果，结果要通过json形式格式化，并返回这个格式化后的json字符串
        //接受get方式传来的codes
        $codes=isset($_GET['codes'])? $_GET['codes'] : null;
        if(is_null($codes)) {
            echo '参数没有传进来';
            die();
        }
        //将得到的代码传出去

        $code=str_replace(PHP_EOL, '', $codes);//去掉所有的换行符号。统一成一行代码
        $this->thecodes=$code;                      //将数据赋值给属性。

    }

    public function setData(){
        //如果代码已经成功的被接收了
        $torun=$this->thecodes;
        if(!is_null($torun)){
            exec('php -r '.'"'.$torun.';"',$this->backcodes);//关于这个单双引号真是他m的dt,,高了好长时间才调试好。
        }

    }

    public function reaction(){
        $jsonCodes=json_encode($this->backcodes);
//        $jsonCodes=$this->backcodes;
        return $jsonCodes;
    }

}


/*第三部分：业务逻辑执行*/

//在这里进行业务
$op=new jsoncode();
$op->getData();
$op->setData();
//print_r($op->reaction());
echo $op->reaction();