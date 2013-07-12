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
	<?php echo $this->Form->create('User', array('type' => 'file')); ?>
		<fieldset>
			<legend><?php echo __d('users', 'Edit User'); ?></legend>
			<?php
                             //   echo $user;
				echo $this->Html->image('users/image/'.$user['id'], array('height' => 250, 'pathPrefix'=>'', 'url' => array('controller' => 'users','action' => 'image', $user['id'])));
				echo $this->Form->input('loadfile',array('type' => 'file','label' =>__d('users', 'Change your image') ));
				echo $this->Form->input('firstname');
				echo $this->Form->input('lastname');
				echo $this->Form->input('birthday');
			?>
			<p>
				<?php echo $this->Html->link(__d('users', 'Change your password'), array('action' => 'change_password')); ?>
			</p>
		</fieldset>
	<?php echo $this->Form->end(__d('users', 'Submit')); ?>
</div>
<?php echo $this->element('Users/sidebar'); ?>