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

App::uses('AppController', 'Controller');


/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends UsersAppController {

	public $components = array('Cookie','Session','Paginator' => array ('className'=>'MongoCake.DocumentPaginator'),'Security');
	
/**
 * index method
 *
 * @return void
 */
	public function index() {
	
		$this->Paginator->settings = array(
			'limit' => 10,
			'conditions' => array(
				'active' => true, 
				'email_verified' => true));
		$this->set('users', $this->paginate());
	}

/**
 * view method
 *
 * @param string $id
 * @return void
 */
	public function view($id = null) {
	//	$user = $this->User->getDocumentManager()->createQueryBuilder('User')->getQuery()->getSingleResult();
		$user = $this->User->find($id);
			
	//	$rrr1 = $user->posts[0]['name'];
		if (!$user) {
			throw new NotFoundException(__('Invalid user'));
		}
		$this->set(compact('user'));
	}
/**
 * Sends the verification email
 *
 * This method is protected and not private so that classes that inherit this
 * controller can override this method to change the varification mail sending
 * in any possible way.
 *
 * @param string $to Receiver email address
 * @param array $options EmailComponent options
 * @return boolean Success
 */
	protected function _sendVerificationEmail($userData, $options = array()) {
		$defaults = array(
			'from' => Configure::read('App.defaultEmail'),
			'subject' => __d('users', 'Account verification'),
			'template' => 'Users.account_verification',
			'layout'=> 'default');

		$options = array_merge($defaults, $options);

		$Email = $this->_getMailInstance();
		$Email->to($userData->getEmail())
			->from($options['from'])
			->subject($options['subject'])
			->template($options['template'], $options['layout'])
			->viewVars(array(
			'model' => $this->modelClass,
				'user' => $userData))
			->send();
	}

/**
 * beforeFilter callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->_setupAuth();

	//	$this->set('model', $this->modelClass);

		if (!Configure::read('App.defaultEmail')) {
			Configure::write('App.defaultEmail', 'noreply@' . env('HTTP_HOST'));
		}
	}
	/**
 * Setup Authentication Component
 *
 * @return void
 */
	protected function _setupAuth() {
		$this->Auth->allow('add', 'reset', 'verify', 'logout', 'view', 'reset_password', 'login','test');
		if (!is_null(Configure::read('Users.allowRegistration')) && !Configure::read('Users.allowRegistration')) {
			$this->Auth->deny('add');
		}
		if ($this->request->action == 'register') {
			$this->Components->disable('Auth');
		}

		$this->Auth->authenticate = array(
			'Form' => array(
				'fields' => array(
					'username' => 'email',
					'password' => 'password'),
				'userModel' => 'Users.User', 
				'scope' => array(
					'User.active' => true,
					'User.email_verified' => true))); 


		$this->Cookie->name = 'rememberMe';
		$this->Auth->logoutRedirect = array('admin' =>false, 'controller' => 'users', 'action' => 'login');
//		$this->Auth->logoutRedirect = array('plugin' =>null, 'controller' => 'posts', 'action' => 'index');
		$this->Auth->loginAction = array('admin' => false,  'controller' => 'users', 'action' => 'login');
	}	
/**
 * Returns a CakeEmail object
 *
 * @return object CakeEmail instance
 * @link http://book.cakephp.org/2.0/en/core-utility-libraries/email.html
 */
	protected function _getMailInstance() {
		App::uses('CakeEmail', 'Network/Email');
		$emailConfig = Configure::read('Users.emailConfig');
		if ($emailConfig) {
			return new CakeEmail($emailConfig);
		} else {
			return new CakeEmail('default');
		}
	}
/**
 * Sets the cookie to remember the user
 *
 * @param array Cookie component properties as array, like array('domain' => 'yourdomain.com')
 * @param string Cookie data keyname for the userdata, its default is "User". This is set to User and NOT using the model alias to make sure it works with different apps with different user models across different (sub)domains.
 * @return void
 * @link http://book.cakephp.org/2.0/en/core-libraries/components/cookie.html
 */
	protected function _setCookie($options = array(), $cookieKey = 'User') {
		if (empty($this->request->data[$this->modelClass]['remember_me'])) {
			$this->Cookie->delete($cookieKey);
		} else {
			$validProperties = array('domain', 'key', 'name', 'path', 'secure', 'time');
			$defaults = array(
				'name' => 'rememberMe');

			$options = array_merge($defaults, $options);
			foreach ($options as $key => $value) {
				if (in_array($key, $validProperties)) {
					$this->Cookie->{$key} = $value;
				}
			}

			$cookieData = array(
				'email' => $this->request->data[$this->modelClass]['email'],
				'password' => $this->request->data[$this->modelClass]['password']);
			$this->Cookie->write($cookieKey, $cookieData, true, '1 Month');
		}
		unset($this->request->data[$this->modelClass]['remember_me']);
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->Auth->user()) {
			$this->Session->setFlash(__d('users', 'You are already registered and logged in!'));
			$this->redirect('/');
		} 
		ClassRegistry::addObject('User', $this->User);		
		if (!empty($this->request->data)) {
			$user = $this->User->register($this->request->data);
			if ($user !== false) {
				$this->_sendVerificationEmail($this->User);
				$this->Session->setFlash(__d('users', 'Your account has been created. You should receive an e-mail shortly to authenticate your account. Once validated you will be able to login.'));
				$this->redirect(array('action' => 'login'));
			} else {
				unset($this->request->data[$this->modelClass]['password']);
				unset($this->request->data[$this->modelClass]['temppassword']);
				$this->Session->setFlash(__d('users', 'Your account could not be created. Please, try again.'), 'default', array('class' => 'message warning'));
			}
		}
	}
