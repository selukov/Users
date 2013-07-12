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
App::uses('User','Users.Model');


/** @ODM\Document */
class UserDetail extends CakeDocument{

	 /**  @ODM\Id(strategy="AUTO") */
		public $id;

	/** @ODM\Field(type="string") */
		public $firstname;

	/** @ODM\Field(type="string") */
		public $lastname;
	
	/** @ODM\Date */
		private $birthday;
	
	public function __construct($data)
	{	
		if(empty($data['birthday']))unset($data['birthday']);
		$this->set($data);
	}
	
	public function change($data)
	{	
		if(empty($data['birthday']))unset($data['birthday']);
		$this->set($data);
	}

	public function getId()
	{
		return $this->id;
	}

	public function getFirstname()
	{
		return $this->firstname;
	}

	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;
	}

	public function getLastname()
	{
		return $this->lastname;
	}

	public function setLastname($lastname)
	{
		$this->lastname = $lastname;
	}
	
	public function setBirthday($birthday)
	{
		$this->birthday = $birthday;
	}

	public function getBirthday()
	{
	//	if ($this->birthday)return $this->birthday->format('D, d M Y H:i');else return null;
		return ($this->birthday)?$this->birthday->format('D, d M Y H:i'):null;
	}

}

?>
