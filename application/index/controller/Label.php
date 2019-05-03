<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/25
 * Time: 21:21
 */

namespace app\index\controller;



use app\index\common\BaseController;
use app\index\model\User as UserModel;

class Label extends BaseController
{
    static protected $typeSum = 4;

    public function create()
    {
        $data = input('param.');
        $checkResult = validateData($data,'Label');
       if ($checkResult === true) {
           $user_id = session('user.user_id');
           $user = UserModel::get($user_id);
           // 验证
           if ($user->isHadLabelType($data['type'])) {
               return error('已有此类型');
           }
           //存储
           //这里allowFiled好坑
           $result = $user->labels()->save(
               ['type'=>$data['type'],'description'=>$data['description']]);
           if ($result) {
               return success();
           } else {
               return error('新增失败');
           }
       } else {
           return error($checkResult);
       }

    }

    public function update($label_id)
    {
        $data = input('param.');
        $checkResult = validateData($data,'Label','update');
        if ($checkResult === true) {
            $user_id = session('user.user_id');
            $user = UserModel::get($user_id);
            $label= $user->labels()->where('label_id',$label_id)->find();
            if (empty($label)) {
                return error('你没有此标签');
            } else {
                $label->description = $data['description'];
                $result = $label->save();
                if ($result) {
                    return success();
                } else {
                    return error('更新失败');
                }
            }

        } else {
            return error($checkResult);
        }

    }

    public function delete($label_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $label= $user->labels()->where('label_id',$label_id)->find();
        if (empty($label)) {
            return error('你没有此标签');
        } else {
            $result = $label->delete();
            if ($result) {
                return success();
            } else {
                return error('删除失败');
            }
        }

    }

    public function getAll()
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $labels = $user->labels()->where(true)->order('score','desc')->select();
        if (count($labels)) {
            return success($labels);
        } else {
            return error('你没有标签');
        }
    }

    public function getOne($label_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $label = $user->labels()->where('label_id',$label_id)->find();
        if (!empty($label)) {
            return success($label);
        } else {
            return error('你没有标签');
        }
    }

    public function getAllRank()
    {
        $arr = [];
        // todo 待定类型
        $label = model('Label');
        for ($i = 1; $i < self::$typeSum + 1; $i++) {
            $data = $label->getLabelRank($i);
            array_push($arr,$data);
        }
        return success($arr);
    }

    public function getScoreSumRank()
    {
        $user = model('User');
        $user = $user->where(true)->limit(10)
            ->order('score','desc')->select();
        return success($user);
    }

    public function myRank()
    {
        $arr = [];
        $user_id = session('user.user_id');
        $user = model('User');
        $user = $user->where(true)->limit(30)
            ->order('score','desc')->select();
        $rank = false;
        $score = 0;
        for ($i = 0; $i < count($user); $i++) {
            if ($user[$i]['user_id'] == $user_id) {
                $rank = $i+1;
                $score = $user[$i]['score'];
                break;
            }
        }
        $scoreSum = array('rank'=>$rank,'score'=>$score);
        array_push($arr,$scoreSum);
        $label = model('Label');
        for ($i = 1; $i < self::$typeSum + 1; $i++) {
            $data = $label->getMyRank($i);
            array_push($arr,$data);
        }
        $user = UserModel::get($user_id);
        $help_num = $user->help_num;
        $data = array('help_num'=>$help_num,'rank'=>$arr);

        return success($data);
    }


}