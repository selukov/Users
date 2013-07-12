<?php
/**
 * Copyright 2010 - 2011, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2011, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
App::uses('CakeDocument', 'MongoCake.Model');
App::uses('UserDetail','Users.Model');
App::uses('UserFile','Users.Model');


/** @ODM\Document */
class User extends CakeDocument {

	/** @ODM\Id */
		public $id;

	/** @ODM\String */
		public $username;

	/** @ODM\String */
		public $slug;

	/** @ODM\String */
		public $password;	

	/** @ODM\String */
		public $password_token;

	/** @ODM\String */
		public $email;

	/** @ODM\Boolean */
		public $email_verified;

	/** @ODM\String */
		public $email_token;

	/** @ODM\Date */
		public $email_token_expires;

	/** @ODM\Boolean */
		public $tos = false;

	/** @ODM\Boolean */
		public $active = false;

	/** @ODM\Date */
		public $last_login;

	/** @ODM\Date */
		public $last_action;

	/** @ODM\Boolean */
		public $is_admin = false;

	/** @ODM\String */
		public $role;

	/** @ODM\Date */	
		public $created;

	/** @ODM\Date */
		public $modified;
		
	 /** @ODM\HasOne(targetDocument="UserFile", cascade={"persist","remove"}) */
		private $file;

	/** @ODM\HasOne(targetDocument="UserDetail", cascade={"persist","remove"}) */
		private $userdetail;

	public function getUserdetail()
	{
		return $this->userdetail;
	}

	public function setUserdetail(UserDetail $userdetail)
	{
		$this->userdetail = $userdetail;
	}
	
	public function changeUserdetail($data = null)
	{
		$this->userdetail->change($data) ;
	}

	public function getPosts()
	{
		return $this->posts;
	}

	public $validationErrors = array();

	public $validationDomain = 'users';

	public $alias = 'User';

	private $temppassword;

	private $new_password;

	private $confirm_password;

	private $old_password;

	private $loadfile; // validation
	
	private $firstname; // validation

	public function __construct($username = null)
	{
		$this->username = $username;
	}

	public function getId()
	{
		return $this->id;
	}

	public function setUsername($username)
	{
		$this->username = $username;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function setSlug($slug)
	{
		$this->slug = $slug;
	}

	 public function getSlug()
	{
		return $this->slug;
	}

	public function setPassword($password)
	{
		return $this->password = $password; 
	}

	public function getPassword()
	{
		return $this->password;
	}

	public function setPassword_token($password_token)
	{
		return $this->password_token = $password_token; 
	}

	public function getPassword_token()
	{
		return $this->password_token;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail_verified($email_verified)
	{
		$this->email_verified = $email_verified;
	}

	public function getEmail_verified()
	{
		return $this->email_verified;
	}

	public function setEmail_token($email_token)
	{
		$this->email_token = $email_token;
	}

	public function getEmail_token()
	{
		return $this->email_token;
	}

	public function setEmail_token_expires($email_token_expires)
	{
		$this->email_token_expires = $email_token_expires;
	}

	public function getEmail_token_expires()
	{
		return $this->email_token_expires;
	}

	public function setTos($tos)
	{
		$this->tos = $tos;
	}

	public function getTos()
	{
		return $this->tos;
	}
	
	public function setActive($active)
	{
		$this->active = $active;
	}
	
	public function getActive()
	{
		return $this->active;
	}
	
	public function setLast_login($last_login)
	{
		$this->last_login = $last_login;
	}
	
	public function getLast_login()
	{
		return $this->last_login;
	}
	
	public function setLast_action($last_action)
	{
		$this->last_action = $last_action;
	}
	
	public function getLast_action()
	{
		return $this->last_action;
	}
	
	public function setIs_admin($is_admin)
	{
		$this->is_admin = $is_admin;
	}
	
	public function getIs_admin()
	{
		return $this->is_admin;
	}
	
	public function setRole($role)
	{
		$this->role = $role;
	}
	
	public function getRole()
	{
		return $this->role;
	}
	
	public function setCreated($created)
	{
		$this->created = $created;
	}
	
	public function getCreated()
	{
		return $this->created;
	}
	
	public function setModified($modified)
	{
		$this->modified = $modified;
	}
	
	public function getModified()
	{
		return $this->modified;
	}

	public function setTemppassword($password)
	{
		return $this->temppassword = $password; 
	}

	public function getTemppassword()
	{
		return $this->temppassword;
	}
	
	public function setNew_password($new_password)
	{
		return $this->new_password = $new_password; 
	}

	public function getNew_password()
	{
		return $this->new_password;
	}
	
	public function setConfirm_password($confirm_password)
	{
		return $this->confirm_password = $confirm_password; 
	}

	public function getConfirm_password()
	{
		return $this->confirm_password;
	}

	public function setOld_password($old_password)
	{
		return $this->old_password = $old_password; 
	}

	public function getOld_password()
	{
		return $this->old_password;
	}
	
	public function __toString()
	{
		return $this->username;
	}

	public function setFile(UserFile $file)
	{
		return $this->file = $file; 
	}

	public function getFile()
	{
		return $this->file;
	}	 
	
	public function changeFile($data = null)
	{
		$this->file->change($data) ;
	}
	
	public function setLoadfile($file)
	{
		$this->loadfile = $file;
	}
	
	public function getLoadfile()
	{
		return $this->loadfile;
	}
	
	public function validator($instance = null)
	{
		return array();
	}
	
	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;
	}
	
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
 * Validation parameters
 *
 * @var array
 */
	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'required' => true, 'allowEmpty' => false,
				'message' => 'Please enter a username.'),
			'alpha' => array(
				'rule' => array('alphaNumeric'),
				'message' => 'The username must be alphanumeric.'),
			'unique_username' => array(
				'rule'=>array('isUnique', 'username'),
				'message' => 'This username is already in use.'),
			'username_min' => array(
				'rule' => array('minLength', '3'),
				'message' => 'The username must have at least 3 characters.')),
		'email' => array(
			'isValid' => array(
				'rule' => 'email',
				'required' => true,
				'message' => 'Please enter a valid email address.'),
			'isUnique' => array(
				'rule' => array('isUnique', 'email'),
				'message' => 'This email is already in use.')),
		'password' => array(
			'too_short' => array(
				'rule' => array('minLength', '6'),
				'message' => 'The password must have at least 6 characters.'),
			'required' => array(
				'rule' => 'notEmpty',
				'message' => 'Please enter a password.')),
		'temppassword' => array(
			'rule' => 'confirmPassword',
			'message' => 'The passwords are not equal, please try again.'),
		'tos' => array(
			'rule' => array('custom','[1]'),
			'message' => 'You must agree to the terms of use.'));


	public function beforeSave()
	{
		return true;
	}

	public function isUnique($fields, $or = true)
	{	
		$fields['id !='] =$this->id;
		return !count($this->find('all', array('conditions' => $fields))); 
	}

