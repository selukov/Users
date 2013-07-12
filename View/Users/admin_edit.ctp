<?php
/**
 * Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2013, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<?php $model='User'; ?>
<div class="users form">
	<?php echo $this->Form->create($model); ?>
		<fieldset>
			<legend><?php echo __d('users', 'Edit User'); ?></legend>
			<?php
				echo $this->Form->hidden('id');
				echo $this->Form->input('username', array(
					'label' => __d('users', 'Username')));
				echo $this->Form->input('email', array(
					'label' => __d('users', 'Email')));
                if (!empty($roles)) {
                    echo $this->Form->input('role', array(
                        'label' => __d('users', 'Role'), 'values' => $roles));
                }
                echo $this->Form->input('is_admin', array(
                        'label' => __d('users', 'Is Admin'),'type' => 'checkbox'));
                    echo $this->Form->input('active', array(
                        'label' => __d('users', 'Active'),'type' => 'checkbox'));
			?>
		</fieldset>
	<?php echo $this->Form->end('Submit'); ?>
</div>
<?php echo $this->element('Users/admin_sidebar'); ?>