<?php
return array(
    
    // 调试模式
    'debug' => true,
    
    // 授权码
    'sn' => '382B4E6010',
    
    // 设置URL模式,1、基本模式,2、伪静态模式
    'url_type' => 1,
    
    // 模板内容输出缓存开关
    'tpl_html_cache' => 0,
    
    // 模板内容缓存有效时间（秒）
    'tpl_html_cache_time' => 900,
    
    // 访问页面规则，如禁用浏览器、操作系统类型
    'access_rule' => array(
        'deny_bs' => 'IE6,IE7,IE8'
    ),
    
    // 上传配置
    'upload' => array(
        'format' => 'jpg,png,gif,xls,xlsx,doc,docx,ppt,pptx,rar,zip,pdf,txt,mp4,avi,flv,rmvb,mp3',
        'max_width' => '1920',
        'max_height' => '1920'
    ),
    
    // 模块模板路径定义
    'tpl_dir' => array(
        'home' => '/template'
    )

);
 