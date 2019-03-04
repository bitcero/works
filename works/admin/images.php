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
define('RMCLOCATION', 'works');
require __DIR__ . '/header.php';

function showImages()
{
    global $xoopsModule, $db, $xoopsSecurity;

    $work = rmc_server_var($_REQUEST, 'work', 0);

    //Verificamos que el trabajo sea válido
    if ($work <= 0) {
        redirectMsg('./works.php', __('Provided work ID is not valid!', 'works'), 1);
        die();
    }

    //Verificamos que el trabajo exista
    $work = new Works_Work($work);
    if ($work->isNew()) {
        redirectMsg('./works.php', __('Specified work does not exists!', 'admin_work'), 1);
        die();
    }

    //Barra de Navegación
    $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('pw_images') . " WHERE work='" . $work->id() . "'";

    list($num) = $db->fetchRow($db->query($sql));

    $page = rmc_server_var($_REQUEST, 'page', 1);
    $page = $page <= 0 ? 1 : $page;
    $limit = 10;

    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num <= 0 ? 0 : ($page - 1) * $limit;

    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('images.php?page={PAGE_NUM}&amp;work=' . $work->id());

    $sql = 'SELECT * FROM ' . $db->prefix('pw_images') . " WHERE work='" . $work->id() . "'";
    $sql .= " LIMIT $start,$limit";
    $result = $db->query($sql);
    $images = [];
    while (false !== ($row = $db->fetchArray($result))) {
        $img = new PWImage();
        $img->assignVars($row);

        $images[] = [
            'id' => $img->id(),
            'title' => $img->title(),
            'image' => $img->image(),
            'work' => $img->work(),
            'desc' => $img->desc(),
        ];
    }

    $images = RMEvents::get()->run_event('works.list.images', $images, $work);
    $form_fields = '';
    $form_fields = RMEvents::get()->run_event('works.images.form.fields', $form_fields, $work);

    Works_Functions::toolbar();
    xoops_cp_location('<a href="./">' . $xoopsModule->name() . '</a> &raquo; ' . __('Work Images', 'works'));
    RMTemplate::get()->assign('xoops_pagetitle', $work->title() . ' &raquo; Work Images', 'admin_mywords');
    RMTemplate::get()->add_style('admin.css', 'works');
    RMTemplate::get()->add_script(RMCURL . '/include/js/jquery.checkboxes.js');
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar pw_message='" . __('Do you really want to delete selected images?', 'works') . "';\n
        var pw_select_message = '" . __('You must select an image before to execute this action!', 'works') . "';</script>");
    xoops_cp_header();

    include RMTemplate::get()->get_template('admin/pw_images.php', 'module', 'works');

    xoops_cp_footer();
}

/**
 * @desc Formulario de creación/edición de Imágenes
 * @param mixed $edit
 **/