/**
 * Verifies a users email by a token that was sent to him via email and flags the user record as active
 *
 * @param string $token The token that wa sent to the user
 * @return array On success it returns the user data record
 */
	public function verifyEmail($token = null) {
		$user = $this->find('first', array(
			'conditions' => array(
				'email_verified' => false,
				'email_token' => $token)
			));

		if (empty($user)) {
			throw new RuntimeException(__d('users', 'Invalid token, please check the email you were sent, and retry the verification link.'));
		}

		if ($user->email_token_expires < new DateTime('now')) {
			throw new RuntimeException(__d('users', 'The token has expired.'));
		}

		$data[$this->alias]['active'] = 1;
		$user->setEmail_verified(true);
		$user->setEmail_token(null);
		$user->setEmail_token_expires(null);

		$user->save(null, false);
		$user->flush();
			
		$this->data = $user;
		return $user;
	}
/**
 * Create a hash from string using given method.
 * Fallback on next available method.
 *
 * Override this method to use a different hashing method
 *
 * @param string $string String to hash
 * @param string $type Method to use (sha1/sha256/md5)
 * @param boolean $salt If true, automatically appends the application's salt
 *     value to $string (Security.salt)
 * @return string Hash
 */
	public function hash($string, $type = null, $salt = false) {
		return Security::hash($string, $type, $salt);
	}
/**
 * Generate token used by the user registration system
 *
 * @param int $length Token Length
 * @return string
 */
	public function generateToken($length = 10) {
		$possible = '0123456789abcdefghijklmnopqrstuvwxyz';
		$token = "";
		$i = 0;

		while ($i < $length) {
			$char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
			if (!stristr($token, $char)) {
				$token .= $char;
				$i++;
			}
		}
		return $token;
	}
/**
 * Custom validation method to ensure that the two entered passwords match
 *
 * @param string $password Password
 * @return boolean Success
 */
	public function confirmPassword($password = null) {
		if ((isset($this->password) && isset($password['temppassword']))
			&& !empty($password['temppassword'])
			&& ($this->password === $password['temppassword'])) {
			return true;
		}
		return false;
	}
/**
 * Optional data manipulation before the registration record is saved
 *
 * @param array post data array
 * @param boolean Use email generation, create token, default true
 * @return array
 */
	protected function _beforeRegistration($postData = array(), $useEmailVerification = true){
		if($useEmailVerification == true){
			$postData[$this->alias]['email_token'] = $this->generateToken();
			$postData[$this->alias]['email_token_expires'] = date('Y-m-d H:i:s', time() + 86400);
			$postData[$this->alias]['email_verified'] = 0;
		}
		else{
			$postData[$this->alias]['email_verified'] = 1;
		}

		$postData[$this->alias]['active'] = 1;
		$defaultRole = Configure::read('Users.defaultRole');
		  if($defaultRole){
			 $postData[$this->alias]['role'] = $defaultRole;
		  }
		  else{
			 $postData[$this->alias]['role'] = 'registered';
		  }

		return $postData;
	}


