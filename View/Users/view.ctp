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
<div class="users view">
<h2><?php echo __d('users', 'User'); ?></h2>
	<dl><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class; ?>><?php echo __d('users', 'Username'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class; ?>>
			<?php echo $user['User']['username']; ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class; ?>><?php echo __d('users', 'Created'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class; ?>>
			<?php echo $user['User']['created']->format('Y-m-d H:i:s'); ?>
			&nbsp;
		</dd>
		<dt<?php if ($i % 2 == 0) echo $class; ?>><?php echo __d('users', 'Image changed'); ?></dt>
		<dd<?php if ($i++ % 2 == 0) echo $class; ?>>
			<?php echo $this->Time->timeAgoInWords($user['User']['file']['uploadDate'], array('format' => 'F jS, Y', 'end' => '+1 year')); ?>
			&nbsp;
		</dd>
		<?php 
		echo $this->Html->image('users/image/'.$user['id'], array('height' => 250, 'pathPrefix'=>'', 'url' => array('controller' => 'users','action' => 'image', $user['id'])));
		?>
	</dl>
</div>
<?php echo $this->element('Users/sidebar'); ?>