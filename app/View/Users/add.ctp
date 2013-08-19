<div class="users form">
<?php echo $this->Form->create('Users', array('type' => 'file')); ?>
    <fieldset>
        <?php 
		echo $this->Html->css('../js/lightbox/jquery.lightbox-0.5.css');
		echo $this->Html->script('lightbox/jquery.lightbox-0.5.min');

		echo $this->Form->input('username');
        echo $this->Form->input('password');

		if($is_admin){
	        echo $this->Form->input('group_id', array('options' => $group));
		}

		echo $this->Form->input('name');	
		echo $this->Form->input('email', array('type' => 'email'));
		echo $this->Form->input('birthday', array( 'label' => 'Date of birth', 
		   'dateFormat' => 'DMY', 
		   'minYear' => date('Y') - 80,
		   'maxYear' => date('Y') - 5 ));

		echo $this->Form->input('sex', array(
            'options' => array('0' => 'Male', '1' => 'Female')
        ));

		echo $this->Form->input('province_id', array('id' => 'province_id', 'options' => $province, 'default' => (isset($default_province) ? $default_province : '')));
		echo $this->Form->input('city_id', array('id' => 'city_id', 'options' => $city));

		if($this->data && $this->data['Users']['photo']){?>
			<div class="imageRow">
				<div class="single">
					<a class="lightbox" href="<?php echo '/files/users/photo/' . $this->data['Users']['id'] . '/xvga_' . $this->data['Users']['photo']; ?>">
						<?php echo $this->Html->image('../files/users/photo/' . $this->data['Users']['id'] . '/thumb_' . $this->data['Users']['photo']); ?>
					</a> 
				</div>
			</div> <?php
		}

		echo $this->Form->input('photo', array('type' => 'file'));

		$a = "
			$(document).ready(function(){
				$('a.lightbox').lightBox();
			});";

		echo $this->Html->scriptblock($a);
    ?>
    </fieldset>

<?php echo $this->Form->end(__('Submit')); ?>
</div>

<?php

$this->Js->get('#province_id');
$this->Js->event('change',
$this->Js->request(array(
	'controller'=>'city',
    'action'=>'getByProvince'),
        array('async'=>true,
              'update'=>'#city_id',
              'dataExpression'=>true,
              'data'=>$this->Js->serializeForm(array('inline'=>true)),
              'method'=>'post')
     )
);
?>
