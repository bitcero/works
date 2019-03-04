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

function works_json_reponse($token, $error, $data)
{
    global $xoopsSecurity;

    if ($token) {
        $data['token'] = $xoopsSecurity->createToken(0, 'CUTOKEN');
    }

    if ($error) {
        $data['error'] = 1;
    }

    echo json_encode($data);
    die();
}

/**
 * @desc Visualiza todos los trabajos existentes
 **/
function showWorks()
{
    global $xoopsModule, $xoopsSecurity, $cuIcons;

    define('RMCSUBLOCATION', 'workslist');

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    $page = RMHttpRequest::request('page', 'integer', 1);
    $page = $page <= 0 ? 1 : $page;
    $limit = 15;
    $show = RMHttpRequest::request('show', 'string', '');

    //Barra de Navegación
    $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_works_works');
    if ('' != $show) {
        $sql .= " WHERE status='$show'";
    }

    list($num) = $db->fetchRow($db->query($sql));

    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num <= 0 ? 0 : ($page - 1) * $limit;

    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('works.php?page={PAGE_NUM}');

    $sql = str_replace('COUNT(*)', '*', $sql);
    $sql .= " ORDER BY id_work DESC LIMIT $start, $limit";
    $result = $db->query($sql);
    $works = []; //Container
    $tf = new RMTimeFormatter(0, '%T% %d%, %Y% at %h%:%i%');

    while (false !== ($row = $db->fetchArray($result))) {
        $work = new Works_Work();
        $work->assignVars($row);

        $works[] = [
            'id' => $work->id(),
            'title' => $work->title,
            'featured' => $work->featured,
            'status' => $work->status,
            'url' => $work->permalink(),
            'categories' => $work->categories('name'),
            'created' => $tf->format($work->created),
            'modified' => $tf->format($work->modified),
            'customer' => $work->customer,
        ];
    }

    RMTemplate::getInstance()->add_style('admin.css', 'works');
    RMTemplate::getInstance()->add_script('jquery.checkboxes.js', 'rmcommon', [ 'directory' => 'include' ]);
    RMTemplate::getInstance()->add_script('admin-works.js', 'works', ['id' => 'works-js', 'footer' => 1]);

    $bc = RMBreadCrumb::get();
    $bc->add_crumb(__('Existing works', 'works'));

    xoops_cp_header();

    include RMTemplate::getInstance()->get_template('admin/works-works.php', 'module', 'works');

    xoops_cp_footer();
}

/**
 * @desc Formulario de creacion/edición de trabajos
 * @param mixed $edit
 **/
function formWorks($edit = 0)
{
    global $xoopsSecurity, $cuIcons, $common;

    global $xoopsModule, $xoopsModuleConfig;

    define('RMCSUBLOCATION', 'new-work');

    $page = RMHttpRequest::request('page', 'integer', 1);
    $query = "page=$page";

    $bc = RMBreadCrumb::get();
    $bc->add_crumb(__('Works management', 'works'), 'works.php');
    $bc->add_crumb($edit ? __('Editing work', 'works') : __('Adding work', 'works'));

    $id = RMHttpRequest::request('id', 'integer', 0);

    if ($edit) {
        //Verificamos que el trabajo sea válido
        if ($id <= 0) {
            RMUris::redirect_with_message(
                __('Provided Work ID is not valid!', 'works'),
                'works.php?' . $query,
                RMMSG_ERROR
            );
        }

        //Verificamos que el trabajo exista
        $work = new Works_Work($id);
        if ($work->isNew()) {
            RMUris::redirect_with_message(
                __('Specified work does not exists!', 'works'),
                'works.php?' . $query,
                RMMSG_ERROR
            );
        }
    } else {
        $work = new Works_Work();
    }

    RMTemplate::getInstance()->add_script('works-form.min.js', 'works', ['id' => 'works-js', 'footer' => 1]);
    RMTemplate::getInstance()->add_style('works-form.min.css', 'works', ['id' => 'works-css']);
    RMTemplate::getInstance()->add_script('jquery.datetimepicker.js', 'works');
    RMTemplate::getInstance()->add_style('jquery.datetimepicker.css', 'works');

    ob_start();
    load_mod_locale('works');
    include PW_PATH . '/include/js-lang.js';
    $lang = ob_get_clean();

    RMTemplate::getInstance()->add_inline_script($lang);

    RMTemplate::getInstance()->header();

    $form = new RMForm('', '', '');
    $editor = new RMFormEditor(__('Description', 'works'), 'description', '100%', '300px', $edit ? $work->getVar('description', 'e') : '');

    /**
     * Get additional fields for form.
     * This event allow other modules, plugins or themes to integrate new fields in works creation form.
     * The third component must return an array with next structure:
     * <pre>
     * $fields = array(
     *     'after-box-id' => array('HTML content for field')
     * );
     * </pre>
     *
     * Example:
     *
     * <pre>
     * $fields['editor'][] = '<div class="cu-box">...</div>';
     * </pre>
     *
     * Where <em>after-box-id</em> is the identificator of standar work field in form. Possible values are:
     * title, permalink, editor, images, customer, seo, meta
     */
    $additional_fields = [
        'title' => [],
        'permalink' => [],
        'editor' => [],
        'images' => [],
        'customer' => [],
        'seo' => [],
        'meta' => [],
    ];
    $additional_fields = RMEvents::get()->run_event('works.form.fields', $additional_fields, $work);

    include RMTemplate::getInstance()->get_template('admin/works-add-form.php', 'module', 'works');

    xoops_cp_footer();
}

