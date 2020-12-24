<?php
// $Id: xoops_version.php 709 2011-08-09 00:43:18Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
 * Es necesario verificar si existe Common Utilities o si ha sido instalado
 * para evitar problemas en el sistema
 */
$amod = xoops_getActiveModules();
if (!in_array('rmcommon', $amod, true)) {
    $error = '<strong>WARNING:</strong> Professional Works requires %s to be installed!<br>Please install %s before trying to use Professional Works';
    $error = str_replace('%s', '<a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>', $error);
    xoops_error($error);
    $error = '%s is not installed! This might cause problems with functioning of Professional Works and entire system. To solve, install %s or uninstall Professional Works and then delete module folder.';
    $error = str_replace('%s', '<a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>', $error);
    trigger_error($error, E_USER_WARNING);
    echo '<br>';
}

if (!function_exists('__')) {
    function __($text, $d)
    {
        return $text;
    }
}
