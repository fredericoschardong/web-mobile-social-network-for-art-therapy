<?php 

echo $this->Form->create(null, array('controller' => 'recipes'));

echo $this->Html->link('Add a new group', '/group/add');

echo "<br><br>";

echo $this->Form->input('name', array('label'=>'Name'));
echo $this->Form->submit('Search');

echo $this->Form->end();

?>

<br>

<table class="grid">
    <tr>
  		<th style="width:100px;">Action</th>
        <th><?php echo $this->Paginator->sort('Group.name',     'Name'); ?></th>
    </tr>
    <?php foreach ($group as $i => $d): ?>
    <tr class="d<?php echo ($i & 1); ?>">	
	    <td>
			<div style="margin-left: 12px; margin-top: 2px;">
				<?php echo $this->Html->image('edit.png', array('class' => 'icon', 'alt'=>'Edit', 'title'=>'Edit', 'url'=> '/' . $this->params['controller'] . '/edit/' . $d['Group']['id'])); ?>
			    <?php echo $this->Html->image('remove.png', array('class' => 'icon', 'alt'=>'Delete', 'title'=>'Delete', 'url'=> '/' . $this->params['controller'] . '/delete/' . $d['Group']['id'], 'onclick' => "return confirm('Are you sure?')")); ?>
			</div>
	    </td>

        <td><?php echo $d['Group']['name']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php echo $this->Paginator->numbers(); ?>
