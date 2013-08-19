<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'Cool App');
?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('screen', 'stylesheet', array('media' => 'screen'));

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
	<link href='http://fonts.googleapis.com/css?family=Fredoka+One|Open+Sans:400,700' rel='stylesheet' type='text/css'>
</head>
<body>
	<?php
	echo $this->html->script('jquery-1.9.1.min.js');  

	if (class_exists('JsHelper') && method_exists($this->Js, 'writeBuffer')) echo $this->Js->writeBuffer();
	// Writes cached scripts
	?>
	<?php echo $this->Session->flash(); ?>
	<script>
		var myMessages = ['info','warning','error','success'];

		$(document).ready(function(){
			function hideAllMessages(){
				 var messagesHeights = new Array(); // this array will store height for each
			 
				 for(i=0; i<myMessages.length; i++){
					  messagesHeights[i] = $('.' + myMessages[i]).outerHeight(); // fill array
					  $('.' + myMessages[i]).css('top', -messagesHeights[i]); //move element outside viewport	  
				 }
			}

			function showMessage(type){
				$('.'+ type +'-trigger').click(function(){
					hideAllMessages();				  
					$('.'+type).animate({top:"0"}, 500);
				});
			}

			// Initially, hide them all
			hideAllMessages();

			// Show message
			for(var i=0;i<myMessages.length;i++){
				showMessage(myMessages[i]);
			}

			// When message is clicked, hide it
			$('.message').click(function(){			  
				$(this).animate({top: -$(this).outerHeight()}, 500);
			});	
		});
	</script>

	<div class="info message">
		 FYI, something just happened!
		 <p>This is just an info notification message.</p>
	</div>

	<div class="error message">
		 Ups, an error ocurred
		 <p>This is just an error notification message.</p>
	</div>

	<div class="warning message">
		 Wait, I must warn you!
		 <p>This is just a warning notification message.</p>
	</div>

	<div class="success message">
		 Congrats, you did it!
		 <p>This is just a success notification message.</p>
	</div>

	<div id="sidebar">
		<h1 class="logo">
			<a href="/">Cool App</a>
		</h1>
			
		<?php if($this->Session->read('Auth.User.id')){ ?>
			<p class="author">
				<?php 
					if($this->Session->read('Auth.User')) {
						echo 'Hi, ' . $this->Session->read('Auth.User.name');
					}
				?> 
			</p>

			<div class="imageRow">
				<div class="single">
					<a class="lightbox" href="<?php echo '/files/users/photo/' . $this->Session->read('Auth.User.id') . '/xvga_' . $this->Session->read('Auth.User.photo'); ?>">
						<?php echo $this->Html->image('../files/users/photo/' . $this->Session->read('Auth.User.id') . '/thumb_' . $this->Session->read('Auth.User.photo'), array('style' => 'width:180px;')); ?>
					</a> 
				</div>
			</div> 

			<ul id="nav">
				<li><a href="/" class="first">Home</a></li>
				<li><a href="/images/add">Add photos</a></li>
				<li><a href="/images">My photos</a></li>
				<li><a href="/profile/<?php echo $this->Session->read('Auth.User.id'); ?>">Profile</a></li>

				<?php if($this->Session->read('Auth.User.group_id') == 1){ ?>
					<li><a href="/group">Groups</a></li>				
					<li><a href="/users">Users</a></li>				
				<?php 
				}
				else{ ?>
					<li><a href="/users">My group</a></li>
				<?php } ?>
				
				<li><a href="/users/edit/<?php echo $this->Session->read('Auth.User.id');?>">Account</a></li>
				<li>
					<?php 
						if($this->Session->read('Auth.User')) {
							echo $this->Html->link('Logout', array('controller'=>'users', 'action'=>'logout'), array('class' => 'last')); 
						}
					?>	
				</li>
			</ul>

		<?php } ?>
	</div>
	<div id="content">
		<div class="section" id="overview">
			<p class="lead">Cool App is an application where you share your photos with your friends and receive comments.</p>
		</div>

		<hr />

		<!--div id="all_content" class="section"-->
			<?php echo $this->fetch('content'); ?>
		<!--/div-->
		<div id="footer">
		</div>
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
