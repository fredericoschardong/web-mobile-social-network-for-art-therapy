<?php

class Images extends AppModel {
	public $actsAs = array(
        'Upload.Upload' => array(
            'photo' => array(
                'fields' => array(
                    'dir' => 'photo_dir'
                ),

                'thumbnailSizes' => array(
                    'xvga' => '1024x768',
                    'vga' => '640x480',
                    'thumb' => '150x150'
                )
            )
        )
    );

    public $validate = array(
		'title' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A title is required'
            )
        ),        

		'photo' => array(
		    'rule' => array('aa', array('jpg', 'png', 'jpeg', 'bmp', 'tiff')),
		    'message' => 'File is not an image'
    	)
    );

	public function aa($check, $limit){
		foreach($limit as $l){
			if(strpos($check['photo']['type'], $l) !== false){
				return true;
			}
		}

		return false;
	}

	public $hasMany = array("Comments" => array('order' => 'Comments.date ASC'));
	public $belongsTo = array('Users');
}

?>
