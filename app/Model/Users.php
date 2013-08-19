<?php

class Users extends AppModel {
	var $hasOne = array('City' => 
							array('foreignKey' => false, 
								  'conditions' => array('Users.city_id = City.id')),
						'Group' => array('foreignKey' => false, 
								  'conditions' => array('Users.group_id = Group.id')));

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
        'username' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A username is required'
            )
        ),

        'password' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A password is required'
            )
        ),

		'name' => array(
            'required' => array(
                'rule' => array('notEmpty'),
                'message' => 'A name is required'
            ),
			
        ),

		'email' => array(
            'required' => array(
                'rule' => array('email'),
                'message' => 'A valid e-mail is required'
            )
        ),

		'photo' => array(
		    'rule' => array('isValidExtension', array('jpg', 'png', 'jpeg', 'bmp', 'tiff')),
		    'message' => 'File is not an image'
    	)
    );

	public function beforeSave($options = array()) {
		if (isset($this->data[$this->alias]['password'])) {
		    $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
		}

		return true;
	}
}

?>