/**
 * Confirm email action
 *
 * @param string $type Type, deprecated, will be removed. Its just still there for a smooth transistion.
 * @param string $token Token
 * @return void
 */
	public function verify($type = 'email', $token = null) {
		if ($type == 'reset') {
			// Backward compatiblity
			$this->request_new_password($token);
		}

		try {
			$this->User->verifyEmail($token);
			$this->Session->setFlash(__d('users', 'Your e-mail has been validated!'));
			return $this->redirect(array('action' => 'login'));
		} catch (RuntimeException $e) {
			$this->Session->setFlash($e->getMessage());
			return $this->redirect('/');
		}
	}
/**
 * edit method
 *
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$user = $this->User->find($this->Auth->user('id'));
		
		if (!$user) {
			throw new NotFoundException(__d('users','Invalid user'));
		}
                	$this->User = $user;

		if ($this->request->is('post') || $this->request->is('put')) {
                        ClassRegistry::addObject('User', $this->User);

			if ($user->changeBSON($this->request->data)) {
				$this->Session->setFlash(__d('users', 'Profile saved.'));
				$this->User->save(null,false);
				$this->User->flush();
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__d('users', 'Could not save your profile.'));			
			}
		} 
		$this->request->data['User']['firstname'] = $user['userdetail']['firstname'];
		$this->request->data['User']['lastname'] = $user['userdetail']['lastname'];
		$this->request->data['User']['birthday'] = $user['userdetail']['birthday'];
		//$this->request->data['User'] = (array)$user['userdetail'];
		$this->set(compact('user'));		
	}
/**
 * image method
 *
 * @param string $id
 * @return void
 */
   public function image($id = null){
		$user = $this->User->find($id);
		if (!$user) {
			throw new NotFoundException(__d('users','Invalid photo'));
		}
		
		$Image = $user->getFile();
		
		$date = $Image->getUploadDate()->format('D, d M Y H:i:s').' GMT';
		if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !strcmp($_SERVER['HTTP_IF_MODIFIED_SINCE'],$date)) {
	// Client's cache IS current, so we just respond '304 Not Modified'.
		header('Last-Modified: '.$date, true, 304);
		header ('Cache-Control:');
		$this->_stop();
		}
		else{
		header('Last-Modified: '.$date, true, 200);
		header('Content-Length: '.$Image->getFile()->getSize());
		header('Content-type: '.$Image['type']);
		header ('Cache-Control:');
		echo $Image ->getFile()->getBytes();
		$this->_stop();
		}
	}
   
   
   
   