/**
 * @desc Almacena la información del trabajo en la base de datos
 * @param mixed $edit
 **/
function saveWorks($edit = 0)
{
    global $xoopsSecurity, $xoopsModuleConfig, $xoopsLogger;

    $xoopsLogger->renderingEnabled = false;
    $xoopsLogger->activated = false;

    $id = RMHttpRequest::post('id', 'integer', 0);
    $featured = RMHttpRequest::post('featured', 'integer', 0);

    // General section
    $title = RMHttpRequest::post('title', 'string', '');
    $title_id = RMHttpRequest::post('titleid', 'string', '');
    $description = RMHttpRequest::post('description', 'string', '');
    $seo_title = RMHttpRequest::post('seo_title', 'string', '');
    $seo_description = RMHttpRequest::post('seo_description', 'string', '');
    $seo_keywords = RMHttpRequest::post('seo_keywords', 'string', '');

    // Customer section
    $customer_name = RMHttpRequest::post('customer_name', 'string', '');
    $web = RMHttpRequest::post('web', 'string', '');
    $url = formatURL(RMHttpRequest::post('url', 'string', ''));
    $comment = RMHttpRequest::post('customer_comment', 'string', '');

    // Images
    $images = RMHttpRequest::post('images', 'array', []);
    $image = RMHttpRequest::post('image', 'string', '');

    // Custom data
    $meta['names'] = RMHttpRequest::post('meta_name', 'array', []);
    $meta['values'] = RMHttpRequest::post('meta_value', 'array', []);

    // Status
    $status = RMHttpRequest::post('status', 'string', 'public');
    $schedule = RMHttpRequest::post('schedule', 'string', '');
    $image = RMHttpRequest::post('image', 'string', '');
    $categories = RMHttpRequest::post('cats', 'array', []);

    /*if ( !$xoopsSecurity->check() )
        works_json_reponse( 0, 1, array('message' => __('Session token expired!','works') ) );*/

    if ($edit) {
        //Verificamos que el trabajo sea válido
        if ($id <= 0) {
            works_json_reponse(1, 1, [
                'message' => __('Work ID not provided', 'works'),
            ]);
        }

        //Verificamos que el trabajo exista
        $work = new Works_Work($id);
        if ($work->isNew()) {
            works_json_reponse(1, 1, [
                'message' => __('Specified work does not exists!', 'works'),
            ]);
        }
    } else {
        $work = new Works_Work();
    }

    $db = XoopsDatabaseFactory::getDatabaseConnection();

    // Create the title ID
    $title_id = '' == $title_id ? TextCleaner::getInstance()->sweetstring($title) : TextCleaner::getInstance()->sweetstring($title_id);

    // Check if work exists already
    if ($edit) {
        $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_works_works') . " WHERE (title='$title' || titleid='$title_id') and id_work != $id";
    } else {
        $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_works_works') . " WHERE title='$title' || titleid='$title_id'";
    }

    list($num) = $db->fetchRow($db->query($sql));
    if ($num > 0) {
        works_json_reponse(1, 1, [
            __('Another work with same name already exists!', 'works'),
        ]);
    }

    $work->setVar('title', $title);
    $work->setVar('titleid', $title_id);
    $work->setVar('description', $description);
    $work->setVar('customer', $customer_name);
    $work->setVar('comment', $comment);
    $work->setVar('web', $web);
    $work->setVar('url', $url);
    $work->setVar('featured', $featured);
    $work->setVar('image', $image);
    $work->setVar('status', $status);

    // Set the groups when status is private
    if ('private' == $status) {
        $groups = RMHttpRequest::post('groups', 'array', []);
        if (empty($groups)) {
            works_json_reponse(1, 1, [
                'message' => __('You must select at least one group to authorize', 'works'),
            ]);
        }
        $work->setVar('groups', $groups);
    }

    // Set the schedule when status is scheduled
    if ('scheduled' == $status) {
        if ('' == $schedule) {
            works_json_reponse(1, 1, [
                'message' => __('You must specify a scheduled date for this work!', 'works'),
            ]);
        }

        $date_validator = DateTime::createFromFormat('Y-m-d H:i', $schedule);
        if (!$date_validator || $date_validator->format('Y-m-d H:i') != $schedule) {
            works_json_reponse(1, 1, [
                'message' => __('The specified scheduled date is not valid!', 'works'),
            ]);
        }

        $work->setVar('schedule', $schedule);
    } else {
        $work->setVar('schedule', date('Y-m-d H:i:s'));
    }

    if ($work->isNew()) {
        $work->setVar('created', date('Y-m-d H:i:s'));
    }
    $work->setVar('modified', date('Y-m-d H:i:s'));
    $work->setVar('seo_title', $seo_title);
    $work->setVar('seo_description', $seo_description);
    $work->setVar('seo_keywords', $seo_keywords);

    // Set multiple images
    $work->set_images($images);

    // Set custom data
    $work->set_meta($meta['names'], $meta['values']);

    if (!$work->save()) {
        works_json_reponse(1, 1, [
            'message' => __('Errors occurred while trying to update database!', 'works') . '<br>' . $work->errors(),
        ]);
    }

    // Add categories relations
    $db->queryF('DELETE FROM ' . $db->prefix('mod_works_categories_rel') . ' WHERE work = ' . $work->id());
    $sql = 'INSERT INTO ' . $db->prefix('mod_works_categories_rel') . ' (category, work) VALUES ';
    foreach ($categories as $id_cat) {
        $sql .= "($id_cat," . $work->id() . '),';
    }
    $sql = rtrim($sql, ',');

    $data = [
        'id' => $work->id(),
        'title' => $title,
        'title_id' => $title_id,
        'url' => $work->permalink(),
    ];

    if (!$db->queryF($sql)) {
        works_json_reponse(1, 1, [
            'message' => __('This work has been saved, however the categories relations could not be saved!', 'works') . '<br>' . $db->error(),
            'work' => $data,
        ]);
    }

    /**
     * Notify to other components
     */
    RMEvents::get()->trigger('works.saved.work', $work, $data);

    works_json_reponse(1, 0, [
        'message' => __('Item saved successfully!', 'works'),
        'data' => $data,
    ]);
}

