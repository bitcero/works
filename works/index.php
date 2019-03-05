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
require dirname(dirname(__DIR__)) . '/mainfile.php';
load_mod_locale('works');

if ($xoopsModuleConfig['permalinks'] <= 0) {
    // PHP Default URLs mode
    $p  = RMHttpRequest::request('p', 'string', 'home');
    $id = RMHttpRequest::request('id', 'string', '');

    switch ($p) {
        case 'category':
            require __DIR__ . '/category.php';
            break;
        case 'work':
            require __DIR__ . '/work.php';
            break;
        default:
            include $p . '.php';
            break;
    }

    if ('' != trim($p)) {
        exit();
    }
}

$request = str_replace(XOOPS_URL, '', RMUris::current_url());
$request = str_replace('/modules/works/', '', $request);

if ($xoopsModuleConfig['permalinks'] > 0 && '/' != $xoopsModuleConfig['htbase'] && '' != $request) {
    $request = str_replace(rtrim($xoopsModuleConfig['htbase'], '/') . '/', '', rtrim($request, '/') . '/');
}
$yesquery = false;

// Allow to plugins to manage requests to module
$request = RMEvents::get()->run_event('works.parse.request', $request);

if ('?' == mb_substr($request, 0, 1)) {
    $request  = mb_substr($request, 1);
    $yesquery = true;
}
if ('' == $request || 'index.php' == $request) {
    require __DIR__ . '/home.php';
    die();
}

$vars = [];
parse_str($request, $vars);

if (isset($vars['work'])) {
    $post = $vars['work'];
    require __DIR__ . '/work.php';
    die();
}
if (isset($vars['cat'])) {
    $category = $vars['cat'];
    require __DIR__ . '/category.php';
    die();
}

$vars = explode('/', rtrim($request, '/'));

foreach ($vars as $i => $v) {
    if ('page' == $v) {
        $page = $vars[$i + 1];
        unset($vars[$i], $vars[$i + 1]);
        break;
    }
}
/**
 * Si el primer valor es category entonces se realiza la búsqueda por
 * categoría
 */
if ('category' == $vars[0]) {
    array_shift($vars);
    $id = implode('/', $vars);
    require __DIR__ . '/category.php';
    die();
}

if ('recent' == $vars[0]) {
    $categotype = 1;
    require __DIR__ . '/recent.php';
    die();
}

if ('featured' == $vars[0]) {
    require __DIR__ . '/featured.php';
    die();
}

if ('page' == $vars[0]) {
    $page = $vars[1];
    require __DIR__ . '/home.php';
    exit();
}

/**
 * Work
 */
if (!empty($vars[0])) {
    $id = $vars[0];
    require __DIR__ . '/work.php';
    exit();
}

if ($yesquery) {
    require __DIR__ . '/home.php';
    exit();
}

RMFunctions::error_404(__('Document not found', 'docs'), 'docs');
exit();
