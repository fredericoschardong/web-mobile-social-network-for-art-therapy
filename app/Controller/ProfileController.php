<?php

App::import('Controller', 'Pages'); 

class ProfileController extends PagesController {
	var $uses = array('Users', 'Images', 'Comments');

	public function beforeFilter() {
		parent::beforeFilter();

		if (!method_exists($this, $this->action)) {
			$this->params['pass'] = array($this->action);
			$this->action = 'index';
		}
	}

    public function index($id) {
		$group = $this->Users->find('first', array('conditions' => array('Users.id' => $id)));

		$this->set("info", $group);

		if($this->Auth->user('group_id') == 1 || (is_array($group) && array_key_exists('Users', $group) && $group['Users']['group_id'] == $this->Auth->user('group_id'))){
			$this->set('notfound', false);

			$this->Images->unbindModel(
				array('hasMany' => array('Comments'))
			);

			$this->Images->bindModel(
				array('hasMany' => array(
						'Comments' => array('order' => 'Comments.date DESC')
					)
				)
			);

			$options = array('joins' => 
				array(
					array('table' => 'comments',
						'alias' => 'Comments',
						'type' => 'LEFT',
						'conditions' => array(
							'Images.id = Comments.image_id',
						)
					)
				),

				'order' => 'Comments.date DESC, Images.date_add DESC',
				'recursive' => 2,
				'conditions' => array('Images.users_id' => $id)
			);

			$images = $this->order_images($this->Images->find('all', $options));	
			$images = $this->allow_delete($images);

			$this->set('images', $images);
		}
		else{
			$this->set('notfound', true);
		}

		$this->render('index');
    }
}

?>