/**
 * Registers a new user
 *
 * Options:
 * - bool emailVerification : Default is true, generates the token for email verification
 * - bool removeExpiredRegistrations : Default is true, removes expired registrations to do cleanup when no cron is configured for that
 * - bool returnData : Default is true, if false the method returns true/false the data is always available through $this->User->data
 *
 * @param array $postData Post data from controller
 * @param mixed should be array now but can be boolean for emailVerification because of backward compatibility
 * @return mixed
 */
	public function register($postData = array(), $options = array()) {
		if (is_bool($options)) {
			$options = array('emailVerification' => $options);
		}

		$defaults = array(
			'emailVerification' => true,
			'removeExpiredRegistrations' => true,
			'returnData' => true);
		extract(array_merge($defaults, $options));

		$postData = $this->_beforeRegistration($postData, $emailVerification);

		if ($removeExpiredRegistrations) {
			$this->_removeExpiredRegistrations();
		}
		

		if ($this->save($postData)) {
			$postData[$this->alias]['password'] = $this->hash($postData[$this->alias]['password'], 'sha1', true);
			$this->setPassword($postData[$this->alias]['password']);
			$this->save(null,false);
			$this->flush();
			return true;
		}
		return false;
	}
/**
 * Checks if an email is in the system, validated and if the user is active so that the user is allowed to reste his password
 *
 * @param array $postData post data from controller
 * @return mixed False or user data as array on success
 */
	public function passwordReset($postData = array()) {
		$this->recursive = -1;
		$user = $this->find('first', array(
			'conditions' => array(
						'active' => true,
						'email' => $postData[$this->alias]['email'])));

		if (!empty($user) && $user->getEmail_verified() == true) {
			$sixtyMins = time() + 43000;
			$token = $this->generateToken();
			$user->setPassword_token($token);
			$user->setEmail_token_expires(date('Y-m-d H:i:s', $sixtyMins));
			$user->save(null, false);
			$user->flush();
			return $user;
		} elseif (!empty($user) && $user->getEmail_verified() == false){
			$this->invalidate('email', __d('users', 'This Email Address exists but was never validated.'));
		} else {
			$this->invalidate('email', __d('users', 'This Email Address does not exist in the system.'));
		}

		return false;
	}

/**
 * Resets the password
 * 
 * @param array $postData Post data from controller
 * @return boolean True on success
 */
	public function resetPassword($postData = array()) {
		$result = false;

		$tmp = $this->validate;
		$this->validate = array(
			'new_password' => $tmp['password'],
			'confirm_password' => array(
				'required' => array(
					'rule' => array('compareFields', 'new_password', 'confirm_password'), 
					'message' => __d('users', 'The passwords are not equal.'))));

		if ($this->save($postData)) {
			$postData[$this->alias]['password'] = $this->hash($postData[$this->alias]['new_password'], 'sha1', true);
			$this->setPassword($postData[$this->alias]['password']);
			$this->setPassword_token(null);
			$this->save(null, false);
			$this->flush();
		}

		$this->validate = $tmp;
		return $result;
	}
		
/**
 * Checks the token for a password change
 * 
 * @param string $token Token
 * @return mixed False or user data as array
 */
	public function checkPasswordToken($token = null) {
		$user = $this->find('first', array(
			'conditions' => array(
				'active' => true,
				'password_token' => $token,
				'email_token_expires >=' => new DateTime('now'))));
		if (empty($user)) {
			return false;
		}
		return $user;
	}
	
/**
 * Validation method to compare two fields
 *
 * @param mixed $field1 Array or string, if array the first key is used as fieldname
 * @param string $field2 Second fieldname
 * @return boolean True on success
 */
	public function compareFields($field1, $field2) {
		if (is_array($field1)) {
			$field1 = key($field1);
		}
		if (isset($this->{$field1}) && isset($this->{$field2}) && 
			$this->{$field1} == $this->{$field2}) {
			return true;
		}
		return false;
	}

/**
 * Changes the password for a user
 *
 * @param array $postData Post data from controller
 * @return boolean True on success
 */
	public function changePassword($postData = array()) {
	
		$tmp = $this->validate;
		$this->validate = array(
			'new_password' => $this->validate['password'],
			'confirm_password' => array(
				'required' => array('rule' => array('compareFields', 'new_password', 'confirm_password'), 'required' => true, 'message' => __d('users', 'The passwords are not equal.'))),
			'old_password' => array(
				'to_short' => array('rule' => 'validateOldPassword', 'required' => true, 'message' => __d('users', 'Invalid password.'))));

		if ($this->save($postData)) {
			$postData[$this->alias]['password'] = $this->hash($postData[$this->alias]['new_password'], 'sha1', true);
			$this->setPassword($postData[$this->alias]['password']);
			$result = $this->save(null, false);
			if($result)$this->flush();
			$this->validate = $tmp;
			return true;
		}
		$this->validate = $tmp;
		return false;
	}
