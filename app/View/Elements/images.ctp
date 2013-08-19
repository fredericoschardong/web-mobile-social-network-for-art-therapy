<?php
	function content($d, $this_, $last_date, $title = true){
		if(!$title || $last_date != date("d/m/Y", strtotime($d['Images']['date_add']))){
			if($last_date != 0){
				echo "</div><div class='section'>";
			}
			
			$last_date = date("d/m/Y", strtotime($d['Images']['date_add']));

			if($title){
				echo "<h2>Photos of $last_date</h2>";
			}
		}

		//echo "<h3>" . $d['Images']['title'] .  " - " . date("d/m/Y H:i:s", strtotime($d['Images']['date_add'])) . "</h3>";
		?>
		<div class="imageRow" >
			<div class="single">
				<a class="lightbox" href="<?php echo '/files/users/photo/' . $d['Users']['id'] . '/xvga_' . $d['Users']['photo']; ?>" rel="lightbox[<?php echo $d['Images']['title'];?>]">
					<?php echo $this_->Html->image('../files/users/photo/' . $d['Users']['id'] . '/thumb_' . $d['Users']['photo'], array('class' => 'img_profile')); ?>
				</a> 
			</div>
			<?php 
				echo "<h3 class='mouse ". $d['Images']['id'] ."'><a href='/profile/". $d['Users']['id'] ."'>" . $d['Users']['name'] .  " - " . date("d/m/Y H:i:s", strtotime($d['Images']['date_add'])) . "</a>  ";

				if($d['Images']['users_id'] == $this_->Session->read('Auth.User.id')){
					echo $this_->Html->image('remove.png', array('class' => 'remove icon ' . $d['Images']['id']));
					echo $this_->Html->image('edit.png', array('class' => 'edit icon ' . $d['Images']['id']));
				}

				echo "</h3>";
			?>
		</div>
		
		<div style="margin-left: 95px;margin-top:-65px;">
			<div class="imageRow">
				<div class="single">
					<a class="lightbox" href="<?php echo '/files/images/photo/' . $d['Images']['id'] . '/xvga_' . $d['Images']['photo']; ?>" rel="lightbox[<?php echo $d['Images']['title'];?>]">
						<?php echo $this_->Html->image('../files/images/photo/' . $d['Images']['id'] . '/thumb_' . $d['Images']['photo']); ?>
					</a> 
					<ul class="changelog description">
						<li class="description <?php echo $d['Images']['id']; ?>">
							<?php echo $d['Images']['description']; ?>
						</li>
					</ul>
				</div>
			</div>

			<?php
			echo "<h4>Comments:</h4>";
			echo "<ul class='changelog'>";

			if($d['Comments']){
				foreach($d['Comments'] as $c){
					echo "<li class='mouse ". $c['id'] ."'><span class='version'><a href='/profile/". $c['users_id'] ."'>" . $c['Users']['name'] . "</a></span> in <span class='date'>" . date("d/m/Y H:i:s", strtotime($c['date'])) . "</span> said: ";
			
					if(array_key_exists('allow_delete', $c)){
						echo $this_->Html->image('remove.png', array('class' => 'remove icon ' . $c['id']));

						if($c['users_id'] == $this_->Session->read('Auth.User.id')){
							echo $this_->Html->image('edit.png', array('class' => 'edit icon ' . $c['id']));
						}
					}

					echo "</li><li class='comment mouse ". $c['id'] ."'>" . $c['comment'] . "</li>";
				}
			}
			else{
				echo "<li class='no_comments'>No comments yet.</li>";
			}
	
			echo "</ul>";

			echo "<h5 id='h5_". $d['Images']['id'] ."'><a id='comment_". $d['Images']['id'] ."' href='#'>Add a comment</a></h5>";
		
		echo "</div>";

		return $last_date;
	}

	function js($this_){
		$a = "
			function prepare(){
				$('.mouse').each(function(){
					$('.icon.' + ($(this).attr('class').split(' ')[1])).hide();

					$(this).mouseover(function() {
						$('.icon.' + ($(this).attr('class').split(' ')[1])).stop(true, true).show();
					}).mouseout(function() {
						$('.icon.' + ($(this).attr('class').split(' ')[1])).stop(true, true).hide();
					});
				});

				$('li img.icon.remove').click(function(){
					var id = $(this).parent().attr('class').split(' ')[1];

					$.ajax({
						type: 'POST',
						url: '/comments/remove/' + id
					}).done(function(msg){
						if(msg == 'error'){
							error('Sorry, an error has occurred and your deletion was not saved.');
						}
						else{	
							$('.mouse.' + id).hide(400, function(){ 
								if($('.mouse.' + id).parent().children().length <= 3){
									$('<li class=\'no_comments\'>No comments yet.</li>').hide().appendTo($('.mouse.' + id).parent()).show(400);
								}

								$('.mouse.' + id).remove(); 
							});
						}
					});
				});

				$('li img.icon.edit').click(function(){
					var id = $(this).parent().attr('class').split(' ')[1];

					if($('.edit_comment', $(this).parent().parent()).length == 0){
						var a = \"<li><textarea id='textarea_\"+ id +\"' rows='4' cols='50'></textarea></li><li><div id='elsewhere' class='row'><a class='edit_comment button \"+ id +\"'>Edit</a><a class='cancel_comment button \"+ id +\"'>Cancel</a></div></li>\";
			
						$(this).parent().next().hide(400);
						$(a).insertAfter($(this).parent().next()).hide().show(400);

						$('#textarea_' + id).html($(this).parent().next().html());
					
						$('#textarea_' + id).focus();

						$('.cancel_comment').click(function(){
							var id = $(this).attr('class').split(' ')[2];

							$(this).parent().parent().hide(400);
							$(this).parent().parent().prev().hide(400);

							$(this).parent().parent().prev().remove();
							$(this).parent().parent().remove();

							$('.comment.mouse.' + id).show(400);
						});

						$('.edit_comment').click(function(){
							var id = $(this).attr('class').split(' ')[2];
							var comment = $('#textarea_' + id).val();

							if($.trim(comment).length == 0){
								error('You have to type something');
								return;
							}

							$.ajax({
								type: 'POST',
								url: '/comments/edit/' + id + '/' + comment
							}).done(function(msg){
								if(msg == 'error'){
									error('Sorry, an error has occurred and your edition was not saved.');
								}
								else{	
									$('.edit_comment.' + id).parent().parent().hide(400);
									$('.edit_comment.' + id).parent().parent().prev().hide(400);

									$('.edit_comment.' + id).parent().parent().prev().remove();
									$('.edit_comment.' + id).parent().parent().remove();
			
									$('.comment.mouse.' + id).html(comment).show(400);
								}
							});
						});
					}	
				});
			}

			$(document).ready(function(){
				$('h3 img.remove.icon').click(function(){
					var id = $(this).parent().attr('class').split(' ')[1];

					$.ajax({
						type: 'POST',
						url: '/images/remove/' + id
					}).done(function(msg){
						if(msg == 'error'){
							error('Sorry, an error has occurred and your deletion was not saved.');
						}
						else{	
							$('.mouse.' + id).parent().parent().hide(400, function(){ 
								$('.mouse.' + id).parent().parent().remove(); 
							});
						}
					});
				});

				$('h3 img.edit.icon').click(function(){
					var id = $(this).parent().attr('class').split(' ')[1];

					if($('#textarea_' + id, $(this).parent().parent().parent()).length == 0){
						var a = \"<li class='description'><textarea id='textarea_\"+ id +\"' rows='4' cols='50'></textarea></li><li class='description'><div id='elsewhere' class='row'><a class='edit_comment button \"+ id +\"'>Edit</a><a class='cancel_comment button \"+ id +\"'>Cancel</a></div></li>\";
			
						$(this).parent().parent().next().find('ul').first().children().hide(400);
						$(a).insertAfter($(this).parent().parent().next().find('ul').first().children()).hide().show(400);
					
						$('#textarea_' + id).focus();

						$('.cancel_comment').click(function(){
							var id = $(this).attr('class').split(' ')[2];

							$(this).parent().parent().prev().hide(400, function(){ 
								$(this).remove();
							});

							$(this).parent().parent().hide(400, function(){ 
								$(this).remove();
							});

							$('.description.' + id).show(400);
						});

						$('.edit_comment').click(function(){
							var id = $(this).attr('class').split(' ')[2];
							var comment = $('#textarea_' + id).val();

							if($.trim(comment).length == 0){
								error('You have to type something');
								return;
							}

							$.ajax({
								type: 'POST',
								url: '/images/edit/' + id + '/' + comment
							}).done(function(msg){
								if(msg == 'error'){
									error('Sorry, an error has occurred and your edition was not saved.');
								}
								else{	
									$('.edit_comment.button.' + id).parent().parent().prev().hide(400, function(){ 
										$(this).remove();
									});

									$('.edit_comment.button.' + id).parent().parent().hide(400, function(){ 
										$(this).remove();
									});
			
									$('.description.' + id).html(comment).show(400);
								}
							});
						});
					}	
				});

				prepare();
			});";

		echo $this_->Html->scriptblock($a);
	}

	function lightbox($this_, $user = '', $username = ''){
		echo $this_->Html->css('../js/lightbox/jquery.lightbox-0.5.css');
		echo $this_->Html->script('lightbox/jquery.lightbox-0.5.min');

		if($user && $username){
			$a = "
			function error(msg){
				$('.error').css('display', 'block');
				$('.error').text(msg);
				$('.error').animate({top:'0'}, 1000);

				setTimeout(function() {
					$('.error').animate({top: -$(this).outerHeight()}, 1000);
				}, 5000);
			}

			$(document).ready(function(){
				$('a.lightbox').lightBox({
					no_nav: true
				});

				$('h5 a').click(function() {
					if($(this).parent().next().prop('tagName') != 'UL'){
						var a = \"<ul id='ul_\"+ $(this).attr('id') +\"'><li><textarea id='textarea_\"+ $(this).attr('id') +\"' rows='4' cols='50'></textarea></li><li><div id='elsewhere' class='row'><a id='_\"+ $(this).attr('id') +\"' class='add_comment button'>Send</a></div></li></lu>\";

						$(a).insertAfter($(this).parent()).hide().show(400);
						
						$('#textarea_' + $(this).attr('id')).focus();

						$('.add_comment').click(function(){
							var comment = $('#textarea' + $(this).attr('id')).val();

							if($.trim(comment) == ''){
								error('You have to type something.');
							}
							else{
								var id = $(this).attr('id').substring(9);

								$.ajax({
									type: 'POST',
									url: '/comments/add/' + id + '/' + comment
								}).done(function(msg){
									if(msg == 'error'){
										error('Sorry, an error has occurred and your comment was not saved.');
									}
									else{	
										var idd = msg.substring(0, msg.indexOf('|'));
										var date = msg.substring(msg.indexOf('|') + 1);

										var aa = '<li class=\'mouse '+ idd +'\'><span class=\'version\'><a href=\'/profile/". $user ."\'>". $username ."</a></span> in <span class=\'date\'>'+ date +'</span> said: <img src=\'/img/remove.png\' class=\'remove icon '+ idd +'\'> <img src=\'/img/edit.png\' class=\'edit icon '+ idd +'\'> </li><li class=\'comment mouse '+ idd +'\'>'+ comment + '</li>';
				
										$(aa).hide().appendTo($('#h5_' + id).prev()).show(400);

										if($('#h5_' + id).prev().find('.no_comments').length){
											$('#h5_' + id).prev().find('.no_comments').hide(400, function(){ 
												$('#h5_' + id).prev().find('.no_comments').remove();
											});
										}
	
										prepare();
									}

									$('#ul_comment_' + id).hide(400, function(){ 
										$('#ul_comment_' + id).remove();
									});
								});
							}
						});
					}
					else{
						var id = $(this).attr('id').substring(8);

						$('#ul_comment_' + id).hide(400, function(){ 
							$('#ul_comment_' + id).remove();
						});
					}

					return false;
				});

			
			});";
		}
		else{
			$a = "
			$(document).ready(function(){
				$('a.lightbox').lightBox();
			});";
		}

		echo $this_->Html->scriptblock($a);
	}

?>