function formImages($edit = 0)
{
    global $xoopsModule, $xoopsModuleConfig;

    $id = rmc_server_var($_REQUEST, 'id', 0);
    $work = rmc_server_var($_REQUEST, 'work', 0);
    $page = rmc_server_var($_REQUEST, 'page', 0);

    $ruta = "&page=$page";

    //Verificamos que el trabajo sea válido
    if ($work <= 0) {
        redirectMsg('./works.php', __('You must specify a work ID!', 'works'), 1);
        die();
    }

    //Verificamos que el trabajo exista
    $work = new Works_Work($work);
    if ($work->isNew()) {
        redirectMsg('./works.php', __('Specified work does not exists!', 'works'), 1);
        die();
    }

    if ($edit) {
        //Verificamos que la imagen sea válida
        if ($id <= 0) {
            redirectMsg('./images.php?work=' . $work->id() . $ruta, __('You must specify an image ID!', 'works'), 1);
            die();
        }

        //Verificamos que la imagen exista
        $img = new PWImage($id);
        if ($img->isNew()) {
            redirectMsg('./images.php?work=' . $work->id() . $ruta, __('Specified image does not exists!', 'works'), 1);
            die();
        }
    }

    Works_Functions::toolbar();
    RMTemplate::get()->assign('xoops_pagetitle', $work->title() . ' &raquo; ' . __('Work Images', 'works'));
    xoops_cp_location('<a href="./">' . $xoopsModule->name() . "</a> &raquo; <a href='./images.php?work=" . $work->id() . "'>" . __('Work Images', 'works') . '</a> &raquo;' . ($edit ? __('Edit Image', 'works') : __('Add Image', 'works')));
    xoops_cp_header();

    $form = new RMForm($edit ? __('Edit Image', 'works') : __('Add Image', 'works'), 'frmImg', 'images.php');
    $form->setExtra("enctype='multipart/form-data'");

    $form->addElement(new RMFormText(__('Title', 'works'), 'title', 50, 100, $edit ? $img->title() : ''), true);
    $form->addElement(new RMFormFile(__('Image file', 'works'), 'image', 45, $xoopsModuleConfig['size_image'] * 1024), $edit ? false : true);
    if ($edit) {
        $form->addElement(new RMFormLabel(__('Current image file', 'works'), "<img src='" . XOOPS_UPLOAD_URL . '/works/ths/' . $img->image() . "'>"));
    }

    $form->addElement(new RMFormTextArea(__('Description', 'works'), 'desc', 4, 50, $edit ? $img->desc() : ''));

    $form->addElement(new RMFormHidden('op', $edit ? 'saveedit' : 'save'));
    $form->addElement(new RMFormHidden('id', $id));
    $form->addElement(new RMFormHidden('work', $work->id()));
    $form->addElement(new RMFormHidden('page', $page));
    $form->addElement(new RMFormHidden('limit', $limit));

    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', _SUBMIT, 'submit');
    $ele->addButton('cancel', _CANCEL, 'button', 'onclick="window.location=\'images.php?work=' . $work->id() . $ruta . '\';"');
    $form->addElement($ele);

    $form->display();

    xoops_cp_footer();
}

/**
 * @desc Almacena las imágenes en la base de datos
 * @param mixed $edit
 **/
function saveImages($edit = 0)
{
    global $xoopsModuleConfig, $xoopsSecurity;

    foreach ($_POST as $k => $v) {
        $$k = $v;
    }

    $ruta = "&page=$page";

    //Verificamos que el trabajo sea válido
    if ($work <= 0) {
        redirectMsg('./works.php', __('You must specify a work ID!', 'works'), 1);
        die();
    }

    //Verificamos que el trabajo exista
    $work = new Works_Work($work);
    if ($work->isNew()) {
        redirectMsg('./works.php', __('Specified work does not exists!', 'works'), 1);
        die();
    }

    if (!$xoopsSecurity->check()) {
        redirectMsg('./images.php?work=' . $work->id() . $ruta, __('Session token expired!', 'works'), 1);
        die();
    }

    if ($edit) {
        //Verificamos que la imagen sea válida
        if ($id <= 0) {
            redirectMsg('./images.php?work=' . $work->id() . $ruta, __('You must specify an image ID!', 'works'), 1);
            die();
        }

        //Verificamos que la imagen exista
        $img = new PWImage($id);
        if ($img->isNew()) {
            redirectMsg('./images.php?work=' . $work->id() . $ruta, __('Specified image does not exists!', 'works'), 1);
            die();
        }
    } else {
        $img = new PWImage();
    }

    $img->setTitle($title);
    $img->setDesc(mb_substr($desc, 0, 100));
    $img->setWork($work->id());

    //Imagen
    require_once RMCPATH . '/class/uploader.php';
    $folder = XOOPS_UPLOAD_PATH . '/works';
    $folderths = XOOPS_UPLOAD_PATH . '/works/ths';
    if ($edit) {
        $image = $img->image();
        $filename = $img->image();
    } else {
        $filename = '';
    }

    //Obtenemos el tamaño de la imagen
    $thSize = $xoopsModuleConfig['image_ths'];
    $imgSize = $xoopsModuleConfig['image'];

    $up = new RMFileUploader($folder, $xoopsModuleConfig['size_image'] * 1024, ['jpg', 'png', 'gif']);

    if ($up->fetchMedia('image')) {
        if (!$up->upload()) {
            redirectMsg('./images.php?op=' . ($edit ? 'edit' : 'new') . '&work=' . $work->id() . $ruta, $up->getErrors(), 1);
            die();
        }

        if ($edit && '' != $img->image()) {
            @unlink(XOOPS_UPLOAD_PATH . '/works/' . $img->image());
            @unlink(XOOPS_UPLOAD_PATH . '/works/ths/' . $img->image());
        }

        $filename = $up->getSavedFileName();
        $fullpath = $up->getSavedDestination();
        // Redimensionamos la imagen
        $redim = new RMImageResizer($fullpath, $fullpath);
        switch ($xoopsModuleConfig['redim_image']) {
            case 0:
                //Recortar miniatura
                $redim->resizeWidth($imgSize[0]);
                $redim->setTargetFile($folderths . "/$filename");
                $redim->resizeAndCrop($thSize[0], $thSize[1]);
                break;
            case 1:
                //Recortar imagen grande
                $redim->resizeWidthOrHeight($imgSize[0], $imgSize[1]);
                $redim->setTargetFile($folderths . "/$filename");
                $redim->resizeWidth($thSize[0]);
                break;
            case 2:
                //Recortar ambas
                $redim->resizeWidthOrHeight($imgSize[0], $imgSize[1]);
                $redim->setTargetFile($folderths . "/$filename");
                $redim->resizeAndCrop($thSize[0], $thSize[1]);
                break;
            case 3:
                //Redimensionar
                $redim->resizeWidth($imgSize[0]);
                $redim->setTargetFile($folderths . "/$filename");
                $redim->resizeWidth($thSize[0]);
                break;
        }
    }

    $img->setImage($filename);

    RMEvents::get()->run_event('works.save.image', $img);

    if (!$img->save()) {
        redirectMsg('./images.php?work=' . $work->id() . $ruta, __('Errors ocurred while trying to save the image', 'works') . '<br>' . $img->errors(), 1);
        die();
    }
    redirectMsg('./images.php?work=' . $work->id() . $ruta, __('Database updated successfully!', 'works'), 0);
    die();
}

