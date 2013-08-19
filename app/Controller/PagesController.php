<?php

class PagesController extends AppController {
	var $uses = array('Users', 'Images', 'Comments', 'Group');

	public function beforeFilter() {
		parent::beforeFilter();

		if (!method_exists($this, $this->action)) {
			$this->params['pass'] = array($this->action);
			$this->action = 'index';
		}
	}

    public function index($id) {
		$users = $this->Users->find('list', array('fields' => array('Users.id'), 'conditions' => array('Users.group_id' => $this->Auth->user('group_id'))));	
	
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
			'conditions' => array('Images.users_id' => $users)
		);

		$images = $this->order_images($this->Images->find('all', $options));
		$images = $this->allow_delete($images);

		$this->set('images', $images);
		$this->set('notfound', 0);

		if($this->data && $this->data['mobile']){
			$array = array();

			foreach ($images as $i => $d){
				if(!in_array($d, $array)){ 
					$array[] = $d;
				}
			}	

			echo json_encode($array);
			exit(0);
		}

		$this->render('/Profile/index');
    }

	public function allow_delete($images){
		foreach($images as $k => $i){
			if($i['Comments']){
				if($i['Images']['users_id'] == $this->Session->read('Auth.User.id')){
					foreach($images[$k]['Comments'] as $k1 => $a){
						$images[$k]['Comments'][$k1]['allow_delete'] = true;
					}
				}
				else{
					foreach($images[$k]['Comments'] as $k1 => $a){
						if($images[$k]['Comments'][$k1]['users_id'] == $this->Session->read('Auth.User.id')){
							$images[$k]['Comments'][$k1]['allow_delete'] = true;
						}
					}
				}
			}
		}

		return $images;
	}

	public function order_images($images){
		usort($images, function($a, $b)
		{
			if($a['Comments'] && $b['Comments']){
				if(strtotime($a['Comments'][0]['date']) > strtotime($b['Comments'][0]['date'])){
					return -1;
				}
			}
			else{
				if($a['Comments']){
					if(strtotime($a['Comments'][0]['date']) > strtotime($b['Images']['date_add'])){
						return -1;
					}
				}
				else{
					if($b['Comments']){
						if(strtotime($a['Images']['date_add']) > strtotime($b['Comments'][0]['date'])){
							return -1;
						}
					}
					else{
						if(strtotime($a['Images']['date_add']) > strtotime($b['Images']['date_add'])){
							return -1;
						}
					}
				}
			}

			return 1;
		});

		foreach($images as $k => $i){
			if($i['Comments']){
				usort($images[$k]['Comments'], function($a, $b)
				{
					if(strtotime($a['date']) > strtotime($b['date'])){
						return 1;
					}

					return -1;
				});
			}
		}

		return $images;
	}
}

?>
