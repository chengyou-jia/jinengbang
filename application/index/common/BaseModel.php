<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 10:31
 */

namespace app\index\common;


use think\Model;
use think\model\concern\SoftDelete;

class BaseModel extends Model
{
    use SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 1;

    protected $connection = 'db_config';


    public function saveMultiFile()
    {

    }

}