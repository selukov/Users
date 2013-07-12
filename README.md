Users Plugin for CakePHP 2.x using MongoCake
----------------------------------

The users plugin is for allowing:
* User registration (Enable by default)
* Account verification by a token sent via email
* User login (email / password)
* User can set a photo. <300KB (The default size is limited by the rules of the validation) 
* User search (powerful search  with regular expressions).
* User management using the "admin" section (add / edit / delete).

The default password reset process requires the user to enter his email address, an email is sent to the user with a link and a token. When the user accesses the URL with the token he can enter a new password.

Requirements
----------------
* CakePHP v2.x
* [MongoCake Plugin](https://github.com/lorenzo/MongoCake)

Installation
-------------
First of all you must install and configure [MongoCake Plugin](https://github.com/lorenzo/MongoCake).

Clone

	$ cd /your_app_path/Plugin
	$ git clone git://github.com/selukov/Users.git

Enable plugin
----------------

You need to enable the plugin your app/Config/bootstrap.php file:

CakePlugin::load('Users', array('routes' => true)); 

For activate authentication layer open app/Controller/AppController.php and add following:

	public $components = array(
		//'DebugKit.Toolbar' => array('panels' => array('history' => false)), //setting for DebugKit
		'Auth'
		);

A little change (patch)
--------------------------
The patch next file

(your_CakePHP_path)/lib/Cake/Controller/Component/Auth/BaseAuthenticate.php

function _findUser($conditions, $password = null)

this string

	return array_merge($user,$result);
to

	return array_merge((array)$user, (array) $result);

if you don't this patch you will receive this error when try to login on

	 array_merge(): Argument #1 is not an array [CORE/Cake/Controller/Component/Auth/BaseAuthenticate.php, line 112]


Other setting
---------------
Email configuration

The plugin uses the $default email configuration (should be present in your Config/email.php file) For more information see [Email](http://book.cakephp.org/2.0/en/core-utility-libraries/email.html#configuration)

I used the following configuration for CakePHP 2.3.7 

	class EmailConfig {
		public $default = array(
			'host' => 'ssl://smtp.gmail.com',
			'port' => 465,
			'username' => 'xxxxxxxx@gmail.com',
			'password' => 'xxxxxxxx',
			'transport' => 'Smtp',
			'tls' => false
		);
	}

Enable admin prefixed routes
In app/Config/core.php
Configure::write('Routing.prefixes', array('admin'));


## License ##

Copyright 2009-2012, [Cake Development Corporation](http://cakedc.com)

Licensed under [The MIT License](http://www.opensource.org/licenses/mit-license.php)<br/>
Redistributions of files must retain the above copyright notice.

## Copyright ###

Copyright 2009-2012<br/>
[Cake Development Corporation](http://cakedc.com)<br/>
1785 E. Sahara Avenue, Suite 490-423<br/>
Las Vegas, Nevada 89104<br/>
http://cakedc.com<br/>
