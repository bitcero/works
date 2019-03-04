<?php
// $Id: pwcategory.class.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class Works_Category extends RMObject
{
	public function __construct($id=null){
		
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("mod_works_categories");
		$this->setNew();
		$this->initVarsFromTable();
		if ($id==''){
			return;
		}
		
		if (is_numeric($id)){
			if ($this->loadValues($id)) $this->unsetNew();
			return;
		} else {
			$this->primary = "nameid";
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary = "id_cat";
			return;
		}
		
	}
	
	public function id(){
		return $this->getVar('id_cat');
	}
	
	/**
	* Get the category link formated
	*/
	public function permalink(){

        $mc = RMSettings::module_settings( 'works' );

		$link = XOOPS_URL.'/';
		if ( $mc->permalinks ){
			$link .= trim($mc->htbase, '/').'/category/'.$this->nameid.'/';
		} else {
			$link .= 'modules/works/index.php?p=category&amp;id='.$this->nameid;
		}
		
		return $link;
		
	}

	/**
	* @desc Obtiene el total de trabajos de la categoría
	**/
	public function works(){
	
		$sql = "SELECT COUNT(*) FROM ".$this->db->prefix('mod_works_works')." WHERE category='".$this->id()."'";
		list($num) = $this->db->fetchRow($this->db->query($sql));

		return $num;

	}
	
	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}

	public function delete(){
		return $this->deleteFromTable();
	}

}
