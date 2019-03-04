<?php
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$id = RMHttpRequest::request('id', 'integer', 0);

$work = new Works_Work($id);

ob_start();
include RMTemplate::get()->get_template('widgets/works-widget-visibility.php', 'module', 'works');
$content = ob_get_clean();

$widget = [
    'title' => __('Visibility', 'works'),
    'content' => $content,
    'icon' => 'fa fa-eye',
];

return $widget;
