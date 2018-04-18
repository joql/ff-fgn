<?php
return array(
	//'配置项'=>'配置值'
    //主题静态文件路径
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__.'/Application/'.MODULE_NAME.'/View/' . '/Public/static',
        '__IMG__' => __ROOT__.'/Public/icon/',
    ),

    //路由设置（短链接设置）
	'URL_ROUTER_ON' => true,
	'URL_ROUTE_RULES' => array(
		'/^so\/(\d+)$/' => 'Appipa/index?xxid=:1',
        '/^So\/(\d+)$/' => 'Appipa/index?xxid=:1',
        '/^duo\/(\d+)$/' => 'Appipa/duo?xxid=:1'
	),
);