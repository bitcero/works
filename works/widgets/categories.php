<?php
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

global $xoopsDB;

$id = RMHttpRequest::request('id', 'integer', 0);
global $xoopsModuleConfig;
$mc = $xoopsModuleConfig;

$work = new Works_Work($id);

$categories = [];
Works_Functions::categories_tree($categories);

ob_start();
include RMTemplate::getInstance()->get_template('widgets/works-widget-categories.php', 'module', 'works');
$content = ob_get_clean();

$widget = [
    'title'   => __('Categories', 'works'),
    'content' => $content,
    'icon'    => 'fa fa-folder',
];

return $widget;
