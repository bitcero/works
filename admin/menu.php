<?php
/**
 * Professional Works
 *
 * Copyright © 2015 Eduardo Cortés http://www.redmexico.com.mx
 * -------------------------------------------------------------
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * -------------------------------------------------------------
 * @copyright    Eduardo Cortés (http://www.redmexico.com.mx)
 * @license      GNU GPL 2
 * @package      works
 * @author       Eduardo Cortés (AKA bitcero)    <i.bitcero@gmail.com>
 * @url          http://www.redmexico.com.mx
 * @url          http://www.eduardocortes.mx
 */

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
