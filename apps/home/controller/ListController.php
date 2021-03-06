<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2018年2月14日
 *  列表控制器
 */
namespace app\home\controller;

use app\home\model\ParserModel;
use core\basic\Controller;

class ListController extends Controller
{

    protected $parser;

    protected $model;

    public function __construct()
    {
        $this->parser = new ParserController();
        $this->model = new ParserModel();
    }

    // 内容列表
    public function index()
    {
        if (! ! $scode = get('scode', 'var')) {
            if (! ! $sort = $this->model->getSort($scode)) {
                if ($sort->listtpl) {
                    $content = parent::parser($sort->listtpl); // 框架标签解析
                    $content = $this->parser->parserPosition($content, $scode); // CMS当前位置标签解析
                    $content = $this->parser->parserSortLabel($content, $sort); // CMS分类信息标签解析
                    $content = $this->parser->parserListLabel($content, $scode); // CMS分类列表标签解析
                    $content = $this->parser->parserCommom($content); // CMS公共标签解析
                } else {
                    error('请到后台设置分类栏目列表页模板！');
                }
            } else {
                error('您访问的分类不存在，请核对后再试！');
            }
        } else {
            error('您访问的地址有误，必须传递栏目scode参数！');
        }
        $this->cache($content, true);
    }

    // 空拦截
    public function _empty()
    {
        error('您访问的地址有误，请核对后重试！');
    }
}