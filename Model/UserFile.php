<?php
/**
 * Users Plugin for MongoCake
 * 
 * @copyright		Copyright © 2013 Selukov Andrey (http://selukov.me)
 * @link 			https://github.com/selukov/Users
 * @license			MIT License
 */

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
App::uses('CakeDocument', 'MongoCake.Model');

/** @ODM\Document */
class UserFile extends CakeDocument {

	 /** @ODM\Id(strategy="AUTO")  */
	 private $id;
	 
	 /**  @ODM\Field(type="string")  */
	 private $name;

	 /** @ODM\File */
	 private $file;

	 /** @ODM\Field(type="string")  */
	private $type;
	
	/** @ODM\Date */
	private $uploadDate;
	
	public function __construct($data)
	{	
		$this->name = $data['name'];
		$this->file = $data['tmp_name'];
		$this->type = $data['type'];
	}
	
	public function change($data)
	{
		$this->name = $data['name'];
		$this->file = $data['tmp_name'];
		$this->type = $data['type'];  
	}
	
	public function getId()
	{
		return $this->id;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getFile()
	{
		return $this->file;
	}

	public function setFile($file)
	{
		$this->file = $file;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function getUploadDate()
	{
		return $this->uploadDate;
	}
}

?>
