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
?>
<div class="users form">
	<h2><?php echo __d('users', 'Add User'); ?></h2>
	<fieldset>
		<?php
			echo $this->Form->create('User');
			echo $this->Form->input('username', array(
				'label' => __d('users', 'Username')));
			echo $this->Form->input('email', array(
				'label' => __d('users', 'E-mail (used as login)'),
				'error' => array('isValid' => __d('users', 'Must be a valid email address'),
				'isUnique' => __d('users', 'An account with that email already exists'))));
			echo $this->Form->input('password', array(
				'label' => __d('users', 'Password'),
				'type' => 'password'));
			echo $this->Form->input('temppassword', array(
				'label' => __d('users', 'Password (confirm)'),
				'type' => 'password'));
			$tosLink = $this->Html->link(__d('users', 'Terms of Service'), array('controller' => 'pages', 'action' => 'tos'));
			echo $this->Form->input('tos', array(
				'label' => __d('users', 'I have read and agreed to ') . $tosLink));
			echo $this->Form->end(__d('users', 'Submit'));
		?>
	</fieldset>
</div>
<div class="actions">
	<ul>
		<?php if (!$this->Session->read('Auth.User.id')) : ?>
			<li><?php echo $this->Html->link(__d('users', 'Login'), array('action' => 'login')); ?></li>
            <?php if (!empty($allowRegistration) && $allowRegistration)  : ?>
			<li><?php echo $this->Html->link(__d('users', 'Register an account'), array('action' => 'add')); ?></li>
            <?php endif; ?>
		<?php else : ?>
			<li><?php echo $this->Html->link(__d('users', 'Logout'), array('action' => 'logout')); ?>
			<li><?php echo $this->Html->link(__d('users', 'My Account'), array('action' => 'edit')); ?>
			<li><?php echo $this->Html->link(__d('users', 'Change password'), array('action' => 'change_password')); ?>
		<?php endif ?>
		<?php if($this->Session->read('Auth.User.is_admin')) : ?>
            <li>&nbsp;</li>
            <li><?php echo $this->Html->link(__d('users', 'List Users'), array('action'=>'index'));?></li>
        <?php endif; ?>
	</ul>
</div>