<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/27
 * Time: 10:51
 */

namespace app\index\controller;


use app\index\common\BaseController;
use app\index\model\HelpComment as HelpCommentModel;
use app\index\model\Help as HelpModel;

class HelpComment extends BaseController
{
    public function create()
    {
        //内容验证
        $data = input('param.');
        $checkResult = validateData($data, 'HelpComment');
        if ($checkResult === true) {
            //求助内容验证
            $help = HelpModel::get($data['help_id']);
            if (empty($help)) {
                return error('此求助不存在');
            }
            //前驱验证
            if (!is_null(input('prior')) and input('prior') != 0) {
                $prior = input('prior');
                $helpComment = HelpCommentModel::get($prior);
                if (empty($helpComment)) {
                    return error('此前驱不存在');
                } if ($helpComment->prior != -1) {
                    return error('不能在此留言下回复');
                }
            } else {
                $prior = -1;
            }
            $helpComment = new HelpCommentModel();
            $helpComment->saveComment($data,$prior);
            return success();
        } else {
            return error($checkResult);
        }
    }

    public function delete($help_comment_id)
    {
        $helpComment = HelpCommentModel::get($help_comment_id);
        if (empty($helpComment)) {
            return error('此留言不存在');
        }
        if ($helpComment->user_id != session('user.user_id')) {
            return error('无权操作');
        }
        //不同情况的删除
        if ($helpComment->prior != -1) {
            //非前驱
            $result = $helpComment->delete();
            if ($result) {
                return success();
            } else {
                return error('删除失败');
            }
        } else {
            //前驱 请用户确认，将删除其下所有
            $result = HelpCommentModel::deleteWithPrior($help_comment_id);
            if ($result) {
                return success();
            } else {
                return error('删除失败');
            }

        }


    }

    //某一前驱的所有后继
    public function getOne($help_comment_id)
    {
        $helpComment = HelpCommentModel::get($help_comment_id);
        //验证
        if (empty($helpComment)) {
            return error('该评论不存在');
        }
        if ($helpComment->prior != -1) {
            return error('非前驱评论');
        }
        //查找
        $helpComment1 = HelpCommentModel::get($help_comment_id);
        $helpComments = HelpCommentModel::where('prior',$help_comment_id)
            ->select();
        $helpComments = $helpComments->toArray();
        array_push($helpComments,$helpComment1);
        return success($helpComments);
    }

    //得到所有前驱
    public function getAll($help_id)
    {
        // todo 分页
        $help = HelpModel::get($help_id);
        if (empty($help)) {
            return error('求助不存在');
        }
        $helpComment = HelpCommentModel::where('help_id',$help_id)
            ->select();
        if (count($helpComment)) {
            return success($helpComment);
        } else {
            return error('没有留言');
        }

    }

}