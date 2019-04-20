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
                'type' => $data['type'],
                'is_anonymous' => $data['is_anonymous'],
                'title' => $data['title']
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
                'type' => $data['type'],
                'is_anonymous' => $data['is_anonymous'],
                'title' => $data['title']
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
        if ($question) {
            $question->browse = $question->browse + 1;
            $result = $question->save();
            if ($result) {
                return success($question);
            } else {
                return error('获取失败');
            }

        } else {
            return error('没有此提问');
        }
    }

    public function questionLike($question_id)
    {
        $question = QuestionModel::get($question_id);
        if (empty($question)) {
            return error('此求助不存在');
        }
        $question->like = $question->like + 1;
        $result = $question->save();
        if ($result) {
            return success();
        } else {
            return error();
        }
    }

    public function complaintQuestion($question_id)
    {
        $question = QuestionModel::get($question_id);
        //验证
        if (empty($question)) {
            return error('该提问不存在');
        }
        if ($question->is_complaint == 1) {
            return error('该提问已被投诉，请等待管理员处理');
        }
        // 操作
        $question->is_complaint = 1;
        $result = $question->save();
        if ($result) {
            return success();
        } else {
            return error();
        }
    }

    public function getAllQuestions()
    {
        $type = input('type');
        $is_anonymous = input('is_anonymous');
        $question = new QuestionModel();
        $question = $question->where(true);
        if ($type != 'all') {
            $question = $question->where('type',$type);
        }
        if ($is_anonymous != 'all') {
            $question = $question->where('is_anonymous',$is_anonymous)->select();
        } else {
            $question = $question->where(true)->select();
        }
        if (count($question)) {
            return success($question);
        } else {
            return error('没有内容');
        }
    }

    public function getQuestionsByWord()
    {
        $word = input('word');
        $question = new QuestionModel();
        $question = $question->where('title|content','like','%'.$word.'%')->select();
        if (count($question)) {
            return success($question);
        } else {
            return error('没有内容');
        }
    }

    public function adminGet()
    {
        $mode = input('mode');
        // 验证
        if (!is_admin())
        {
            return error('没有权限');
        }
        $question = new QuestionModel();
        if ($mode == 'all') {
            $question = $question->where(true)->select();
        } elseif ($mode == 1) {
            $question = $question->where('is_complaint',1)->select();
        } else {
            return error('参数错误');
        }
        $total = count($question);
        if (!$total) {
            return error('没有内容');
        } else {
            $data = array('total'=>$total,'data'=>$question);
            return success($data);
        }
    }

    public function adminDelete($question_id)
    {
        $question = questionModel::get($question_id);
        //验证
        if (empty($question)) {
            return error('该提问不存在');
        }
        if (!is_admin()) {
            return error('没有权限');
        }
        // 操作
        $result = $question->delete();
        if ($result) {
            return success();
        } else {
            return error();
        }
    }

}