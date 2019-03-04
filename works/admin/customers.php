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
define('RMCLOCATION', 'customers');
require __DIR__ . '/header.php';

/**
 * @desc Visualiza todos los clientes
 **/
function showClients()
{
    global $xoopsModule, $xoopsSecurity;

    define('RMCSUBLOCATION', 'customerslist');

    $db = XoopsDatabaseFactory::getDatabaseConnection();
    //Barra de Navegación
    $sql = 'SELECT COUNT(*) FROM ' . $db->prefix('mod_works_clients');

    list($num) = $db->fetchRow($db->query($sql));

    $page = rmc_server_var($_GET, 'page', 1);
    $limit = 15;
    $tpages = ceil($num / $limit);
    $page = $page > $tpages ? $tpages : $page;

    $start = $num <= 0 ? 0 : ($page - 1) * $limit;

    $nav = new RMPageNav($num, $limit, $page, 5);
    $nav->target_url('clients.php?page={PAGE_NUM}');

    $sql = 'SELECT * FROM ' . $db->prefix('mod_works_clients') . ' ORDER BY id_client';
    $sql .= " LIMIT $start,$limit";
    $result = $db->query($sql);
    $customers = [];
    while (false !== ($rows = $db->fetchArray($result))) {
        $client = new PWClient();
        $client->assignVars($rows);

        $type = new PWType($client->type());

        $customers[] = [
            'id' => $client->id(),
            'name' => $client->name(),
            'business' => $client->businessName(),
            'type' => $type->type(),
            'description' => $client->desc(),
        ];
    }

    // Event
    $customers = RMEvents::get()->run_event('works.list.customers', $customers);

    RMTemplate::get()->add_style('admin.css', 'works');
    xoops_cp_location('<a href="./">' . $xoopsModule->name() . '</a> &raquo; ' . __('Customers', 'works'));
    RMTemplate::get()->assign('xoops_pagetitle', __('Customers', 'works'));
    RMTemplate::getInstance()->add_script('admin-works.min.js', 'works', ['id' => 'works-js', 'footer' => 1]);
    RMTemplate::get()->add_head("<script type='text/javascript'>\nvar pw_message='" . __('Do you really want to delete selected customers?', 'works') . "';\n
        var pw_select_message = '" . __('You must select some customer before to execute this action!', 'works') . "';</script>");
    xoops_cp_header();

    include RMTemplate::get()->get_template('admin/works-customers.php', 'module', 'works');

    xoops_cp_footer();
}

/**
 * @desc Formulario de creación/edición de clientes
 * @param mixed $edit
 **/
function formClients($edit = 0)
{
    global $xoopsModule, $db;

    define('RMCSUBLOCATION', 'newcustomer');

    $id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
    $page = isset($_REQUEST['pag']) ? $_REQUEST['pag'] : '';
    $limit = isset($_REQUEST['limit']) ? intval($_REQUEST['limit']) : 15;

    $ruta = "pag=$page&limit=$limit";

    if ($edit) {
        //Verificamos si el cliente es válido
        if ($id <= 0) {
            redirectMsg('./clients.php?' . $ruta, __('You must provide a customer ID', 'works'), 1);
            die();
        }

        //Verificamos si el cliente existe
        $client = new PWClient($id);
        if ($client->isNew()) {
            redirectMsg('./clients.php?' . $ruta, __('Specified customer does not exists!', 'works'), 1);
            die();
        }
    }

    xoops_cp_location('<a href="./">' . $xoopsModule->name() . "</a> &raquo; <a href='./clients.php'>" . __('Customers', 'works') . '</a> &raquo;' . ($edit ? __('Edit Customer', 'works') : __('New Customer', 'works')));
    RMTemplate::get()->assign('xoops_pagetitle', __('Customers', 'works'));
    Works_Functions::toolbar();
    xoops_cp_header();

    $form = new RMForm($edit ? __('Edit Customer', 'works') : __('New Customer', 'works'), 'frmClient', 'clients.php');

    $form->addElement(new RMFormText(__('Name', 'works'), 'name', 50, 200, $edit ? $client->name() : ''), true);
    $form->addElement(new RMFormText(__('Company', 'works'), 'business', 50, 200, $edit ? $client->businessName() : ''));
    $form->addElement(new RMFormText(__('Email address', 'works'), 'mail', 50, 100, $edit ? $client->email() : ''));
    $form->addElement(new RMFormTextArea(__('Description', 'works'), 'desc', 4, 50, $edit ? $client->desc() : ''), true);

    //Tipos de Cliente
    $ele = new RMFormSelect(__('Type', 'works'), 'type');
    $ele->addOption(0, _SELECT);
    $result = $db->query('SELECT * FROM ' . $db->prefix('pw_types'));
    while (false !== ($row = $db->fetchArray($result))) {
        $ele->addOption($row['id_type'], $row['type'], $edit ? ($row['id_type'] == $client->type() ? 1 : 0) : 0);
    }

    $form->addElement($ele, true, 'noselect:0');

    $form->addElement(new RMFormHidden('op', $edit ? 'saveedit' : 'save'));
    $form->addElement(new RMFormHidden('id', $id));
    $form->addElement(new RMFormHidden('page', $page));
    $form->addElement(new RMFormHidden('limit', $limit));

    $ele = new RMFormButtonGroup();
    $ele->addButton('sbt', $edit ? __('Save Changes', 'works') : __('Create Customer', 'works'), 'submit');
    $ele->addButton('cancel', __('Cancel', 'works'), 'button', 'onclick="window.location=\'clients.php?' . $ruta . '\';"');
    $form->addElement($ele);

    //Event
    $form = RMEvents::get()->run_event('works.form.customers', $form);

    $form->display();

    xoops_cp_footer();
}

/**
 * @desc Almacena en la base de datos la información del cliente
 * @param mixed $edit
 **/
function saveClients($edit = 0)
{
    global $xoopsSecurity;

    foreach ($_POST as $k => $v) {
        $$k = $v;
    }

    $ruta = "pag=$page&limit=$limit";

    if (!$xoopsSecurity->check()) {
        redirectMsg('./clients.php?op=' . ($edit ? 'edit&id=' . $id : 'new') . '&' . $ruta, __('Session token expired!', 'works'), 1);
        die();
    }

    if ($edit) {
        //Verificamos si el cliente es válido
        if ($id <= 0) {
            redirectMsg('./clients.php?' . $ruta, __('Customer ID not provided!', 'works'), 1);
            die();
        }

        //Verificamos si el cliente existe
        $client = new PWClient($id);
        if ($client->isNew()) {
            redirectMsg('./clients.php?' . $ruta, __('Specified customer does not exists!', 'works'), 1);
            die();
        }
    } else {
        $client = new PWClient();
    }

    $client->setName($name);
    $client->setBusinessName($business);
    $client->setEmail($mail);
    $client->setType($type);
    $client->setDesc(mb_substr($desc, 0, 50));
    $client->isNew() ? $client->setCreated(time()) : $client->setModified(time());

    //Event
    $client = RMEvents::get()->run_event('works.save.customer', $client);

    if (!$client->save()) {
        redirectMsg('./clients.php?' . $ruta, __('Errors occurred while trying to update database!', 'works') . '<br>' . $client->errors(), 1);
        die();
    }
    redirectMsg('./clients.php?' . $ruta, __('Database updated successfully!', 'works'), 0);
    die();
}

/**
 * @desc Elimina de la base de datos la información del cliente
 **/
function deleteClients()
{
    global $xoopsSecurity, $xoopsModule;

    $ids = rmc_server_var($_POST, 'ids', []);
    $page = rmc_server_var($_POST, 'page', 1);
    $limit = rmc_server_var($_POST, 'limit', 15);

    $ruta = "page=$page&limit=$limit";

    //Verificamos que nos hayan proporcionado un cliente para eliminar
    if (!is_array($ids)) {
        redirectMsg('./clients.php?' . $ruta, __('You must specify a customer to delete!', 'works'), 1);
        die();
    }

    if (!$xoopsSecurity->check()) {
        redirectMsg('./clients.php?' . $ruta, __('Session token expired!', 'works'), 1);
        die();
    }

    $errors = '';
    foreach ($ids as $k) {
        //Verificamos si el cliente es válido
        if ($k <= 0) {
            $errors .= sprintf(__('Customer ID "%u" is not valid!', 'works'), $k);
            continue;
        }

        //Verificamos si el cliente existe
        $client = new PWClient($k);
        if ($client->isNew()) {
            $errors .= sprintf(__('Customer with ID "%u" does not exists!', 'works'), $k);
            continue;
        }

        // Event
        RMEvents::get()->run_event('works.deleting.customer', $client);

        if (!$client->delete()) {
            $errors .= sprintf(__('Customer with ID "%u" could not be deleted!', 'works'), $k);
        }
    }

    if ('' != $errors) {
        redirectMsg('./clients.php?' . $ruta, __('Errors ocurred while trying to delete customers:', 'works') . '<br>' . $errors, 1);
        die();
    }
    redirectMsg('./clients.php?' . $ruta, __('Database updated successfully!', 'works'), 0);
    die();
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';
switch ($op) {
    case 'new':
        formClients();
        break;
    case 'edit':
        formClients(1);
        break;
    case 'save':
        saveClients();
        break;
    case 'saveedit':
        saveClients(1);
        break;
    case 'delete':
        deleteClients();
        break;
    default:
        showClients();
}
