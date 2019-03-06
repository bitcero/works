<?php
// $Id: pwfunctions.class.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

class Works_Functions
{
    /**
     * @desc Crea el encabezado de la sección frontal
     */
    public static function makeHeader()
    {
        global $xoopsModuleConfig, $xoopsTpl, $xoopsUser, $db;

        $xoopsTpl->assign('pw_title', $xoopsModuleConfig['title']);
        $xoopsTpl->assign('lang_recentsall', __('Recent works', 'works'));
        $xoopsTpl->assign('lang_featuredall', __('Featured works', 'works'));

        $recent   = $xoopsModuleConfig['permalinks'] ? XOOPS_URL . '/' . trim($xoopsModuleConfig['htbase'], '/') . '/recent/' : XOOPS_URL . '/modules/works/index.php?page=recent';
        $featured = $xoopsModuleConfig['permalinks'] ? XOOPS_URL . '/' . trim($xoopsModuleConfig['htbase'], '/') . '/featured/' : XOOPS_URL . '/modules/works/index.php?page=featured';

        $xoopsTpl->assign('url_recent', $recent);
        $xoopsTpl->assign('url_featured', $featured);
        $xoopsTpl->assign('url_home', PW_URL);
    }

    /**
     * @desc Verifica el tipo de acceso a la información y si es necesario
     *la existencia del archivo htaccess
     **/
    public function accessInfo()
    {
        global $xoopsModuleConfig;

        if (0 == $xoopsModuleConfig['urlmode']) {
            return true;
        }

        $docroot = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
        $path    = str_replace($docroot, '', XOOPS_ROOT_PATH . '/modules/works/');
        if (0 !== mb_strpos($path, '/')) {
            $path = '/' . $path;
        }
        $file = XOOPS_ROOT_PATH . '/modules/works/.htaccess';

        if (!file_exists($file)) {
            return false;
        }

        //Determina permisos de lectura y escritura a htacces
        if ((!is_readable($file))) {
            return false;
        }

        //Verifica que información contiene htaccess y si es necesario reescribe htacces
        $info = file_get_contents($file);

        //Si acceso es por id numérico
        if ($xoopsModuleConfig['urlmode']) {
            $contenido = "RewriteEngine On\nRewriteBase "
                         . str_replace($docroot, '', PW_PATH . '/')
                         . "\nRewriteCond %{REQUEST_URI} !/[A-Z]+-\nRewriteRule ^pag/(.*)/?$ index.php?pag=$1 [L]\nRewriteRule ^recent/(.*)/?$ recent.php$1 [L]\nRewriteRule ^featured/(.*)/?$ featured.php$1 [L]\nRewriteRule ^work/(.*)/?$ work.php?id=$1 [L]\nRewriteRule ^cat/(.*)/?$ catego.php?id=$1 [L]";
            //Compara contenido de htaccess
            $pos = mb_stripos(file_get_contents($file), $contenido);

            if (false !== $pos) {
                return true;
            }

            if ((!is_writable($file))) {
                return false;
            }

            //Copia información a archivo
            return file_put_contents($file, $contenido);
        }
    }

    /**
     * Get works based on given parameters
     * @param int    $limit    Limit of results to get
     * @param int    $category Id of category of works
     * @param string $status   Status of works. Can be <em>public</em>, <em>scheduled</em> or <em>draft</em>
     * @param bool   $object   Return or not objects
     * @param string $order    Sort order
     * @param mixed  $len
     * @return array
     */
    public static function get_works($limit, $category = null, $status = 'public', $object = true, $order = 'created DESC', $len = 100)
    {
        global $xoopsModule, $xoopsModuleConfig;

        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $order  = '' == $order ? 'created DESC' : $order;
        $status = '' == $status ? 'public' : $status;

        if (null === $category || $category <= 0) {
            $sql = 'SELECT * FROM ' . $db->prefix('mod_works_works') . " WHERE status = '$status' ORDER BY $order LIMIT 0, $limit";
        } else {
            $tbw = $db->prefix('mod_works_works');
            $tbc = $db->prefix('mod_works_categories_rel');

            $sql = "SELECT w.* FROM $tbw as w, $tbc as c WHERE c.category = $category AND w.id_work = c.work AND w.status = '$status' ORDER BY $order LIMIT 0, $limit";
        }

        $result = $db->query($sql);
        $works  = [];

        while (false !== ($row = $db->fetchArray($result))) {
            $work = new Works_Work();
            $work->assignVars($row);
            $ret = [];

            $ret = self::render_data($work, $len);

            if ($object) {
                $works[] = (object)$ret;
            } else {
                $works[] = $ret;
            }
        }

        return $works;
    }

    /**
     * Get works custom fields
     * @param int $work
     * @return array
     */
    public static function metas($work)
    {
        if ($work <= 0) {
            return;
        }

        $db     = XoopsDatabaseFactory::getDatabaseConnection();
        $sql    = 'SELECT * FROM ' . $db->prefix('mod_works_meta') . " WHERE work='$work'";
        $result = $db->query($sql);
        $metas  = [];
        while (false !== ($row = $db->fetchArray($result))) {
            $metas[$row['name']] = $row['value'];
        }

        return $metas;
    }