/**
 * @desc Elimina de la base de datos la información del trabajo
 **/
function deleteWorks()
{
    global $xoopsSecurity, $xoopsModule;

    $ids = rmc_server_var($_POST, 'ids', 0);
    $page = rmc_server_var($_POST, 'page', 1);
    $show = rmc_server_var($_POST, 'show', 1);

    $ruta = "pag=$page&show=$show";

    //Verificamos que nos hayan proporcionado un trabajo para eliminar
    if (!is_array($ids)) {
        redirectMsg('./works.php?' . $ruta, __('You must select a work at least!', 'works'), 1);
        die();
    }

    if (!$xoopsSecurity->check()) {
        redirectMsg('./works.php?' . $ruta, __('Session token expired!', 'works'), 1);
        die();
    }

    $errors = '';
    foreach ($ids as $k) {
        //Verificamos si el trabajo es válido
        if ($k <= 0) {
            $errors .= sprintf(__('Work ID "%s" is not valid!', 'works'), $k);
            continue;
        }

        //Verificamos si el trabajo existe
        $work = new Works_Work($k);
        if ($work->isNew()) {
            $errors .= sprintf(__('Work with ID "%s" does not exists!', 'works'), $k);
            continue;
        }

        if (!$work->delete()) {
            $errors .= sprintf(__('Work "%s" could not be deleted!', 'works'), $work->title());
        }
    }

    if ('' != $errors) {
        redirectMsg('./works.php?' . $ruta, __('Errors ocurred while trying to delete works', 'works') . '<br>' . $errors, 1);
        die();
    }
    redirectMsg('./works.php?' . $ruta, __('Works deleted successfully!', 'works'), 0);
    die();
}

