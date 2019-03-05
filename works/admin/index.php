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
define('RMCLOCATION', 'index');
require __DIR__ . '/header.php';

define('WORKS_LOCATION', 'dashboard');

// URL rewriting
$mc = RMSettings::module_settings('works');
if ($mc->permalinks) {
    $rule     = 'RewriteRule ^' . trim($mc->htbase, '/') . '/?(.*)$ modules/works/index.php [L]';
    $ht       = new RMHtaccess('works');
    $htResult = $ht->write($rule);
    if (true !== $htResult) {
        $errmsg = __('You have set the URL redirection in the server, but .htaccess file could not be written! Please verify that you have writing permissions. If not, please add next lines to your htaccess file:', 'works');
        $errmsg .= '<pre>' . $htResult . '</pre>';
        showMessage($errmsg, RMMSG_WARN);
    }
}

// Widgets
$widgets_right = [];
$widgets_left  = [];
$widgets_right = RMEvents::get()->run_event('works.dashboard.right.widgets', $widgets_right);
$widgets_left  = RMEvents::get()->run_event('works.dashboard.left.widgets', $widgets_left);

//Categorías
$sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_works_categories');
list($categories) = $db->fetchRow($db->query($sql));

//Tipos de Cliente
$sql = 'SELECT COUNT(*) FROM ' . $db->prefix('pw_types');
list($types) = $db->fetchRow($db->query($sql));

//Clientes
$sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_works_clients');
list($customers) = $db->fetchRow($db->query($sql));

//Trabajos
$sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_works_works');
list($works) = $db->fetchRow($db->query($sql));

// IMages
$sql = 'SELECT COUNT(*) FROM ' . $db->prefix('pw_images');
list($images) = $db->fetchRow($db->query($sql));

// Works not published
$sql           = 'SELECT * FROM ' . $db->prefix('mod_works_works') . ' WHERE public=0 ORDER BY id_work DESC LIMIT 0,5';
$result        = $db->query($sql);
$works_pending = [];
while (false !== ($row = $db->fetchArray($result))) {
    $work = new Works_Work();
    $work->assignVars($row);
    $works_pending[] = [
        'id'    => $work->id(),
        'title' => $work->title(),
        'desc'  => $work->descShort(),
        'date'  => formatTimestamp($work->created(), 'c'),
    ];
}

Works_Functions::go_scheduled();

$bc = RMBreadCrumb::get();
$bc->add_crumb(__('Dashboard', 'works'));

RMTemplate::get()->add_style('admin.css', 'works');
RMTemplate::get()->add_style('dashboard.css', 'works');
RMTemplate::getInstance()->add_body_class('dashboard');

$dashboardPanels = [];
$dashboardPanels = RMEvents::get()->trigger('works.dashboard.panels', $dashboardPanels);

xoops_cp_header();

include RMTemplate::get()->get_template('admin/works-dashboard.php', 'module', 'works');
xoops_cp_footer();
