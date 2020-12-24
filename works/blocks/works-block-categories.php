<?php
// $Id: pw_cats.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('works');

function works_block_categories_show($options)
{
    global $xoopsModule, $xoopsModuleConfig;

    $categories = [];
    Works_Functions::categories_tree($categories);

    $block['categories'] = $categories;

    return $block;
}
