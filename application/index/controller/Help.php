<?php
/**
 * Created by PhpStorm.
 * Author: 贾成铕
 * Email: jiachengyou@tiaozhan.com
 * Date: 2019/3/26
 * Time: 21:39
 */

namespace app\index\controller;


use app\index\common\BaseController;
use app\index\model\User as UserModel;
use app\index\model\Help as HelpModel;

class Help extends BaseController
{

    public function create()
    {
        $data = input('param.');
        $checkResult = validateData($data,'Help');
        if ($checkResult === true) {
            $user_id = session('user.user_id');
            $user = UserModel::get($user_id);
            $is_official = $user->is_official;
            //存储
            $result = $user->helps()->save([
                'title' => $data['title'],
                'content' => $data['content'],
                'is_free' => $data['is_free'],
                'askfor_type' => $data['askfor_type'],
                'type' => $data['type'],
                'publisher' => $is_official
            ]);
            if ($result) {
                $help = $user->helps()->where(true)->order('create_time desc')->find();
                $data = array('help_id'=>$help->help_id);
                return success($data);
            } else {
                return error('新增失败');
            }
        } else {
            return error($checkResult);
        }

    }

    public function update($help_id)
    {
        $data = input('param.');
        $checkResult = validateData($data,'Help');
        if ($checkResult === true) {
            $user_id = session('user.user_id');
            $user = UserModel::get($user_id);
            //查找
            $help = $user->helps()->where('help_id',$help_id)->find();
            //验证
            if (empty($help)){
                return error('你没有此求助');
            }

            $result = $help->save([
                'title' => $data['title'],
                'content' => $data['content'],
                'is_free' => $data['is_free'],
                'askfor_type' => $data['askfor_type'],
                'type' => $data['type'],
                // todo 图片待存储
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

    public function delete($help_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        $help= $user->helps()->where('help_id',$help_id)->find();
        if (empty($help)) {
            return error('你没有此求助');
        } else {
            $result = $help->delete();
            if ($result) {
                return success();
            } else {
                return error('删除失败');
            }
        }
    }

    public function getOne($help_id)
    {
        $help = HelpModel::get($help_id);
        if ($help) {
            $help->browse = $help->browse + 1;
            $result = $help->save();
            if ($result) {
                $help = $help->toArray();
                $help = addUserNickname($help);
                return success($help);
            } else {
                return error('获取失败');
            }
        } else {
            return error('你没有此求助');
        }
        

    }

    public function getAll()
    {
        $type = input('has_finished');
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        if ($type == 'all') {
            $helps = $user->helps()->where(true)
                ->order('create_time desc')->select();
        } else if ($type == 0) {
            $helps = $user->helps()->where('has_finished',0)
                ->order('create_time desc')->select();
        } else if ($type == 1) {
            $helps = $user->helps()->where('has_finished',1)
                ->order('create_time desc')->select();
        } else {
            return error('参数错误');
        }
        if (count($helps)) {
            $helps = $helps->toArray();
            for ($i = 0; $i < count($helps); $i++) {
                $helps[$i] = addUserNickname($helps[$i]);
            }
            return success($helps);
        } else {
            return error('你没有求助');
        }
    }

    public function getAllHelps()
    {
        $page = input('page');
        if ($page < 1) {
            return error('参数错误');
        }
        $limit = input('limit');
        $start = ($page - 1) * $limit;
        $is_free = input('is_free');
        $askfor_type = input('askfor_type');
        $publisher = input('publisher');
        $help = new HelpModel();
        $help = $help->where('has_finished',0);
        if ($askfor_type != 'all') {
            $help = $help->where('askfor_type',$askfor_type);
        }
        if ($is_free != 'all') {
            $help = $help->where('is_free',$is_free);
        }
        if ($publisher != 'all') {
            $help = $help->where('publisher',$publisher)
                ->order('create_time desc')->limit($start,$limit)->select();
        } else {
            $help = $help->where(true)
                ->order('create_time desc')->limit($start,$limit)->select();
        }
        if (count($help)) {
            $help = $help->toArray();
            for ($i = 0; $i < count($help); $i++) {
                $help[$i] = addUserNickname($help[$i]);
            }
            return success($help);
        } else {
            return error('没有内容');
        }
    }

    // todo 妈的好难写
    public function getHelpsByWord()
    {
        $page = input('page');
        if ($page < 1) {
            return error('参数错误');
        }
        $limit = input('limit');
        $start = ($page - 1) * $limit;
        $word = input('word');
        $is_free = input('is_free');
        $askfor_type = input('askfor_type');
        $publisher = input('publisher');
        $help = new HelpModel();
        $help = $help->where(true);
        if ($askfor_type != 'all') {
            $help = $help->where('askfor_type',$askfor_type);
        }
        if ($is_free != 'all') {
            $help = $help->where('is_free',$is_free);
        }
        if ($publisher != 'all') {
            $help = $help->where('publisher',$publisher);
        }
        if (!strlen($word)) {
            return error('word不能为空');
        }
        $help = $help->where('title|content','like','%'.$word.'%')
            ->order('create_time desc')->limit($start,$limit)->select();
        if (count($help)) {
            $help = $help->toArray();
            for ($i = 0; $i < count($help); $i++) {
                $help[$i] = addUserNickname($help[$i]);
            }
            return success($help);
        } else {
            return error('没有内容');
        }
    }

    public function helpLike($help_id)
    {
        $help = HelpModel::get($help_id);
        if (empty($help)) {
            return error('此求助不存在');
        }
        $help->like = $help->like + 1;
        $result = $help->save();
        if ($result) {
            return success();
        } else {
            return error();
        }
    }

    public function complaintHelp($help_id)
    {
        $help = HelpModel::get($help_id);
        //验证
        if (empty($help)) {
            return error('该求助不存在');
        }
        if ($help->is_complaint == 1) {
            return error('该求助已被投诉，请等待管理员处理');
        }
        // 操作
        $help->is_complaint = 1;
        $result = $help->save();
        if ($result) {
            return success();
        } else {
            return error();
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
        $help = new HelpModel();
        if ($mode == 'all') {
            $help = $help->where(true)
                ->order('create_time desc')->limit($start,$limit)->select();
        } elseif ($mode == 1) {
            $help = $help->where('is_complaint',1)
                ->order('create_time desc')->limit($start,$limit)->select();
        } else {
            return error('参数错误');
        }
        if (!count($help)) {
            return error('没有内容');
        } else {
            $help = $help->toArray();
            for ($i = 0; $i < count($help); $i++) {
                $help[$i] = addUserNickname($help[$i]);
            }
            return success($help);
        }
    }

    public function adminDelete($help_id)
    {
        $help = HelpModel::get($help_id);
        //验证
        if (empty($help)) {
            return error('该求助不存在');
        }
        if (!is_admin()) {
            return error('没有权限');
        }
        // 操作
        $result = $help->delete();
        if ($result) {
            return success();
        } else {
            return error();
        }
    }

    public function addHelpPicture($help_id)
    {
        $user_id = session('user.user_id');
        $user = UserModel::get($user_id);
        //验证
        $help = $user->helps()->where('help_id',$help_id)->find();
        if (empty($help)){
            return error('你没有此求助');
        }
        $result = getOneFile('img','help');
        if (!$result['result']) {
            return error($result['message']);
        } else {
            $help->picture = $help->picture.$result['message'].'||';
            $result = $help->save();
            if ($result) {
                return success();
            } else {
                return error('图片上传失败');
            }
        }
    }
}