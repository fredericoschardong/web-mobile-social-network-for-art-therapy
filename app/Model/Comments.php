<?php

class Comments extends AppModel {
	public $useTable = "comments";
	public $belongsTo = array("Users");
}

?>
