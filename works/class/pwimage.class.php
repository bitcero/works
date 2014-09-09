<?php
// $Id: pwimage.class.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class PWImage extends RMObject
{

	public function __construct($id=null){
		
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("pw_images");
		$this->setNew();
		$this->initVarsFromTable();
		
		 if ($id==null) return;
        
        	if (!$this->loadValues(intval($id))) return;
        
	        $this->unsetNew();
		
	}	

	public function id(){
		return $this->getVar('id_img');
	}

	/**
	* @desc Título
	**/
	public function title(){
		return $this->getVar('title');
	}

	public function setTitle($title){
		return $this->setVar('title',$title);
	}

	/**
	* @desc Descripcion
	**/
	public function desc(){
		return $this->getVar('desc');
	}

	public function setDesc($desc){
		return $this->setVar('desc',$desc);
	}

	/**
	* @desc Imagen
	**/
	public function image(){
		return $this->getVar('image');
	}

	public function setImage($image){
		return $this->setVar('image',$image);
	}

	/**
	* @desc Trabajo
	**/
	public function work(){
		return $this->getVar('work');
	}

	public function setWork($work){
		return $this->setVar('work',$work);
	}

	public function save(){
		if ($this->isNew()){
			return $this->saveToTable();
		} else {
			return $this->updateTable();
		}
	}

	public function delete(){
		//Eliminamos las imágenes
		@unlink(XOOPS_UPLOAD_PATH.'/works/'.$this->image());
		@unlink(XOOPS_UPLOAD_PATH.'/works/ths/'.$this->image());


		return $this->deleteFromTable();
	}

} 
