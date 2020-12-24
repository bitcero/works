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
require dirname(dirname(dirname(__DIR__))) . '/include/cp_header.php';

define('PW_PATH', XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname());
define('PW_URL', XOOPS_URL . '/modules/works');
define('PW_PUBLIC_URL', $xoopsModuleConfig['permalinks'] ? XOOPS_URL . '/' . trim($xoopsModuleConfig['htbase'], '/') : XOOPS_URL . '/modules/works');

# Definimos el motor de plantillas si no existe
$mc   = &$xoopsModuleConfig;
$myts = MyTextSanitizer::getInstance();

$tpl = RMTemplate::get();
$db  = XoopsDatabaseFactory::getDatabaseConnection();

# Asignamos las variables básicas a SMARTY
$tpl->assign('pw_url', PW_URL);
$tpl->assign('pw_path', PW_PATH);

// Directorios
if (!file_exists(XOOPS_UPLOAD_PATH . '/works')) {
    if (!mkdir($concurrentDirectory = XOOPS_UPLOAD_PATH . '/works') && !is_dir($concurrentDirectory)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
    }
}
if (!file_exists(XOOPS_UPLOAD_PATH . '/works/ths')) {
    if (!mkdir($concurrentDirectory = XOOPS_UPLOAD_PATH . '/works/ths') && !is_dir($concurrentDirectory)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
    }
}

RMTemplate::getInstance()->add_script('admin-works.js', 'works', ['id' => 'works-js', 'footer' => 1]);
