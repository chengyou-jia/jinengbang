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
                $question = $user->questions()->where(true)->order('create_time desc')->find();
                $data = array('help_id'=>$question->question_id);
                return success($data);
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
        $type = input('type');
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        if ($type == 'all') {
            $questions = $user->questions()->where(true)
                ->order('create_time desc')->select();
        } else {
            $questions = $user->questions()->where('type',$type)
                ->order('create_time desc')->select();
        }
        if (count($questions)) {
            $questions = $questions->toArray();
            for ($i = 0; $i < count($questions); $i++) {
                $questions[$i] = addUserNickname($questions[$i]);
            }
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
                $question = $question->toArray();
                $question = addUserNickname($question);
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
        $page = input('page');
        if ($page < 1) {
            return error('参数错误');
        }
        $limit = input('limit');
        $start = ($page - 1) * $limit;
        $type = input('type');
        $is_anonymous = input('is_anonymous');
        $question = new QuestionModel();
        $question = $question->where(true);
        if ($type != 'all') {
            $question = $question->where('type',$type);
        }
        if ($is_anonymous != 'all') {
            $question = $question->where('is_anonymous',$is_anonymous)
                ->order('create_time desc')->limit($start,$limit)->select();
        } else {
            $question = $question->where(true)
                ->order('create_time desc')->limit($start,$limit)->select();
        }
        if (count($question)) {
            $question = $question->toArray();
            for ($i = 0; $i < count($question); $i++) {
                $question[$i] = addUserNickname($question[$i]);
            }
            return success($question);
        } else {
            return error('没有内容');
        }
    }

    public function getQuestionsByWord()
    {

        $page = input('page');
        if ($page < 1) {
            return error('参数错误');
        }
        $limit = input('limit');
        $start = ($page - 1) * $limit;
        $word = input('word');
        $type = input('type');
        $is_anonymous = input('is_anonymous');
        $question = new QuestionModel();
        $question = $question->where(true);
        if ($type != 'all') {
            $question = $question->where('type',$type);
        }
        if ($is_anonymous != 'all') {
            $question = $question->where('is_anonymous',$is_anonymous);
        }
        if (!strlen($word)) {
            return error('word不能为空');
        }
        $question = $question->where('title|content','like','%'.$word.'%')
            ->order('create_time desc')->limit($start,$limit)->select();
        if (count($question)) {
            $question = $question->toArray();
            for ($i = 0; $i < count($question); $i++) {
                $question[$i] = addUserNickname($question[$i]);
            }
            return success($question);
        } else {
            return error('没有内容');
        }
    }

    public function adminGet()
    {
        $page = input('page');
        if ($page < 1) {
            return error('参数错误');
        }
        $limit = input('limit');
        $start = ($page - 1) * $limit;
        $mode = input('mode');
        // 验证
        if (!is_admin())
        {
            return error('没有权限');
        }
        $question = new QuestionModel();
        if ($mode == 'all') {
            $question = $question->where(true)
                ->order('create_time desc')->limit($start,$limit)->select();
        } elseif ($mode == 1) {
            $question = $question->where('is_complaint',1)
                ->order('create_time desc')->limit($start,$limit)->select();
        } else {
            return error('参数错误');
        }
        if (!count($question)) {
            return error('没有内容');
        } else {
            $question = $question->toArray();
            for ($i = 0; $i < count($question); $i++) {
                $question[$i] = addUserNickname($question[$i]);
            }
            return success($question);
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

    public function addQuestionPicture($question_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        //验证
        $question = $user->questions()->where('question_id',$question_id)->find();
        if (empty($question)){
            return error('你没有此提问');
        }
        $result = getOneFile('img','question');
        if (!$result['result']) {
            return error($result['message']);
        } else {
            $question->picture = $question->picture.$result['message'].'||';
            $result = $question->save();
            if ($result) {
                return success();
            } else {
                return error('图片上传失败');
            }
        }
    }

}