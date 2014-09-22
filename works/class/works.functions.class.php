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
	public function makeHeader(){
		global $xoopsModuleConfig, $xoopsTpl, $xoopsUser, $db;
		
		$xoopsTpl->assign('pw_title', $xoopsModuleConfig['title']);
		$xoopsTpl->assign('lang_recentsall', __('Recent works','works'));
		$xoopsTpl->assign('lang_featuredall', __('Featured works','works'));
		
		$recent = $xoopsModuleConfig['permalinks'] ? XOOPS_URL.'/'.trim($xoopsModuleConfig['htbase'], '/').'/recent/' : XOOPS_URL.'/modules/works/index.php?page=recent';
		$featured = $xoopsModuleConfig['permalinks'] ? XOOPS_URL.'/'.trim($xoopsModuleConfig['htbase'], '/').'/featured/' : XOOPS_URL.'/modules/works/index.php?page=featured';
		
		$xoopsTpl->assign('url_recent', $recent);
		$xoopsTpl->assign('url_featured', $featured);
		$xoopsTpl->assign('url_home', PW_URL);
			
	}

	/**
	* @desc Rating del trabajo
	* @param $rating del trabajo
	**/
	public function rating($rating){
		$rtn = '<div class="pwRating" style="font-weight: bold; color: #999; font-family: Verdana, arial, helvetica, sans-serif; font-size: 10px; width: 69px; text-align: center;"><div style="text-align: left;width: 69px; height: 15px; background: url('.XOOPS_URL.'/modules/works/images/starsgray.png) no-repeat;">';
		$rating = $rating;
		$percent = 10/69;
		$rtn .= '<div style="text-align: center; width: '.($rating>0 ? ($rating/$percent > 69 ? 69 : round($rating/$percent)) : 0).'px; height: 15px; background: url('.XOOPS_URL.'/modules/works/images/stars.png) no-repeat;">&nbsp;</div>';
		$rtn .= '</div>';
		$rtn .= "</div>";
		return $rtn;
	}

	/**
	* @desc Verifica el tipo de acceso a la información y si es necesario 
	*la existencia del archivo htaccess
	**/
	public function accessInfo(){
		global $xoopsModuleConfig;

		if ($xoopsModuleConfig['urlmode']==0) return true;
		
		$docroot = str_replace("\\", "/", $_SERVER['DOCUMENT_ROOT']);
		$path = str_replace($docroot, '', XOOPS_ROOT_PATH.'/modules/works/');
		if (substr($path, 0, 1)!='/'){
			$path = '/'.$path;
		}
		$file=XOOPS_ROOT_PATH.'/modules/works/.htaccess';

		if (!file_exists($file)){
			return false;
		}
			
		//Determina permisos de lectura y escritura a htacces
		if ((!is_readable($file)))		
		{
			return false;
		}
		
		//Verifica que información contiene htaccess y si es necesario reescribe htacces
		$info = file_get_contents($file);
		
		//Si acceso es por id numérico
		if ($xoopsModuleConfig['urlmode']){
			$contenido = "RewriteEngine On\nRewriteBase ".str_replace($docroot, '', PW_PATH.'/')."\nRewriteCond %{REQUEST_URI} !/[A-Z]+-\nRewriteRule ^pag/(.*)/?$ index.php?pag=$1 [L]\nRewriteRule ^recent/(.*)/?$ recent.php$1 [L]\nRewriteRule ^featured/(.*)/?$ featured.php$1 [L]\nRewriteRule ^work/(.*)/?$ work.php?id=$1 [L]\nRewriteRule ^cat/(.*)/?$ catego.php?id=$1 [L]";
			//Compara contenido de htaccess
			$pos = stripos(file_get_contents($file),$contenido);		

			if ($pos!==false) return true;
			
			if ((!is_writable($file)))		
			{
				return false;
			}
			
			//Copia información a archivo
			return file_put_contents($file,$contenido);
		
		}

	}

	/**
	* Get works based on given parameters
	*/
	static public function get_works($limit, $category=null, $status='public', $object=true, $order="created DESC"){
		global $xoopsModule, $xoopsModuleConfig;
        
        include_once XOOPS_ROOT_PATH.'/modules/works/class/pwwork.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/works/class/pwcategory.class.php';
        include_once XOOPS_ROOT_PATH.'/modules/works/class/pwclient.class.php';
        
		$db = XoopsDatabaseFactory::getDatabaseConnection();
		$sql = "SELECT * FROM ".$db->prefix('mod_works_works')." WHERE public=$public";
		$sql .= $category>0 ? " AND catego='$category'" : '';
        $sql .= $order!='' ? " ORDER BY $order" : '';
		$sql.= " LIMIT 0,$limit";
		
		if ($xoopsModule && $xoopsModule->dirname()=='works'){
			$mc =& $xoopsModuleConfig;
		} else {
			$mc = RMUtilities::module_config('works');
		}
		
		$result = $db->query($sql);
		$works = array();
		while ($row = $db->fetchArray($result)){
			$work = new Works_Work();
			$work->assignVars($row);
			$ret = array();

			if (!isset($categos[$work->category()])) $categos[$work->category()] = new Works_Category($work->category());

			if (!isset($clients[$work->client()])) $clients[$work->client()] = new PWClient($work->client());

			$ret = array(
				'id'=>$work->id(),
				'title'=>$work->title(),
				'desc'=>$work->descShort(),
				'catego'=>$categos[$work->category()]->name(),
				'client'=>$clients[$work->client()]->name(),
				'link'=>$work->link(),
				'created'=>formatTimeStamp($work->created(),'s'),
                'created_time'=>$work->created(),
				'image'=>XOOPS_UPLOAD_URL.'/works/ths/'.$work->image(),
				'rating'=>Works_Functions::rating($work->rating()),
				'featured'=>$work->mark(),
				'linkcat'=>$categos[$work->category()]->link(),
                'metas'=>$work->get_metas()
			);
			
			if ($object){
	    		$w = new stdClass();
				foreach ($ret as $var => $value){
					$w->$var = $value;
				}
				$works[] = $w;
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
    static public function metas($work){
        
        if ($work<=0) return;
        
        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT * FROM ".$db->prefix("mod_works_meta")." WHERE work='$work'";
        $result = $db->query($sql);
        $metas = array();
        while ($row = $db->fetchArray($result)){
            $metas[$row['name']] = $row['value'];
        }
        
        return $metas;
        
    }

    /**
     * Get all images for a specified work
     * @param int $id Work ID
     * @return array of images
     */
    static public function images( $id ){

        if ( $id <= 0 )
            return false;

        $db = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = "SELECT * FROM " . $db->prefix("mod_works_images") . " WHERE work = $id";
        $result = $db->query( $sql );
        $images = array();
        while( $row = $db->fetchArray( $result ) ){

            $images[] = array(
                'title' => $row['title'],
                'url'   => $row['image']
            );

        }

        return $images;

    }

    /**
     * SENDs an HTTP status code to browser
     * @return bool
     */
    static function send_404_status(){

        header("HTTP/1.0 404 Not Found");
        http_response_code(404);

        $controller = RMUris::current_url();
        $controller = str_replace( PW_URL, '', $controller );

        include $GLOBALS['rmTpl']->get_template("rm-error-404.php", 'module', 'rmcommon');

        return true;

    }

    /**
     * Checks if a specific user have access rights for a project
     * @param Works_Work $work
     * @param null $user <p>Can be a XoopsUser object or null</p>
     * @return bool
     */
    static public function is_allowed( Works_Work $work, $user = null ){

        global $xoopsUser;

        if ( !$user )
            $user = $xoopsUser;

        if ( $work->status == 'public' )
            return true;

        if ( $user->isAdmin() )
            return true;

        /**
         * @TODO: provide the module ID
         */
        if ( $work->status == 'draft' && (!$user || !$user->isAdmin() ) )
            return false;

        $groups = $user->getGroups();
        $intersect = array_intersect( $groups, $work->groups );

        if ( $work->status == 'private' && empty( $intersect ) )
            return false;

        if ( $work->status == 'scheduled' && strtotime( $work->schedule ) > time() )
            return false;

        return true;

    }

    static function render_data( &$work, $desclen ){

        $ret = array(
            'id'            => $work->id(),
            'title'         => $work->title,
            'description'   => TextCleaner::getInstance()->truncate( $work->description, $desclen ),
            'customer'      => $work->customer,
            'web'           => $work->web,
            'url'           => $work->url,
            'created'       => formatTimeStamp($work->created,'s'),
            'featured'      => $work->featured,
            'image'         => RMImage::get()->load_from_params( $work->image ),
            'comment'       => $work->comment,
            'rating'        => $work->rating,
            'views'         => $work->views,
            'metas'         => $work->get_meta(),
            'link'          => $work->permalink(),
            'images'        => $work->images(),
            'categories'    => $work->categories( 'objects' ),
            'status'        => $work->status
        );

        return $ret;

    }

}
