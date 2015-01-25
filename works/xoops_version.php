<?php
/*
Professional Works
Module for personals and professionals portfolios
Author: Eduardo Cortes <i.bitcero@gmail.com>
Email: i.bitcero@gmail.com
Website: eduardocortes.mx
License: GPL 2.0
-------------------------------------------------
PLEASE: DO NOT MODIFY ABOVE LINES
*/

/**
 * This file contains all XOOPS information for Professional Works
 * ¡DO NOT MODIFY THIS FILE!
 *
 * CONTENT
 * 1. General information
 * 2. Common Utilities information
 * 3. Author information
 * 4. Logo and icons
 * 5. Social links
 * 6. Backend information
 * 7. Frontend information
 * 8. SQL file
 * 9. Database tables
 * 10. Search configuration
 * 11. Smarty templates
 * 12. Blocks
 * 13. Settings
 * 14. Module subpages
 */

include_once 'include/xv-header.php';

if(function_exists("load_mod_locale")) load_mod_locale('works');

$modversion = array(

    // 1. General
    'name'          => 'Professional Works',
    'description'   => __('A module to create portfolios in XOOPS','works'),
    'version'       => 2.2,
    'license'       => 'GPL 2',
    'dirname'       => 'works',
    'official'      => 0,

    // 2. Common Utilities
    'rmnative'      => 1,
    'rmversion'     => array(
        'major'     => 2,
        'minor'     => 2,
        'revision'  => 8,
        'stage'     => -1,
        'name'      => 'Professional Works'
    ),
    'rewrite'       => 0,
    'updateurl'     => "http://www.xoopsmexico.net/modules/vcontrol/",
    'help'          => 'docs/readme.html',

    // 3. Author information
    'author'        => "Eduardo Cortes",
    'authormail'    => "yo@eduardocortes.mx",
    'authorweb'     => "EduardoCortes.mx",
    'authorurl'     => "http://eduardocortes.mx",
    'credits'       => "Eduardo Cortes",

    // 4. Logo and icons
    'image'         => "images/logo.png",
    'icon16'        => "images/works-16.png",
    'icon24'        => 'images/works-24.png',
    'icon32'        => 'images/works-32.png',
    'icon48'        => "images/works-48.png",

    // 5. Social
    'social'        => array(
        array(
            'title' => 'Twitter',
            'type'  => 'twitter-square',
            'url'   => 'http://www.twitter.com/bitcero/'
        ),
        array(
            'title' => 'Facebook',
            'type'  => 'facebook-square',
            'url'   => 'http://www.facebook.com/eduardo.cortes.hervis/'
        ),
        array(
            'title' => 'Instagram',
            'type'  => 'instagram',
            'url'   => 'http://www.instagram.com/eduardocortesh/'
        ),
        array(
            'title' => 'LinkedIn',
            'type'  => 'linkedin-square',
            'url'   => 'http://www.linkedin.com/in/bitcero/'
        ),
        array(
            'title' => 'GitHub',
            'type'  => 'github',
            'url'   => 'http://www.github.com/bitcero/'
        ),
        array(
            'title' => 'Google+',
            'type'  => 'google-plus-square',
            'url'   => 'https://plus.google.com/100655708852776329288'
        ),
        array(
            'title' => __('My Blog', 'works'),
            'type'  => 'quote-left',
            'url'   => 'http://eduardocortes.mx'
        ),
    ),

    // 6. Backend
    'hasAdmin'      => 1,
    'adminindex'    => "admin/index.php",
    'adminmenu'     => "admin/menu.php",

    // 7. Front End
    'hasMain'       => 1,

    // 8. SQL file
    'sqlfile'       => array( 'mysql' => "sql/mysql.sql" ),

    // 9. Database tables
    'tables'        => array(
        'mod_works_categories',
        'mod_works_categories_rel',
        'mod_works_works',
        'mod_works_images',
        'mod_works_clients',
        'mod_works_types',
        'mod_works_meta',
    ),

    // 10. Search
    'hasSearch'     => 1,
    'search'        => array(
        'file'      => 'include/search.functions.php',
        'func'      => 'works_search'
    ),

    // 11. Smarty templates
    'templates'     => array(
        array(
            'file'          => 'works-header.tpl',
            'description'   => __('Contains the header for every single template', 'works')
        ),
        array(
            'file'          => 'works-index.tpl',
            'description'   => __('Home page for module', 'works')
        ),
        array(
            'file'          => 'works-recent.tpl',
            'description'   => __('Display the recent works', 'works')
        ),
        array(
            'file'          => 'works-featured.tpl',
            'description'   => __('Display the featured works', 'works')
        ),
        array(
            'file'          => 'works-category.tpl',
            'description'   => __('Displays the works that belong to a specific category', 'works')
        ),
        array(
            'file'          => 'works-item.tpl',
            'description'   => __('Display all the information for a specific item', 'works')
        ),
        array(
            'file'          => 'works-loop-item.tpl',
            'description'   => __('Display the work data for insertion in lists', 'works')
        )
    ),

    // 12. Blocks
    'blocks'        => array(
        #··· Works
        array(
            'file'          => 'works-block-items.php',
            'name'          => __('Works', 'works'),
            'description'   => __('Display a block with works', 'works'),
            'show_func'     => 'works_block_items_show',
            'edit_func'     => 'works_block_items_edit',
            'template'      => 'works-block-items.tpl',
            'options'       => array(0,0,0,1,1,1,0)
        ),
        #··· Testimonials
        array(
            'file'          => 'works-block-testimonials.php',
            'name'          => __('Testimonials', 'works'),
            'description'   => __('Display a block with customers testimonials', 'works'),
            'show_func'     => 'works_block_testimonials_show',
            'edit_func'     => 'works_block_testimonials_edit',
            'template'      => 'works-block-testimonials.tpl',
            'options'       => array(3,0)
        ),
        #··· Categories
        array(
            'file'          => 'works-block-categories.php',
            'name'          => __('Categories', 'works'),
            'description'   => __('Display a block with works categories', 'works'),
            'show_func'     => 'works_block_categories_show',
            'edit_func'     => '',
            'template'      => 'works-block-categories.tpl',
            'options'       => array(1)
        ),

        #··· Work details
        array(
            'file'          => 'works-block-details.php',
            'name'          => __('Work item details', 'works'),
            'description'   => __('Shows details for selected work. This block only works in work page.', 'works'),
            'show_func'     => 'works_block_details_show',
            'edit_func'     => 'works_block_details_edit',
            'template'      => 'works-block-details.tpl',
            'options'       => array('description' => 0, 'len' => 80)
        )
    ),

    // 13. Settings
    'config'        => array(

        #··· Permalinks
        array(
            'name'          => 'permalinks',
            'title'         => __('Enable permalinks', 'works'),
            'description'   => __('This option activate/deactivate friendly URLs for module', 'works'),
            'formtype'      => 'yesno',
            'valuetype'     => 'int',
            'default'       => 0
        ),

        #··· Base path for permalinks
        array(
            'name'          => 'htbase',
            'title'         => __('Base path for permalinks', 'works'),
            'description'   => __('Sets the base path to use in friendly URLs.', 'works'),
            'formtype'      => 'textbox',
            'valuetype'     => 'text',
            'default'       => '/portfolio'
        ),

        #··· Header title
        array(
            'name'          => 'title',
            'title'         => __('Header title', 'works'),
            'description'   => __('This title will be show as header of module.', 'works'),
            'formtype'      => 'textbox',
            'valuetype'     => 'text',
            'default'       => 'Professional Works'
        ),

        #··· Description length for lists
        array(
            'name'          => 'desclen',
            'title'         => __('Description length in works lists', 'works'),
            'description'   => __('This value will limit the length of descriptions in works lists, such as home, categories, recent, etc.', 'works'),
            'formtype'      => 'textbox',
            'valuetype'     => 'int',
            'default'       => '100'
        ),

        #··· Number of recent works
        array(
            'name'          => 'num_recent',
            'title'         => __('Number of recent works', 'works'),
            'description'   => __('The number of recent works that will be shown in the home page.', 'works'),
            'formtype'      => 'textbox',
            'valuetype'     => 'int',
            'default'       => 5
        ),

        #··· Number of featured works
        array(
            'name'          => 'num_featured',
            'title'         => __('Number of featured works', 'works'),
            'description'   => __('The number of featured works that will be shown in the home page.', 'works'),
            'formtype'      => 'textbox',
            'valuetype'     => 'int',
            'default'       => 5
        ),

        #··· Related works
        array(
            'name'          => 'other_works',
            'title'         => __('Related works', 'works'),
            'description'   => __('Show or not related works box in details page.', 'works'),
            'formtype'      => 'select',
            'valuetype'     => 'int',
            'default'       => 0,
            'options'       => array(
                'Not to show'       => 0,
                'Same category'     => 1,
                'Featured works'    => 2
            )
        ),

        #··· Number of related works
        array(
            'name'          => 'num_otherworks',
            'title'         => __('Number of related works', 'works'),
            'description'   => __('The number of related works that will be shown in the details page.', 'works'),
            'formtype'      => 'textbox',
            'valuetype'     => 'int',
            'default'       => 5
        ),

        #··· Customer information
        array(
            'name'          => 'show_customer',
            'title'         => __('Show customer information', 'works'),
            'description'   => __('When this option is enabled you will see the option to provide customer name and customer testimonial in works form.', 'works'),
            'formtype'      => 'yesno',
            'valuetype'     => 'int',
            'default'       => 0
        ),

        #··· Web site
        array(
            'name'          => 'show_web',
            'title'         => __('Show website information', 'works'),
            'description'   => __('When this option is enabled you will see the option to provide the website in works form.', 'works'),
            'formtype'      => 'yesno',
            'valuetype'     => 'int',
            'default'       => 0
        ),

    ),

    // 14. Module Pages
    'subpages'      => array(

        'index'     => __('Homepage','works'),
        'recent'    => __('Recent works','works'),
        'featured'  => __('Featured works','works'),
        'work'      => __('Work details','works'),
        'category'  => __('Category content','works')

    )
    
);




