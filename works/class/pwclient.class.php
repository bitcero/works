<?php
// $Id: pwclient.class.php 903 2012-01-03 07:09:43Z i.bitcero $
// --------------------------------------------------------------
// Professional Works
// Advanced Portfolio System
// Author: BitC3R0 <i.bitcero@gmail.com>
// Email: i.bitcero@gmail.com
// License: GPL 2.0
// --------------------------------------------------------------


class PWClient extends RMObject
{

	public function __construct($id=null){
		
		$this->db = XoopsDatabaseFactory::getDatabaseConnection();
		$this->_dbtable = $this->db->prefix("mod_works_clients");
		$this->setNew();
		$this->initVarsFromTable();
		
		 if ($id==null) return;
        
        	if (!$this->loadValues(intval($id))) return;
        
	        $this->unsetNew();
		
	}	

	public function id(){
		return $this->getVar('id_client');
	}

	/**
	* @desc Nombre del cliente
	**/
	public function name(){
		return $this->getVar('name');
	}

	public function setName($name){
		return $this->setVar('name',$name);
	}

	/**
	* @desc Nombre de la empresa
	**/
	public function businessName(){
		return $this->getVar('business_name');
	}

	public function setBusinessName($name){
		return $this->setVar('business_name',$name);
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
	* @desc Correo Electrónico
	**/
	public function email(){
		return $this->getVar('email');
	}

	public function setEmail($mail){
		return $this->setVar('email',$mail);
	}

	/**
	* @desc Tipo de Cliente
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

	/**
	* @desc Fecha de modificación
	**/
	public function modified(){
		return $this->getVar('modified');
	}

	public function setModified($date){
		return $this->setVar('modified',$date);
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