/**
 * Delete a user account
 *
 * @param string $userId User ID
 * @return void
 */
	public function admin_delete($id = null) {
		//$user = $this->User->find($this->Auth->user('id'));
		$user = $this->User->find($id);

		if (!$user) {
			throw new NotFoundException(__d('users','Invalid user'));
		} 
		
		if ($user->delete()) {
			$this->Session->setFlash(__d('users','User deleted'));
			$this->User->flush();
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__d('users','User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
/**
 * Admin edit
 *
 * @param string $id User ID
 * @return void
 */
	public function admin_edit($userId = null) {
	  
		if ($this->request->is('post') || $this->request->is('put')) {
			$user =$this->User->find($userId);
			
			if (!$user) {
			throw new NotFoundException(__d('users','Invalid user'));
			 } 
			 $this->User = $user;
			 ClassRegistry::addObject('User', $this->User);
			
			$result = $this->User->edit($this->request->data);
			if ($result === true) {
				$this->Session->setFlash(__d('users', 'User saved'));
				$this->User->flush();
				$this->redirect(array('action' => 'index'));
			} else {
				$this->request->data = $result;
			}
		} 
		
		if (empty($this->request->data)) {
			// ClassRegistry::addObject('User', $this->User);
			$this->request->data['User'] = (array)$this->User->find($userId);
		}
		$this->set('roles', Configure::read('Users.roles'));
	}

/**
 * Admin view
 *
 * @param string $id User ID
 * @return void
 */
	public function admin_view($id = null) {	  
		if (!$id) {
			$this->Session->setFlash(__d('users', 'Invalid User.'));
			$this->redirect(array('action' => 'index'));
		}	
		$user = $this->User->find($id);
		if (!$user) {
			throw new NotFoundException(__d('users','Invalid user'));
		} 
		$this->set(compact('user'));
	}

/**
 * Common login action
 *
 * @return void
 */
	public function login() {
		if ($this->request->is('post')) {
			if ($this->Auth->login()) {
				$this->User->getDocumentManager()->clear();
				$user = $this->User->find('first', array ('conditions' =>array ('email' => $this->Auth->user('email'))));
				if (!$user) {
						throw new NotFoundException(__d('users','Invalid user'));
				} 
				$user->setLast_login(new DateTime('now'));
				if($user->save(null,false))$user->flush();
				
				if ($this->here == $this->Auth->loginRedirect) {
					$this->Auth->loginRedirect = '/';
				}
				
				$this->Session->setFlash(sprintf(__d('users', '%s you have successfully logged in'), $this->Auth->user('username')));
				if (!empty($this->request->data)) {
					$data = $this->request->data[$this->modelClass];
					$this->_setCookie();
				}
				
				if (empty($data['return_to'])) {
					$data['return_to'] = null;
				}

				$this->redirect($this->Auth->redirect($data['return_to']));
				
			} else {
				$this->Auth->flash(__d('users', 'Invalid e-mail / password combination.  Please try again'));
			}
		}	
		if (isset($this->request->params['named']['return_to'])) {
			$this->set('return_to', urldecode($this->request->params['named']['return_to']));
		} else {
			$this->set('return_to', false);
		}
		$allowRegistration = Configure::read('Users.allowRegistration');
		$this->set('allowRegistration', (is_null($allowRegistration) ? true : $allowRegistration));
	}
	
/**
 * Reset Password Action
 *
 * Handles the trigger of the reset, also takes the token, validates it and let the user enter
 * a new password.
 *
 * @param string $token Token
 * @param string $user User Data
 * @return void
 */
	public function reset_password($token = null, $user = null) {
		if (empty($token)) {
			$admin = false;
			if ($user) {
				$this->request->data = $user;
				$admin = true;
			}
			$this->_sendPasswordReset($admin);
		} else {
			$this->_resetPassword($token);
		}
	}

/**
 * This method allows the user to change his password if the reset token is correct
 *
 * @param string $token Token
 * @return void
 */
	protected function _resetPassword($token) {
		$user = $this->User->checkPasswordToken($token);
		if (empty($user)) {
			$this->Session->setFlash(__d('users', 'Invalid password reset token, try again.'));
			$this->redirect(array('action' => 'reset_password'));
		}
		else {
			$this->User = $user;
			ClassRegistry::addObject('User', $this->User);
		}

		if (!empty($this->request->data) && $this->User->resetPassword($this->request->data)) {
			$this->Session->setFlash(__d('users', 'Password changed, you can now login with your new password.'));
			$this->redirect($this->Auth->loginAction);
		}

		$this->set('token', $token);
	}
/**
 * Checks if the email is in the system and authenticated, if yes create the token
 * save it and send the user an email
 *
 * @param boolean $admin Admin boolean
 * @param array $options Options
 * @return void
 */
 
	protected function _sendPasswordReset($admin = null, $options = array()) {
		$defaults = array(
			'from' => Configure::read('App.defaultEmail'),
			'subject' => __d('users', 'Password Reset'),
			'template' => 'Users.password_reset_request',
			'layout'=> 'default');

		$options = array_merge($defaults, $options);
	
		if (!empty($this->request->data)) {
			$user = $this->User->passwordReset($this->request->data);
		
			if (!empty($user)) {

				$Email = $this->_getMailInstance();
				$Email->to($user->getEmail())
					->from($options['from'])
					->subject($options['subject'])
					->template($options['template'], $options['layout'])
					->viewVars(array(
					'model' => $this->modelClass,
					'user' => $user,
						'token' => $user->getPassword_token()))
					->send();

				if ($admin) {
					$this->Session->setFlash(sprintf(
						__d('users', '%s has been sent an email with instruction to reset their password.'),
						$user[$this->modelClass]['email']));
					$this->redirect(array('action' => 'index', 'admin' => true));
				} else {
					$this->Session->setFlash(__d('users', 'You should receive an email with further instructions shortly'));
					$this->redirect(array('action' => 'login'));
				}
			} else {
				$this->Session->setFlash(__d('users', 'No user was found with that email.'));
				$this->redirect($this->referer('/'));
			}
		}
		$this->render('request_password_change');
	}

/**
 * Allows the user to enter a new password, it needs to be confirmed by entering the old password
 *
 * @return void
 */
	public function change_password() {
		if ($this->request->is('post')) {
			$this->request->data[$this->modelClass]['id'] = $this->Auth->user('id');
			$user = $this->User->find($this->Auth->user('id'));
			$this->User = $user;
			ClassRegistry::addObject('User', $this->User);
			if ($this->User->changePassword($this->request->data)) {
				$this->Session->setFlash(__d('users', 'Password changed.'));
				$this->redirect('/');
			}
		}
	}

/**
 * Common logout action
 *
 * @return void
 */
	public function logout() {
		$user = $this->Auth->user();
		$this->Session->destroy();
		$this->Cookie->destroy();
		$this->Session->setFlash(sprintf(__d('users', '%s you have successfully logged out'), $user['username']));
		$this->redirect($this->Auth->logout());
	}
	
/**
 * Admin Index
 *
 * @return void
 */
	public function admin_index() {
		unset ($this->User->validate['username']);
		unset ($this->User->validate['email']);
		$email =null;
		$username =null;
		
		if ($this->request->is('post') || $this->request->is('put')) {
		  $email =$this->request->data[$this->modelClass]['email'];
		  $username =$this->request->data[$this->modelClass]['username'];
		  $this->request->params['named']['username'] =$username;
		  $this->request->params['named']['email'] =$email;
		}
		if (!empty($this->request->params['named']['username'])||!empty($this->request->params['named']['email'])){
		  $username = $this->request->params['named']['username'];
		  $email = $this->request->params['named']['email'];
		}
		$this->Paginator->settings = array(
			'limit' => 10,
			 'conditions' => array('username' => new \MongoRegex("/$username/"),
									 'email' => new \MongoRegex("/$email/")),
			 'order' => array(
            			'created' => 'desc')
			 );
		//$user  = $this->User->getDocumentManager()->createQueryBuilder()->field('username')->equals('andrew');
	//	$user  =$this->User->getDocumentManager()->createQueryBuilder('User')->field('username')->equals(new \MongoRegex('/^and/'))
	//			  ->field('email')->equals(new \MongoRegex('/and/'))->getQuery()->execute();
		$this->request->data['User']['email'] = $email;
		$this->request->data['User']['username'] = $username;
		$this->set('users', $this->paginate());
		//$this->render('temp');
	}

/**
 * Admin add
 *
 * @return void
 */
	public function admin_add() {
		if (!empty($this->request->data)) {
			$this->request->data['User']['tos'] = true;
			$this->request->data['User']['email_verified'] = true;
			ClassRegistry::addObject('User', $this->User);
			
			if ($this->User->add($this->request->data)) {
				$this->Session->setFlash(__d('users', 'The User has been saved'));
				$this->redirect(array('action' => 'index'));
			}
		}
		$this->set('roles', Configure::read('Users.roles'));
	}

}

?>