/**
 * @desc Publica o no los trabajos
 * @param mixed $pub
 **/
function publicWorks($pub = 0)
{
    global $xoopsSecurity;

    $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
    $page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $show = rmc_server_var($_POST, 'show', 1);

    $ruta = "page=$page&show=$show";

    //Verificamos que nos hayan proporcionado un trabajo para publicar
    if (!is_array($ids)) {
        redirectMsg('./works.php?' . $ruta, __('You must specify a work ID', 'works'), 1);
        die();
    }

    if (!$xoopsSecurity->check()) {
        redirectMsg('./works.php?' . $ruta, __('Session token expired!', 'works'), 1);
        die();
    }
    $errors = '';
    foreach ($ids as $k) {
        //Verificamos si el trabajo es válido
        if ($k <= 0) {
            $errors .= sprintf(__('Work ID "%s" is not valid!', 'works'), $k);
            continue;
        }

        //Verificamos si el trabajo existe
        $work = new Works_Work($k);
        if ($work->isNew()) {
            $errors .= sprintf(__('Work with ID "%s" does not exists!', 'works'), $k);
            continue;
        }

        $work->setPublic($pub);

        if (!$work->save()) {
            $errors .= sprintf(__('Work "%s" could not be saved!', 'works'), $k);
        }
    }

    if ('' != $errors) {
        redirectMsg('./works.php?' . $ruta, __('Errors ocurred while trying to update works') . '<br>' . $errors, 1);
        die();
    }
    redirectMsg('./works.php?' . $ruta, __('Works updated successfully!', 'works'), 0);
    die();
}

/**
 * @desc Destaca o no los trabajos
 * @param mixed $mark
 **/
function markWorks($mark = 0)
{
    global $xoopsSecurity;

    $ids = isset($_REQUEST['ids']) ? $_REQUEST['ids'] : 0;
    $page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $show = rmc_server_var($_POST, 'show', 1);

    $ruta = "page=$page&show=$show";

    //Verificamos que nos hayan proporcionado un trabajo para destacar
    if (!is_array($ids)) {
        redirectMsg('./works.php?' . $ruta, __('You must specify a work ID', 'works'), 1);
        die();
    }

    if (!$xoopsSecurity->check()) {
        redirectMsg('./works.php?' . $ruta, __('Session token expired!', 'works'), 1);
        die();
    }
    $errors = '';
    foreach ($ids as $k) {
        //Verificamos si el trabajo es válido
        if ($k <= 0) {
            $errors .= sprintf(__('Work ID "%s" is not valid!', 'works'), $k);
            continue;
        }

        //Verificamos si el trabajo existe
        $work = new Works_Work($k);
        if ($work->isNew()) {
            $errors .= sprintf(__('Work with ID "%s" does not exists!', 'works'), $k);
            continue;
        }

        $work->setMark($mark);

        if (!$work->save()) {
            $errors .= sprintf(__('Work "%s" could not be saved!', 'works'), $k);
        }
    }

    if ('' != $errors) {
        redirectMsg('./works.php?' . $ruta, __('Errors ocurred while trying to update works') . '<br>' . $errors, 1);
        die();
    }
    redirectMsg('./works.php?' . $ruta, __('Works updated successfully!', 'works'), 0);
    die();
}

