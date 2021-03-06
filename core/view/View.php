<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @license This is not a freeware, use is subject to license terms
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2016年11月6日
 *  模板显示类 
 */
namespace core\view;

use core\basic\Config;

class View
{

    // 模板路径
    protected $tplPath;

    // 编译路径
    protected $tplcPath;

    // 缓存路径
    protected $cachePath;

    // 存储注入变量
    protected $vars = array();

    // 实例
    protected static $view;

    // 获取单一实例
    public static function getInstance()
    {
        if (! self::$view) {
            self::$view = new self();
        }
        return self::$view;
    }

    // 禁止通过new实例化类
    private function __construct()
    {
        $this->tplPath = APP_VIEW_PATH;
        $this->tplcPath = RUN_PATH . '/complile';
        $this->cachePath = RUN_PATH . '/cache';
        check_dir($this->tplcPath, true);
        check_dir($this->cachePath, true);
    }

    private function __clone()
    {
        die('不允许克隆对象！请使用getInstance获取实例');
    }

    // 变量注入
    public function assign($var, $value)
    {
        if (! empty($var)) {
            if (isset($this->vars[$var])) {
                error('模板变量$' . $var . '出现重复注入！');
            }
            $this->vars[$var] = $value;
            return true;
        } else {
            error('传递的设置模板变量有误');
        }
    }

    // 变量获取
    public function getVar($var)
    {
        if (! empty($var)) {
            if (isset($this->vars[$var])) {
                return $this->vars[$var];
            } else {
                return null;
            }
        } else {
            error('传递的获取模板变量有误');
        }
    }

    // 解析模板文件
    public function parser($file)
    {
        // 设置主题
        $theme = isset($this->vars['theme']) ? $this->vars['theme'] : 'default';
        
        if (! is_dir($this->tplPath .= '/' . $theme)) { // 检查主题是否存在
            if ($theme == 'default') { // 默认主题不存在且未默认的，自动初始化
                check_file($this->tplPath . '/index.html', true, '<h2>(- -)欢迎您使用本系统，请开始您的开发旅程吧!</h2>');
            } else {
                error('模板主题目录不存在！主题路径：' . $this->tplPath);
            }
        }
        
        // 定义当前应用主题目录
        define('APP_THEME_DIR', str_replace(DOC_PATH, '', APP_VIEW_PATH) . '/' . $theme);
        
        $tplFile = $this->tplPath . '/' . $file; // 模板文件
        file_exists($tplFile) ?: error('模板文件' . $file . '不存在！');
        $tplcFile = $this->tplcPath . '/' . md5($tplFile) . '.php'; // 编译文件
                                                                    
        // 当编译文件不存在，或者模板文件修改过，或者调试模式，则重新生成编译文件
        if (! file_exists($tplcFile) || filemtime($tplcFile) < filemtime($tplFile) || ! Config::get('tpl_parser_cache') || Config::get('debug')) {
            $content = file_get_contents($tplFile) ?: error('模板文件' . $file . '读取错误！'); // 读取模板
            $content = Parser::compile($this->tplPath, $content); // 解析模板
            file_put_contents($tplcFile, $content) ?: error('编译文件' . $tplcFile . '生成出错！请检查目录是否有可写权限！'); // 写入编译文件
        }
        
        // 获取编译后内容返回
        ob_start();
        include $tplcFile;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    // 缓存页面， 开启缓存开关时有效
    public function cache($content)
    {
        if (Config::get('tpl_html_cache')) {
            $cacheFile = $this->cachePath . '/' . md5($_SERVER["REQUEST_URI"] . session('acode')) . '.html'; // 缓存文件
            file_put_contents($cacheFile, $content) ?: error('缓存文件' . $cacheFile . '生成出错！请检查目录是否有可写权限！'); // 写入缓存文件
            return true;
        }
        return false;
    }
}
