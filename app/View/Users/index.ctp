<?php 

echo $this->Form->create(null, array('action'=>'index'));

if($is_admin){
	echo $this->Html->link('Add a new user', '/users/add');

	echo "<br><br>";

	echo $this->Form->input('Users.name', array('label'=>'Name'));
	echo $this->Form->input('group_id', array('empty' => '', 'options' => $group));
	echo $this->Form->input('province_id', array('id' => 'province_id', 'empty' => '', 'options' => $province));
	echo $this->Form->input('city_id', array('id' => 'city_id', 'empty' => '', 'options' => $city));
	echo $this->Form->submit('Search');
}

echo $this->Form->end();

?>

<br>

<table class="grid">
    <tr>
		<?php if($is_admin){ ?>
      		<th style="width:100px;">Action</th>
		<?php } ?>

        <th><?php echo $this->Paginator->sort('Users.name',     'Name'); ?></th>
        <th><?php echo $this->Paginator->sort('Users.city_id',  'City'); ?></th>

		<?php if($is_admin){ ?>
        	<th><?php echo $this->Paginator->sort('Users.group_id', 'Group'); ?></th>
		<?php } ?>
    </tr>
    <?php foreach ($users as $i => $d): ?>
    <tr class="d<?php echo ($i & 1); ?>">	
		<?php if($is_admin){ ?>
		    <td>
				<div style="margin-left: 12px; margin-top: 2px;">
					<?php echo $this->Html->image('edit.png', array('class' => 'icon', 'alt'=>'Edit', 'title'=>'Edit', 'url'=> '/' . $this->params['controller'] . '/edit/' . $d['Users']['id'])); ?>
				    <?php echo $this->Html->image('remove.png', array('class' => 'icon', 'alt'=>'Delete', 'title'=>'Delete', 'url'=> '/' . $this->params['controller'] . '/delete/' . $d['Users']['id'], 'onclick' => "return confirm('Are you sure?')")); ?>
				</div>
		    </td>
		<?php } ?>

        <td><a href='/profile/<?php echo $d['Users']['id']; ?>'><?php echo $d['Users']['name']; ?></a></td>
        <td><?php echo $d['City']['name']; ?></td>

		<?php if($is_admin){ ?>
        	<td><?php echo $d['Group']['name']; ?></td>
		<?php } ?>
    </tr>
    <?php endforeach; ?>
</table>
<?php echo $this->Paginator->numbers(); ?>

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
