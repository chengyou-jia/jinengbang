<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 21:21
 */

namespace app\index\model;


use app\index\common\BaseModel;

class Label extends BaseModel
{
    protected $table = 'label';
    protected $pk = 'label_id';

    static private $type_num = 4;

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function getLabelRank($type)
    {
        $data = $this->where('type',$type)->limit(10)
            ->order('score','desc')->select();
        for ($i = 0; $i < count($data); $i++) {
            $data[$i] = $data[$i]->toArray();
            $data[$i] = addUserNickname($data[$i]);
        }
        return $data;
    }

    public function getMyRank($type)
    {
        $user_id = session('user.user_id');
        $label = $this->where('type',$type)->limit(30)
            ->order('score','desc')->select();
        $rank = false;
        $score = 0;
        for ($i = 0; $i < count($label); $i++) {
            if ($label[$i]['user_id'] == $user_id) {
                $rank = $i+1;
                $score = $label[$i]['score'];
                break;
            }
        }
        return array('rank'=>$rank,'score'=>$score);
    }

}