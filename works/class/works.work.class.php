<?php
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class Works_Work extends RMObject
{
    /**
    * Custom fields storage
    */
    private $meta = array();
    /**
     * @var array Images container
     */
    private $images = array();

	public function __construct($id=null){
		
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("mod_works_works");
		$this->setNew();
		$this->initVarsFromTable();

        $this->setVarType( 'groups', XOBJ_DTYPE_ARRAY );
		
		if ($id==null) return;
		
		if (is_numeric($id)){
			if (!$this->loadValues(intval($id))) return;
		} else {
			$this->primary = 'titleid';
			if (!$this->loadValues($id)){
				$this->primary = 'id_work';
				return;
			}
		}
		
        $this->unsetNew();

	}

	public function id(){
		return $this->getVar('id_work');
	}

	/**
	* @desc Incrementar el número de visitas
	*/
	public function addView(){
		$sql = "UPDATE ".$this->db->prefix("mod_works_works")." SET views=views+1 WHERE id_work='".$this->id()."'";
		if (!$this->db->queryF($sql)){
			$this->addError($this->db->error());
			return false;
		}
		return true;
	}

	public function permalink(){

		$mc = RMSettings::module_settings( 'works' );
		
		$link = XOOPS_URL.'/';
		if ($mc->permalinks){
			$link .= trim($mc->htbase, '/').'/'.$this->title_id().'/';
		} else {
			$link .= 'modules/works/index.php?p=work&amp;id='.$this->id();
		}
		
		return $link;
	}
    
    /**
     * Set multiple images for current work
     * @param array $images <p>Pair/value array containing all specified images for work.
     * All these images must be taked from RMCommon Images Manager.</p>
     */
    public function set_images( $images ){

        $this->images = array();

        foreach( $images as $image ){

            $temp = explode( "|", $image, 2 );
            $this->images[] = array(
                'title' => $temp[1],
                'url'   => $temp[0]
            );

        }

    }

    public function images(){

        if ( empty( $this->images ) )
            $this->images = Works_Functions::images( $this->id() );

        return $this->images;
    }

    /**
     * Set custom data for work
     * @param array $names All meta names
     * @param array $values All meta values
     */
    public function set_meta( $names, $values ){

        $this->meta = array();

        foreach ( $names as $id => $name ){

            $this->meta[ $name ] = $values[$id];

        }

    }

    public function get_meta( $name = '' ){

        if ( empty( $this->meta ) )
            $this->meta = Works_Functions::metas( $this->id() );

        if ( $name != '' && isset( $this->meta[ $name ] ) )
            return $this->meta[$name];
        else
            return $this->meta;

    }

	public function save(){
		if ($this->isNew()){
			$return = $this->saveToTable();
		} else {
			$return = $this->updateTable();
		}

        if ( !$return )
            return false;

        // Save images

        $this->db->queryF( "DELETE FROM " . $this->db->prefix("mod_works_images") . " WHERE work = " . $this->id() );
        $sql = '';
        foreach( $this->images as $image ){

            $sql .= "('$image[title]','$image[url]'," . $this->id() . "),";

        }

        if ( $sql != '' ){

            $sql = $sql = 'INSERT INTO ' . $this->db->prefix("mod_works_images") . " (title,image,work) VALUES " . rtrim( $sql, "," );
            $return = $this->db->queryF( $sql );

            if ( !$return )
                $this->addError( __('Images could not be saved:', 'works') . ' ' .  $this->db->error() );
        }

        // Save meta
        $this->db->queryF( "DELETE FROM " . $this->db->prefix("mod_works_meta") . " WHERE work = " . $this->id() );
        $sql = '';
        foreach( $this->meta as $name => $value ){

            $sql .= "('". MyTextSanitizer::addSlashes($name) . "','" . MyTextSanitizer::addSlashes($value) . "'," . $this->id() . "),";

        }

        if ( $sql != '' ){

            $sql = 'INSERT INTO ' . $this->db->prefix("mod_works_meta") . " (`name`,`value`,`work`) VALUES " . rtrim( $sql, "," );
            $return = $this->db->queryF( $sql );

            if ( !$return )
                $this->addError( __('Custom data could not be saved:', 'works') . ' ' .  $this->db->error() );

        }

        return $return;

	}

	public function delete(){

		$return = $this->deleteFromTable();

        if ( !$return )
            return false;

        $return = $this->db->queryF( "DELETE FROM " . $this->db->prefix("mod_works_images") . " WHERE work = " . $this->id() );
        if ( !$return )
            $this->addError( __('Images data could not be deleted:', 'works' ) . ' ' . $this->db->error() );

        $return = $this->db->queryF( "DELETE FROM " . $this->db->prefix("mod_works_meta") . " WHERE work = " . $this->id() );
        if ( !$return )
            $this->addError( __('Custom data could not be deleted:', 'works' ) . ' ' . $this->db->error() );

        $return = $this->db->queryF( "DELETE FROM " . $this->db->prefix("mod_works_categories_rel") . " WHERE work = " . $this->id() );
        if ( !$return )
            $this->addError( __('Categories relations could not be deleted:', 'works' ) . ' ' . $this->db->error() );

        return $return;

	}

}
