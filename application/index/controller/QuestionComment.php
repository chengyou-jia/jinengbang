<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/28
 * Time: 15:23
 */

namespace app\index\controller;


use app\index\common\BaseController;
use app\index\model\Question as QuestionModel;
use app\index\model\QuestionComment as QuestionCommentModel;

class QuestionComment extends BaseController
{

    public function create()
    {
        //内容验证
        $data = input('param.');
        $checkResult = validateData($data, 'QuestionComment');
        if ($checkResult === true) {
            //求助内容验证
            $question = QuestionModel::get($data['question_id']);
            if (empty($question)) {
                return error('此提问不存在');
            }
            //前驱验证
            if (!is_null(input('prior')) and input('prior') != 0) {
                $prior = input('prior');
                $questionComment = QuestionCommentModel::get($prior);
                if (empty($questionComment)) {
                    return error('此前驱不存在');
                } if ($questionComment->prior != -1) {
                    return error('不能在此评论下回复');
                }
            } else {
                $prior = -1;
            }
            $questionComment = new QuestionCommentModel();
            $result = $questionComment->saveComment($data,$prior);
            if ($result) {
                return success();
            } else {
                return error();
            }
        } else {
            return error($checkResult);
        }
    }
    
    public function delete($question_comment_id)
    {
        $questionComment = QuestionCommentModel::get($question_comment_id);
        if (empty($questionComment)) {
            return error('此留言不存在');
        }
        if ($questionComment->user_id != session('user.user_id')) {
            return error('无权操作');
        }
        //不同情况的删除
        if ($questionComment->prior != -1) {
            //非前驱
            $result = $questionComment->delete();
            if ($result) {
                return success();
            } else {
                return error('删除失败');
            }
        } else {
            //前驱 请用户确认，将删除其下所有
            $result = questionCommentModel::deleteWithPrior($question_comment_id);
            if ($result) {
                return success();
            } else {
                return error('删除失败');
            }

        }

    }

    //某一前驱的所有后继
    public function getOne($question_comment_id)
    {
        $questionComment = QuestionCommentModel::get($question_comment_id);
        //验证
        if (empty($questionComment)) {
            return error('该评论不存在');
        }
        if ($questionComment->prior != -1) {
            return error('非前驱评论');
        }
        //查找
        $questionComment1 = questionCommentModel::get($question_comment_id);
        $questionComments = questionCommentModel::where('prior',$question_comment_id)
            ->order('create_time desc')->select();
        $questionComments = $questionComments->toArray();
        for ($i = 0; $i < count($questionComments); $i++) {
            $questionComments[$i] = addUserNickname($questionComments[$i]);
        }
        $questionComment1 = $questionComment1->toArray();
        $questionComment1 = addUserNickname($questionComment1);
        array_unshift($questionComments,$questionComment1);
        return success($questionComments);
    }

    //得到所有前驱
    public function getAll($question_id)
    {
        // todo 分页
        $question = questionModel::get($question_id);
        if (empty($question)) {
            return error('求助不存在');
        }
        $questionComment = questionCommentModel::where('question_id',$question_id)
            ->where('prior',-1)->order('create_time desc')
            ->select();
        if (count($questionComment)) {
            $questionComment = $questionComment->toArray();
            for ($i = 0; $i < count($questionComment); $i++) {
                $questionComment[$i] = addUserNickname($questionComment[$i]);
            }
            return success($questionComment);
        } else {
            return error('没有留言');
        }

    }

}