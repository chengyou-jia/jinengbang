<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/28
 * Time: 13:59
 */

namespace app\index\controller;


use app\index\common\BaseController;
use app\index\model\User as UserModel;
use app\index\model\Question as QuestionModel;

class Question extends BaseController
{
    public function create()
    {
        $data = input('param.');
        $checkResult = validateData($data,'Question');
        if ($checkResult === true) {
            $user_id = session('user.user_id');
            $user = UserModel::get($user_id);
            //存储
            $result = $user->questions()->save([
                'content' => $data['content'],
                'type' => $data['type']
            ]);
            if ($result) {
                return success();
            } else {
                return error('新增失败');
            }

        } else {
            return error($checkResult);
        }


    }

    public function update($question_id)
    {
        $data = input('param.');
        $checkResult = validateData($data,'Question');
        if ($checkResult === true) {
            $user_id = session('user.user_id');
            $user = UserModel::get($user_id);
            //查找
            $question = $user->questions()->where('question_id',$question_id)->find();
            //验证
            if (empty($question)){
                return error('你没有此提问');
            }
            $result = $question->save([
                'content' => $data['content'],
                'type' => $data['type']
            ]);
            if ($result) {
                return success();
            } else {
                return error('更新失败');
            }
        } else {
            return error($checkResult);
        }


    }

    //待补充，逻辑删除
    public function delete($question_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $question= $user->questions()->where('question_id',$question_id)->find();
        if (empty($question)) {
            return error('你没有此求助');
        } else {
            $result = $question->delete();
            if ($result) {
                return success();
            } else {
                return error('删除失败');
            }
        }
        

    }

    public function getAll()
    {
        //todo 分页
        $questions = QuestionModel::all();
        if (count($questions)) {
            return success($questions);
        } else {
            return error('你没有标签');
        }
    }

    public function getOne($question_id)
    {
        $question = QuestionModel::get($question_id);
        if (!empty($question)) {
            return success($question);
        } else {
            return error('没有此提问');
        }

    }

}