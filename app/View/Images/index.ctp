<div class='section'>

<?php 
	if($images){
		$this->element('images');

		$last_date = 0;

		foreach ($images as $i => $d){ 
			$last_date = content($d, $this, $last_date);
		}		

		js($this);

		lightbox($this, $user, $username);
	}
	else{
		echo "You have nothing to be shown, please add a photo";
	}
?>

</div>

