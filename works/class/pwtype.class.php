<?php
// $Id: pwtype.class.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------

/**
* @desc Clase para tipos de cliente
**/
class PWType extends RMObject
{

	public function __construct($id=null){
		
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("pw_types");
		$this->setNew();
		$this->initVarsFromTable();
		
		 if ($id==null) return;
        

		if (is_numeric($id)){
			
			if (!$this->loadValues($id)) return;
			$this->unsetNew();
		}else{
			$this->primary="type";
			if ($this->loadValues($id)) $this->unsetNew();
			$this->primary="id_type";
		}
		
	}	

	public function id(){
		return $this->getVar('id_type');
	}

	/**
	* @desc Tipo de cliente
	**/
	public function type(){
		return $this->getVar('type');
	}

	public function setType($type){
		return $this->setVar('type',$type);
	}

	/**
	* @desc Fecha de creación
	**/
	public function created(){
		return $this->getVar('created');
	}

	public function setCreated($created){
		return $this->setVar('created',$created);
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
