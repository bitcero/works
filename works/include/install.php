<?php
// $Id: xoops_version.php 709 2011-08-09 00:43:18Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Module for personals and professionals portfolios
// Author: Eduardo Cortés <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

function xoops_module_pre_install_works(&$mod)
{
    xoops_setActiveModules();

    $mods = xoops_getActiveModules();

    if (!in_array('rmcommon', $mods, true)) {
        $mod->setErrors('Professional Works could not be instaled if <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a> has not be installed previously!<br>Please install <a href="http://www.redmexico.com.mx/w/common-utilities/" target="_blank">Common Utilities</a>.');

        return false;
    }

    return true;
}