function works_save_meta()
{
    global $xoopsSecurity;

    $id = rmc_server_var($_POST, 'id', 0);

    if ($id <= 0) {
        redirectMsg('works.php', __('You must provide a work ID!', 'works'), 1);
        die();
    }

    $work = new Works_Work($id);
    if ($work->isNew()) {
        redirectMsg('works.php', __('Specified work does not exists!', 'works'), 1);
        die();
    }

    if (!$xoopsSecurity->check()) {
        redirectMsg('works.php?id=' . $id . '&op=meta', __('Session token expired!', 'works'), 1);
        die();
    }

    $name = rmc_server_var($_POST, 'name', '');
    $value = rmc_server_var($_POST, 'value', '');

    if ('' == $name || '' == $value) {
        redirectMsg('works.php?id=' . $id . '&op=meta', __('Please, fill all data!', 'works'), 1);
        die();
    }

    $name = TextCleaner::sweetstring($name);

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('pw_meta') . " WHERE name='$name' AND work='$id'";
    list($num) = $db->fetchRow($db->query($sql));

    $value = TextCleaner::addslashes($value);

    if ($num > 0) {
        $sql = 'UPDATE ' . $db->prefix('pw_meta') . " SET value='$value' WHERE name='$name' AND work='$id'";
    } else {
        $sql = 'INSERT INTO ' . $db->prefix('pw_meta') . " (`value`,`name`,`work`) VALUES ('$value','$name','$id')";
    }

    if ($db->queryF($sql)) {
        redirectMsg('works.php?id=' . $id . '&op=meta', __('Custom field added successfully!', 'works'), 0);
    } else {
        redirectMsg('works.php?id=' . $id . '&op=meta', __('Custom field could not be added. Please try again!', 'works') . '<br>' . $db->error(), 1);
    }
}

function works_delete_meta()
{
    global $xoopsSecurity;

    $id = rmc_server_var($_POST, 'id', 0);

    if ($id <= 0) {
        redirectMsg('works.php', __('You must provide a work ID!', 'works'), 1);
        die();
    }

    $work = new Works_Work($id);
    if ($work->isNew()) {
        redirectMsg('works.php', __('Specified work does not exists!', 'works'), 1);
        die();
    }

    if (!$xoopsSecurity->check()) {
        redirectMsg('works.php?id=' . $id . '&op=meta', __('Session token expired!', 'works'), 1);
        die();
    }

    $ids = rmc_server_var($_POST, 'ids', []);
    if (!is_array($ids) || empty($ids)) {
        redirectMsg('works.php', __('Select some fields to delete!', 'works'), 1);
        die();
    }

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'DELETE FROM ' . $db->prefix('pw_meta') . ' WHERE id_meta IN(' . implode(',', $ids) . ')';

    if ($db->queryF($sql)) {
        redirectMsg('works.php?id=' . $id . '&op=meta', __('Custom fields deleted successfully!', 'works'), 0);
    } else {
        redirectMsg('works.php?id=' . $id . '&op=meta', __('Custom fields could not be deleted!', 'works') . '<br>' . $db->error(), 1);
    }
}

/**
 * Add a new video and response via ajax
 * @param mixed $edit
 */
function works_add_video($edit = 0)
{
    global $common;

    $common->ajax()->prepare();
    $common->checkToken();

    $id = $common->httpRequest()->post('id', 'integer', 0);
    $url = $common->httpRequest()->post('url', 'string', '');
    $title = $common->httpRequest()->post('title', 'string', '');
    $description = $common->httpRequest()->post('description', 'string', '');
    $image = $common->httpRequest()->post('image', 'string', '');
    $type = $common->httpRequest()->post('type', 'string', '');
    $full = $common->httpRequest()->post('fullScreen', 'integer', 1);
    $videoId = $common->httpRequest()->post('video', 'integer', 0);

    if ($id <= 0) {
        $common->ajax()->notifyError(__('Project must exists in order to add videos!', 'works'));
    }

    if ('' == $url || '' == $title) {
        $common->ajax()->notifyError(__('Please provide the URL and title for this video!', 'works'));
    }

    $work = new Works_Work($id);
    if ($work->isNew()) {
        $common->ajax()->notifyError(__('Especified work does not exists!', 'works'));
    }

    if ($edit) {
        if ($videoId <= 0) {
            $common->ajax()->notifyError(__('You must provide a video ID to edit', 'works'));
        }

        $video = new Works_Video($videoId);
        if ($video->isNew()) {
            $common->ajax()->response(__('Specified video does not exists!', 'works'));
        }
    } else {
        $video = new Works_Video();
    }

    $video->url = $url;
    $video->title = $title;
    $video->description = $description;
    $video->image = $image;
    $video->work = $id;

    if (0 == $edit) {
        $video->type = $type;
        $video->fullscreen = $full;
    }

    if ('' == $image) {
        $image = Works_Functions::videoThumbnail($url);
    }

    $video->image = $image;

    if ($video->save()) {
        $common->ajax()->response(
            $edit ? __('Video updated successfully!', 'works') : __('Video added successfully!', 'works'),
            0,
            1,
            [
                'notify' => [
                    'type' => 'alert-success',
                    'icon' => 'svg-rmcommon-video',
                ],
                'video' => [
                    'id' => $video->id(),
                    'url' => $url,
                    'title' => $title,
                    'description' => $description,
                    'image' => RMImageResizer::getInstance()->resize($image, ['width' => 300, 'height' => 180])->url,
                ],
            ]
        );
    }
}

