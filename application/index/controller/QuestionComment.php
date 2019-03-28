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
            $questionComment->saveComment($data,$prior);
            return success();
        } else {
            return error($checkResult);
        }
    }

}