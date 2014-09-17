<?php
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

$id = RMHttpRequest::request( 'id', 'integer', 0 );

$work = new Works_Work( $id );

$util = new RMUtilities();

if ( isset($work) && is_a( $work, 'Works_Work' ) ){

    if ($work->isNew())
        $params = '';
    else
        $params = $work->getVar('image','e');

} else {
    $params = '';
}

$content = '<form name="frmDefimage" id="frm-defimage" method="post">';
$content .= $util->image_manager('image', 'image', $params, array('accept' => 'thumbnail', 'multiple' => 'no'));
$content .= '</form>';

$widget = array(

    'title'     => __('Featured Image', 'works'),
    'content'   => $content,
    'icon'      => 'fa fa-eye'

);

return $widget;