<?php echo $this->Form->create('Images', array('type' => 'file')); ?>
    <?php echo $this->Form->input('photo', array('type' => 'file')); ?>
	<?php echo $this->Form->input('description'); ?>
	<?php echo $this->Form->submit('Go'); ?>
<?php echo $this->Form->end(); ?>
