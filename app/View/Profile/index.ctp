<div class='section'>

<?php 
	$this->element('images');

	if($notfound){
		echo "We could not find the person you are looking for.";
	}
	else{
		if(!$images){
			echo $info['Users']['name'] . " has nothing to be shown.";
		}
		else{
			$last_date = 0;

			$array = array();

			foreach ($images as $i => $d){
				if(!in_array($d['Images']['id'], $array)){ 
					$last_date = content($d, $this, $last_date, false	);
					$array[] = $d['Images']['id'];
				}
			}		

			js($this);
		}
	}


	lightbox($this, $user, $username);
?>

</div>