/**
 * Validation method to check the old password
 *
 * @param array $password 
 * @return boolean True on success
 */
	public function validateOldPassword($password) {
		if (!isset($this->id) || empty($this->id)) {
			if (Configure::read('debug') > 0) {
				throw new OutOfBoundsException(__d('users', '$this->data[\'' . $this->alias . '\'][\'id\'] has to be set and not empty'));
			}
		}

		$currentPassword = $this->getPassword();
		return $currentPassword === $this->hash($password['old_password'], 'sha1', true);
	}



/**
 * Removes all users from the user table that are outdated
 *
 * Override it as needed for your specific project
 *
 * @return void
 */
	public function _removeExpiredRegistrations() {

		$this->getDocumentManager()->createQueryBuilder('User')->remove()
			->field('email_verified')->equals(false)
			->field('email_token_expires')->lt(new DateTime('now'))
			->getQuery()->execute();
	//	$this->flush();
	
	//	$this->deleteAll(array(
	//		$this->alias . '.email_verified' => 0,
	//		$this->alias . '.email_token_expires <' => date('Y-m-d H:i:s')));
	}
	
	public function changeBSON($postData = array()){
		$tmp = $this->validate;
		$this->validate = array(
			'loadfile' => array(
				'type' => array(
					'rule' => array('mimeType', array('image/gif', 'image/jpeg', 'image/png', 'image/pjpeg' )),
					'message' => __d('users', 'Invalid mime type.')),
				'fileSize' => array(
					'rule' => array('fileSize', '<=', '300KB'),
					'message' => __d('users','Image must be less than 300KB')),
				'extension' => array(
					'rule' => array('extension', array('gif', 'jpeg', 'png', 'jpg')),
					'message' =>__d('users', 'Please supply a valid image.'))),
			 'firstname' => array(
			 	'required' => array(
			 		'rule' => array('notEmpty'),
			 		'required' => true, 'allowEmpty' => false,
			 		'message' => 'Please enter a username.'),
			 	'alpha' => array(
					'rule' => array('alphaNumeric'),
					'message' => 'The username must be alphanumeric.'),
			 	'username_min' => array(
					'rule' => array('minLength', '3'),
					'message' => 'The username must have at least 3 characters.')));
		
		if($postData['User']['loadfile']['error'] !== UPLOAD_ERR_OK) {
				unset ($this->validate['loadfile']);
				unset ($postData['User']['loadfile']);
		}

		if ($this->save($postData)) {
				if(!empty($this->userdetail))$this->changeUserdetail($postData['User']);
					else $this->setUserdetail(new UserDetail($postData['User']));
			
			 	if(!empty($postData['User']['loadfile']['tmp_name'])) {
					 if(!empty($this->file))$this->changeFile($postData['User']['loadfile']);
						else $this->setFile(new UserFile ($postData['User']['loadfile']));
				}
		
		  $this->validate = $tmp;
		  return true;
		  }
	$this->validate = $tmp;
	return false;
	 }
	 
/**
 * Adds a new user
 * 
 * @param array post data, should be Controller->data
 * @return boolean True if the data was saved successfully.
 */
	public function add($postData = null) {
		if (!empty($postData)) {
            $this->set($postData);
            if ($this->validates()) {
                if (empty($postData[$this->alias]['role'])) {
                    if (empty($postData[$this->alias]['is_admin'])) {
                        $defaultRole = Configure::read('Users.defaultRole');
                        if ($defaultRole) {
                            $postData[$this->alias]['role'] = $defaultRole;
                        } else {
                            $postData[$this->alias]['role'] = 'registered';
                        }
                    } else {
                        $postData[$this->alias]['role'] = 'admin';
                    }
                }
                $postData[$this->alias]['password'] = $this->hash($postData[$this->alias]['password'], 'sha1', true);
                $this->create();
                $result = $this->save($postData, false);
                if ($result) {
		     $this->flush();			
                    return true;
                }
            }
		}
		return false;
	}
	
/**
 * Edits an existing user
 *
 * @param array $postData controller post data usually $this->data
 * @return mixed True on successfully save else post data as array
 */
	public function edit($postData = null) {

		if (!empty($postData)) {
			$result = $this->save($postData);
			if ($result) {
				//$this->data = $result;
				return true;
			} else {
				return $postData;
			}
		}
	}

}
?>