function works_edit_video()
{
    global $common;

    $common->ajax()->prepare();
    $common->checkToken();

    $workId = $common->httpRequest()->get('work', 'integer', 0);
    $videoId = $common->httpRequest()->get('id', 'integer', 0);

    if ($workId <= 0 || $videoId <= 0) {
        $common->ajax()->notifyError(__('You must provide a valid project ID and a valid video ID', 'works'));
    }

    $work = new Works_Work($workId);

    if ($work->isNew()) {
        $common->ajax()->notifyError(__('Specified project does not exists!', 'works'));
    }

    $video = new Works_Video($videoId);
    if ($video->isNew()) {
        $common->ajax()->notifyError(__('Specified video does not exists!', 'works'));
    }

    $common->ajax()->response(
        __('Video data', 'works'),
        0,
        1,
        [
            'video' => [
                'id' => $video->id(),
                'title' => $video->title,
                'description' => $video->description,
                'url' => $video->url,
                'image' => $video->image,
                'type' => $video->type,
                'fullscreen' => $video->fullscreen,
                'work' => $workId,
            ],
        ]
    );
}

/**
 * Deletes a video
 * Response via AJAX with JSON format
 */
function works_delete_video()
{
    global $common;

    $common->ajax()->prepare();
    $common->checkToken();

    $workId = $common->httpRequest()->post('work', 'integer', 0);
    $id = $common->httpRequest()->post('id', 'integer', 0);

    if ($workId <= 0 || $id <= 0) {
        $common->ajax()->notifyError(__('You must provide a valid video ID and a project ID!', 'works'));
    }

    $work = new Works_Work($workId);
    if ($work->isNew()) {
        $common->ajax()->notifyError(__('Specified project does not exists!', 'works'));
    }

    $video = new Works_Video($id);
    if ($video->isNew()) {
        $common->ajax()->notifyError(__('Specified video does not exists!', 'works'));
    }

    if ($video->delete()) {
        $common->ajax()->response(
            sprintf(__('Video <strong>%s</strong> deleted successfully!', 'works'), $video->title),
            0,
            1,
            [
                'id' => $id,
                'notify' => [
                    'type' => 'alert-success',
                    'icon' => 'svg-rmcommon-ok-circle',
                ],
            ]
        );
    }

    $common->ajax()->notifyError(sprintf(__('Video <strong>%s</strong> could not be deleted:', 'works'), $video->title) . $video->errors());
}

$action = RMHttpRequest::request('action', 'string', '');

switch ($action) {
    case 'new':
        formWorks();
        break;
    case 'edit':
        formWorks(1);
        break;
    case 'save':
        saveWorks();
        break;
    case 'saveedited':
        saveWorks(1);
        break;
    case 'delete':
        deleteWorks();
        break;
    case 'public':
        publicWorks(1);
        break;
    case 'nopublic':
        publicWorks();
        break;
    case 'mark':
        markWorks(1);
        break;
    case 'nomark':
        markWorks(0);
        break;
    case 'savemeta':
        works_save_meta();
        break;
    case 'delmeta':
        works_delete_meta();
        break;
    case 'add-video':
        works_add_video();
        break;
    case 'update-video':
        works_add_video(1);
        break;
    case 'edit-video':
        works_edit_video();
        break;
    case 'delete-video':
        works_delete_video();
        break;
    default:
        showWorks();
}