/**
 * @desc Elimina de la base de datos las imagenes especificadas
 **/
function deleteImages()
{
    global $xoopsSecurity, $xoopsModule;

    $ids = rmc_server_var($_REQUEST, 'ids', 0);
    $work = rmc_server_var($_REQUEST, 'work', 0);
    $page = rmc_server_var($_REQUEST, 'page', 0);

    $ruta = "&page=$page";

    //Verificamos que nos hayan proporcionado una imagen para eliminar
    if (!is_array($ids)) {
        redirectMsg('./images.php?work=' . $work . $ruta, __('You must select an image to delete!', 'works'), 1);
        die();
    }

    if (!$xoopsSecurity->check()) {
        redirectMsg('./images.php?work=' . $work . $ruta, __('Session token expired!', 'works'), 1);
        die();
    }

    $errors = '';
    foreach ($ids as $k) {
        //Verificamos si la imagen es válida
        if ($k <= 0) {
            $errors .= sprintf(__('Image ID "%s" is not valid!', 'works'), $k);
            continue;
        }

        //Verificamos si la imagen existe
        $img = new PWImage($k);
        if ($img->isNew()) {
            $errors .= sprintf(__('Image with ID "%s" does not exists!', 'works'), $k);
            continue;
        }

        if (!$img->delete()) {
            $errors .= sprintf(__('Image "%s" could not be deleted!', 'works'), $img->title());
        }
    }

    if ('' != $errors) {
        redirectMsg('./images.php?work=' . $work . $ruta, __('Errors ocurred while trying to delete images', 'works') . '<br>' . $errors, 1);
        die();
    }
    redirectMsg('./images.php?work=' . $work . $ruta, __('Images deleted successfully!', 'works'), 0);
    die();
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op) {
    case 'new':
        formImages();
        break;
    case 'edit':
        formImages(1);
        break;
    case 'save':
        saveImages();
        break;
    case 'saveedit':
        saveImages(1);
        break;
    case 'delete':
        deleteImages();
        break;
    default:
        showImages();
}