    /**
     * Get all images for a specified work
     * @param int $id Work ID
     * @return array of images
     */
    public static function images($id)
    {
        if ($id <= 0) {
            return false;
        }

        $db     = XoopsDatabaseFactory::getDatabaseConnection();
        $sql    = 'SELECT * FROM ' . $db->prefix('mod_works_images') . " WHERE work = $id";
        $result = $db->query($sql);
        $images = [];
        while (false !== ($row = $db->fetchArray($result))) {
            $images[] = [
                'title' => $row['title'],
                'url'   => $row['image'],
            ];
        }

        return $images;
    }

    /**
     * Get all videos for a specified work
     * @param int $id Work ID
     * @return array of videos
     */
    public static function videos($id)
    {
        if ($id <= 0) {
            return false;
        }

        $db     = XoopsDatabaseFactory::getDatabaseConnection();
        $sql    = 'SELECT * FROM ' . $db->prefix('mod_works_videos') . " WHERE work = $id";
        $result = $db->query($sql);
        $videos = [];

        while (false !== ($row = $db->fetchArray($result))) {
            $videos[] = [
                'id'          => $row['video_id'],
                'title'       => $row['title'],
                'url'         => $row['url'],
                'image'       => $row['image'],
                'description' => $row['description'],
                'type'        => $row['type'],
            ];
        }

        return $videos;
    }

    /**
     * SENDs an HTTP status code to browser
     * @return void
     */
    public static function send_404_status()
    {
        RMFunctions::error_404(__('Document not found!', 'works'), 'works');
    }

    /**
     * Checks if a specific user have access rights for a project
     * @param Works_Work $work
     * @param null       $user <p>Can be a XoopsUser object or null</p>
     * @return bool
     */
    public static function is_allowed(Works_Work $work, $user = null)
    {
        global $xoopsUser;

        if (!$user) {
            $user = $xoopsUser;
        }

        if ('public' == $work->status) {
            return true;
        }

        if ($user->isAdmin()) {
            return true;
        }

        /**
         * @TODO: provide the module ID
         */
        if ('draft' == $work->status && (!$user || !$user->isAdmin())) {
            return false;
        }

        $groups    = $user->getGroups();
        $intersect = array_intersect($groups, $work->groups);

        if ('private' == $work->status && empty($intersect)) {
            return false;
        }

        if ('scheduled' == $work->status && strtotime($work->schedule) > time()) {
            return false;
        }

        return true;
    }

    public static function render_data(&$work, $desclen)
    {
        $tf = new RMTimeFormatter(0, '%d%/%T%/%Y%');

        $ret = [
            'id'          => $work->id(),
            'title'       => $work->title,
            'description' => $desclen > 0 ? TextCleaner::getInstance()->truncate($work->description, $desclen) : '',
            'customer'    => $work->customer,
            'web'         => $work->web,
            'url'         => $work->url,
            'created'     => $tf->format($work->created),
            'featured'    => $work->featured,
            'image'       => RMImage::get()->load_from_params($work->image),
            'comment'     => $work->comment,
            'rating'      => $work->rating,
            'views'       => $work->views,
            'metas'       => $work->get_meta(),
            'link'        => $work->permalink(),
            'images'      => $work->images(),
            'categories'  => $work->categories('objects'),
            'status'      => $work->status,
        ];

        /**
         * Notify to other components
         */
        $ret = RMEvents::get()->trigger('works.render.data', $ret, $work);

        return $ret;
    }

    public static function go_scheduled()
    {
        $db = XoopsDatabaseFactory::getDatabaseConnection();

        $sql = 'UPDATE ' . $db->prefix('mod_works_works') . " SET status='public' WHERE schedule <= '" . date('Y-m-d H:i:s', time()) . "' AND status = 'scheduled'";
        $db->queryF($sql);
    }

