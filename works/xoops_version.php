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
require_once __DIR__ . '/include/xv-header.php';

if (function_exists('load_mod_locale')) {
    load_mod_locale('works');
}

$modversion = [
    // 1. General
    'name' => 'Professional Works',
    'description' => __('A module to create portfolios in XOOPS', 'works'),
    'version' => 2.2,
    'license' => 'GPL 2',
    'dirname' => 'works',
    'official' => 0,

    // 2. Common Utilities
    'rmnative' => 1,
    'url' => 'https://github.com/bitcero/works',
    'rmversion' => [
        'major' => 2,
        'minor' => 2,
        'revision' => 24,
        'stage' => -1,
        'name' => 'Professional Works',
    ],
    'rewrite' => 0,
    'updateurl' => 'http://www.xoopsmexico.net/modules/vcontrol/',
    'help' => 'docs/readme.html',

    // 3. Author information
    'author' => 'Eduardo Cortes',
    'authormail' => 'i.bitcero@gmail.com',
    'authorweb' => 'Eduardo Cortes',
    'authorurl' => 'http://eduardocortes.mx',
    'credits' => 'Eduardo Cortes',

    // 4. Logo and icons
    'image' => 'images/logo.png',
    'icon' => 'fa fa-briefcase text-brown',

    // 5. Social
    'social' => [
        [
            'title' => 'Twitter',
            'type' => 'twitter-square',
            'url' => 'http://www.twitter.com/bitcero/',
        ],
        [
            'title' => 'Facebook',
            'type' => 'facebook-square',
            'url' => 'http://www.facebook.com/eduardo.cortes.hervis/',
        ],
        [
            'title' => 'Instagram',
            'type' => 'instagram',
            'url' => 'http://www.instagram.com/eduardocortesh/',
        ],
        [
            'title' => 'LinkedIn',
            'type' => 'linkedin-square',
            'url' => 'http://www.linkedin.com/in/bitcero/',
        ],
        [
            'title' => 'GitHub',
            'type' => 'github',
            'url' => 'http://www.github.com/bitcero/',
        ],
        [
            'title' => 'Google+',
            'type' => 'google-plus-square',
            'url' => 'https://plus.google.com/100655708852776329288',
        ],
        [
            'title' => __('My Blog', 'works'),
            'type' => 'quote-left',
            'url' => 'http://eduardocortes.mx',
        ],
    ],

    // 6. Backend
    'hasAdmin' => 1,
    'adminindex' => 'admin/index.php',
    'adminmenu' => 'admin/menu.php',

    // 7. Front End
    'hasMain' => 1,

    // 8. SQL file
    'sqlfile' => [ 'mysql' => 'sql/mysql.sql' ],

    // 9. Database tables
    'tables' => [
        'mod_works_categories',
        'mod_works_categories_rel',
        'mod_works_works',
        'mod_works_images',
        'mod_works_clients',
        'mod_works_types',
        'mod_works_meta',
        'mod_works_videos',
    ],

    // 10. Search
    'hasSearch' => 1,
    'search' => [
        'file' => 'include/search.functions.php',
        'func' => 'works_search',
    ],

    // 11. Smarty templates
    'templates' => [
        [
            'file' => 'works-header.tpl',
            'description' => __('Contains the header for every single template', 'works'),
        ],
        [
            'file' => 'works-index.tpl',
            'description' => __('Home page for module', 'works'),
        ],
        [
            'file' => 'works-recent.tpl',
            'description' => __('Display the recent works', 'works'),
        ],
        [
            'file' => 'works-featured.tpl',
            'description' => __('Display the featured works', 'works'),
        ],
        [
            'file' => 'works-category.tpl',
            'description' => __('Displays the works that belong to a specific category', 'works'),
        ],
        [
            'file' => 'works-item.tpl',
            'description' => __('Display all the information for a specific item', 'works'),
        ],
        [
            'file' => 'works-loop-item.tpl',
            'description' => __('Display the work data for insertion in lists', 'works'),
        ],
    ],

    // 12. Blocks
    'blocks' => [
        #··· Works
        [
            'file' => 'works-block-items.php',
            'name' => __('Works', 'works'),
            'description' => __('Display a block with works', 'works'),
            'show_func' => 'works_block_items_show',
            'edit_func' => 'works_block_items_edit',
            'template' => 'works-block-items.tpl',
            'options' => [0, 0, 0, 1, 1, 1, 0],
        ],
        #··· Testimonials
        [
            'file' => 'works-block-testimonials.php',
            'name' => __('Testimonials', 'works'),
            'description' => __('Display a block with customers testimonials', 'works'),
            'show_func' => 'works_block_testimonials_show',
            'edit_func' => 'works_block_testimonials_edit',
            'template' => 'works-block-testimonials.tpl',
            'options' => [3, 0],
        ],
        #··· Categories
        [
            'file' => 'works-block-categories.php',
            'name' => __('Categories', 'works'),
            'description' => __('Display a block with works categories', 'works'),
            'show_func' => 'works_block_categories_show',
            'edit_func' => '',
            'template' => 'works-block-categories.tpl',
            'options' => [1],
        ],

        #··· Work details
        [
            'file' => 'works-block-details.php',
            'name' => __('Work item details', 'works'),
            'description' => __('Shows details for selected work. This block only works in work page.', 'works'),
            'show_func' => 'works_block_details_show',
            'edit_func' => 'works_block_details_edit',
            'template' => 'works-block-details.tpl',
            'options' => ['description' => 0, 'len' => 80],
        ],
    ],

    // 13. Settings
    'config' => [
        #··· Permalinks
        [
            'name' => 'permalinks',
            'title' => __('Enable permalinks', 'works'),
            'description' => __('This option activate/deactivate friendly URLs for module', 'works'),
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 0,
        ],

        #··· Base path for permalinks
        [
            'name' => 'htbase',
            'title' => __('Base path for permalinks', 'works'),
            'description' => __('Sets the base path to use in friendly URLs.', 'works'),
            'formtype' => 'textbox',
            'valuetype' => 'text',
            'default' => '/portfolio',
        ],

        #··· Header title
        [
            'name' => 'title',
            'title' => __('Header title', 'works'),
            'description' => __('This title will be show as header of module.', 'works'),
            'formtype' => 'textbox',
            'valuetype' => 'text',
            'default' => 'Professional Works',
        ],

        #··· Description length for lists
        [
            'name' => 'desclen',
            'title' => __('Description length in works lists', 'works'),
            'description' => __('This value will limit the length of descriptions in works lists, such as home, categories, recent, etc.', 'works'),
            'formtype' => 'textbox',
            'valuetype' => 'int',
            'default' => '100',
        ],

        #··· Number of recent works
        [
            'name' => 'num_recent',
            'title' => __('Number of recent works', 'works'),
            'description' => __('The number of recent works that will be shown in the home page.', 'works'),
            'formtype' => 'textbox',
            'valuetype' => 'int',
            'default' => 5,
        ],

        #··· Number of featured works
        [
            'name' => 'num_featured',
            'title' => __('Number of featured works', 'works'),
            'description' => __('The number of featured works that will be shown in the home page.', 'works'),
            'formtype' => 'textbox',
            'valuetype' => 'int',
            'default' => 5,
        ],

        #··· Related works
        [
            'name' => 'other_works',
            'title' => __('Related works', 'works'),
            'description' => __('Show or not related works box in details page.', 'works'),
            'formtype' => 'select',
            'valuetype' => 'int',
            'default' => 0,
            'options' => [
                'Not to show' => 0,
                'Same category' => 1,
                'Featured works' => 2,
            ],
        ],

        #··· Number of related works
        [
            'name' => 'num_otherworks',
            'title' => __('Number of related works', 'works'),
            'description' => __('The number of related works that will be shown in the details page.', 'works'),
            'formtype' => 'textbox',
            'valuetype' => 'int',
            'default' => 5,
        ],

        #··· Customer information
        [
            'name' => 'show_customer',
            'title' => __('Show customer information', 'works'),
            'description' => __('When this option is enabled you will see the option to provide customer name and customer testimonial in works form.', 'works'),
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 0,
        ],

        #··· Web site
        [
            'name' => 'show_web',
            'title' => __('Show website information', 'works'),
            'description' => __('When this option is enabled you will see the option to provide the website in works form.', 'works'),
            'formtype' => 'yesno',
            'valuetype' => 'int',
            'default' => 0,
        ],
    ],

    // 14. Module Pages
    'subpages' => [
        'index' => __('Homepage', 'works'),
        'recent' => __('Recent works', 'works'),
        'featured' => __('Featured works', 'works'),
        'work' => __('Work details', 'works'),
        'category' => __('Category content', 'works'),
    ],
];
