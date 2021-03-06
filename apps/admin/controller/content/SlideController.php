<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年3月1日
 *  轮播图控制器
 */
namespace app\admin\controller\content;

use core\basic\Controller;
use app\admin\model\content\SlideModel;

class SlideController extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new SlideModel();
    }

    // 轮播图列表
    public function index()
    {
        if ((! ! $id = get('id', 'int')) && $result = $this->model->getSlide($id)) {
            $this->assign('more', true);
            $this->assign('slide', $result);
        } else {
            $this->assign('list', true);
            if (! ! ($field = get('field')) && ! ! ($keyword = get('keyword'))) {
                $result = $this->model->findSlide($field, $keyword);
            } else {
                $result = $this->model->getList();
            }
            $this->assign('slides', $result);
        }
        $this->display('content/slide.html');
    }

    // 轮播图增加
    public function add()
    {
        if ($_POST) {
            // 获取数据
            $gid = post('gid', 'int');
            $pic = post('pic');
            $link = post('link');
            $title = post('title');
            
            if (! $gid) {
                alert_back('分组不能为空！');
            }
            
            if (! $pic) {
                alert_back('图片不能为空！');
            }
            
            // 构建数据
            $data = array(
                'acode' => session('acode'),
                'gid' => $gid,
                'pic' => $pic,
                'link' => $link,
                'title' => $title,
                'create_user' => session('username'),
                'update_user' => session('username')
            );
            
            // 执行添加
            if ($this->model->addSlide($data)) {
                $this->log('新增轮播图成功！');
                if (! ! $backurl = get('backurl')) {
                    success('新增成功！', $backurl);
                } else {
                    success('新增成功！', url('/admin/Slide/index'));
                }
            } else {
                $this->log('新增轮播图失败！');
                error('新增失败！', - 1);
            }
        } else {
            $this->assign('add', true);
            $this->display('content/slide.html');
        }
    }

    // 轮播图删除
    public function del()
    {
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        if ($this->model->delSlide($id)) {
            $this->log('删除轮播图' . $id . '成功！');
            success('删除成功！', - 1);
        } else {
            $this->log('删除轮播图' . $id . '失败！');
            error('删除失败！', - 1);
        }
    }

    // 轮播图修改
    public function mod()
    {
        if (! $id = get('id', 'int')) {
            error('传递的参数值错误！', - 1);
        }
        
        // 单独修改状态
        if (($field = get('field', 'var')) && ! is_null($value = get('value', 'var'))) {
            if ($this->model->modSlide($id, "$field='$value',update_user='" . session('username') . "'")) {
                location(- 1);
            } else {
                alert_back('修改失败！');
            }
        }
        
        // 修改操作
        if ($_POST) {
            
            // 获取数据
            $gid = post('gid', 'int');
            $pic = post('pic');
            $link = post('link');
            $title = post('title');
            
            if (! $gid) {
                alert_back('分组不能为空！');
            }
            
            if (! $pic) {
                alert_back('图片不能为空！');
            }
            
            // 构建数据
            $data = array(
                'gid' => $gid,
                'pic' => $pic,
                'link' => $link,
                'title' => $title,
                'update_user' => session('username')
            );
            
            // 执行添加
            if ($this->model->modSlide($id, $data)) {
                $this->log('修改轮播图' . $id . '成功！');
                if (! ! $backurl = get('backurl')) {
                    success('修改成功！', $backurl);
                } else {
                    success('修改成功！', url('/admin/Slide/index'));
                }
            } else {
                location(- 1);
            }
        } else {
            // 调取修改内容
            $this->assign('mod', true);
            if (! $result = $this->model->getSlide($id)) {
                error('编辑的内容已经不存在！', - 1);
            }
            $this->assign('slide', $result);
            $this->display('content/slide.html');
        }
    }
}