    /**
     * Get categories and return the categories tree
     *
     * This fuction accepts parameters as array. The parameters could be:
     * <pre>'parent'  = Id of parent category where to search (Default: 0)
     * 'level' = The starting category level (tree) (Default: 0)
     * 'status' = Search '' (for all), 'active' or 'inactive' categories (Default: '')
     * 'exclude' = Id of the category to exclude from tree (Default: 0)
     * 'expected' = Type of array expected: 'format' will return a formated data, 'raw' returns the data directly from database (Default: 'format)</pre>
     *
     * <strong>How to use:</strong>
     * <pre>$categories = array();
     * Works_Functions::categories_tree( $categories, array(
     *     'parent'  => 1,
     *     'level'   => 0,
     *     'status'  => 'active',
     *     'exclude' => 3,
     *     'expected' => 'raw'
     * ));</pre>
     * @param array $tree       Referenced array to fill with data
     * @param array $parameters Parameters to perform search
     */
    public static function categories_tree(&$tree, $parameters = [])
    {
        extract($parameters);
        $parent   = isset($parent) ? $parent : 0;
        $level    = isset($level) ? $level : 0;
        $status   = isset($status) ? $status : '';
        $exclude  = isset($exclude) ? $exclude : 0;
        $expected = isset($expected) ? $expected : 'format';
        $links    = isset($links) ? $links : true;

        $db = XoopsDatabaseFactory::getDatabaseConnection();
        if ('active' != $status && 'inactive' != $status) {
            $status = '';
        }

        $sql    = 'SELECT * FROM ' . $db->prefix('mod_works_categories') . " WHERE parent=$parent";
        $sql    .= trim('' == $status) ? '' : " AND status = '" . $status . "'";
        $sql    .= $exclude > 0 ? " AND id_cat != $exclude" : '';
        $sql    .= ' ORDER BY `position`,status';
        $result = $db->query($sql);

        while (false !== ($row = $db->fetchArray($result))) {
            if ('raw' == $expected) {
                $row['level']         = $level;
                $tree[$row['id_cat']] = $row;
            } else {
                $category = new Works_Category();
                $category->assignVars($row);

                $tree[$category->id()] = [
                    'id'          => $category->id(),
                    'name'        => $category->name,
                    'description' => $category->description,
                    'status'      => $category->status,
                    'parent'      => $category->parent,
                    'level'       => $level,
                    'link'        => $links ? $category->permalink() : '',
                ];
            }

            self::categories_tree($tree, [
                'parent'   => $row['id_cat'],
                'level'    => $level + 1,
                'status'   => $status,
                'exclude'  => $exclude,
                'expected' => $expected,
            ]);
        }
    }

    public static function videoThumbnail($source)
    {
        // Check if videos thumbnails directory exists
        $path = XOOPS_UPLOAD_PATH . '/works/videos/';
        if (!is_dir($path)) {
            mkdir($path, 511, true);
        }

        if (false !== mb_strpos($source, 'vimeo.com/')) {
            /* VIMEO */

            $matches = [];
            preg_match("/.*.\/([0-9]{3,}).*/", $source, $matches);

            $data = unserialize(file_get_contents('http://vimeo.com/api/v2/video/' . $matches[1] . '.php'));

            file_put_contents($path . 'vimeo-' . $matches[1] . '.jpg', file_get_contents($data[0]['thumbnail_large']));

            return XOOPS_UPLOAD_URL . '/works/videos/' . 'vimeo-' . $matches[1] . '.jpg';
        } elseif (false !== mb_strpos($source, 'youtube.com')) {
            /* YOUTUBE */

            $params = [];
            $params = parse_url($source);
            parse_str($params['query'], $params);

            if (isset($params['v'])) {
                file_put_contents($path . 'youtube-' . $params['v'] . '.jpg', file_get_contents('http://img.youtube.com/vi/' . $params['v'] . '/hqdefault.jpg'));

                return XOOPS_UPLOAD_URL . '/works/videos/youtube-' . $params['v'] . '.jpg';
            }
        } elseif (false !== mb_strpos($source, 'youtu.be')) {
            /* YOUTUBE */

            $params = [];
            preg_match("/^http.*youtu\.be\/([a-zA-Z\d]+)$/", $source, $params);

            if (isset($params[1])) {
                file_put_contents($path . 'youtube-' . $params[1] . '.jpg', file_get_contents('http://img.youtube.com/vi/' . $params[1] . '/hqdefault.jpg'));

                return XOOPS_UPLOAD_URL . '/works/videos/youtube-' . $params[1] . '.jpg';
            }
        } elseif (false !== mb_strpos($source, '//www.dailymotion.com/video')) {
            /* DAILY MOTION */
            $params = [];
            preg_match("/^http.*dailymotion\.com\/video\/([a-zA-Z0-9]+)._*/", $source, $params);

            if (isset($params[1]) && '' != $params[1]) {
                $data = json_decode(file_get_contents('https://api.dailymotion.com/video/' . $params[1] . '?fields=thumbnail_large_url'), true);
                if ($data) {
                    file_put_contents($path . 'daily-' . $params[1] . '.jpg', file_get_contents($data['thumbnail_large_url']));

                    return XOOPS_UPLOAD_URL . '/works/videos/daily-' . $params[1] . '.jpg';
                }
            }
        } elseif (false !== mb_strpos($source, '//www.dailymotion.com/embed/video/')) {
            $id = str_replace('//www.dailymotion.com/embed/video/', '', $source);
            /* DAILY MOTION */
            $data = json_decode(file_get_contents('https://api.dailymotion.com/video/' . $id . '?fields=thumbnail_large_url'), true);
            if ($data) {
                file_put_contents($path . 'daily-' . $id . '.jpg', file_get_contents($data['thumbnail_large_url']));

                return XOOPS_UPLOAD_URL . '/works/videos/daily-' . $id . '.jpg';
            }
        }

        return '';
    }
}
