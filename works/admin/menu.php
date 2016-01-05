<?php
// $Id: menu.php 612 2011-02-14 21:29:27Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

load_mod_locale('works');

$i = 0;
$adminmenu[$i]['title'] = __('Dashboard', 'works');
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['icon'] = "svg-rmcommon-dashboard text-midnight";
$adminmenu[$i]['location'] = "dashboard";

$i++;
$adminmenu[$i]['title'] = __('Categories', 'works');
$adminmenu[$i]['link'] = "admin/categories.php";
$adminmenu[$i]['icon'] = "svg-rmcommon-folder text-orange";
$adminmenu[$i]['location'] = "categories";
$adminmenu[$i]['options'] = array(
	array('title'=>__('List all', 'works'),'link'=>'admin/categories.php', 'selected'=>'categories', 'icon' => 'fa fa-list'),
	array('title'=>__('Add Category', 'works'),'link'=>'admin/categories.php?action=new', 'selected'=>'newcategory', 'icon' => 'fa fa-plus')
);

$i++;
$adminmenu[$i]['title'] = __('Works','works');
$adminmenu[$i]['link'] = "admin/works.php";
$adminmenu[$i]['icon'] = "svg-rmcommon-briefcase text-brown";
$adminmenu[$i]['location'] = "works";
$adminmenu[$i]['options'] = array(
    array('title'=>__('List', 'works'),'link'=>'admin/works.php', 'selected'=>'works', 'icon' => 'fa fa-list'),
    array('title'=>__('Add Work', 'works'),'link'=>'admin/works.php?action=new', 'selected'=>'newwork', 'icon' => 'fa fa-plus')
);
