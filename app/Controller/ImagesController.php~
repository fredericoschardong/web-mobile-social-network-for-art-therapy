<?php

class ImagesController extends AppController {
	var $uses = array('Images', 'Comments');

	public function beforeFilter(){
        $this->Auth->allow('add');
	}

    public function index() {
		$this->Images->validate = array();

		$filters = array();

        if (array_key_exists('Images', $this->data) && strlen($this->data['Images']['title']) > 0) {
            $filters['Images.title LIKE'] = '%' . $this->data['Images']['title']. '%';
        }

        $images = $this->Images->Find('all', array('order' => 'Images.date_add DESC', 'recursive' => 2, 'conditions' => array('Images.users_id' => $this->Auth->user('id'))));

		$this->set('images', $this->allow_delete($images));
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

    public function add() {
		$mobile = false;

		if(array_key_exists('mobile', $this->request->data)){
			$mobile = true;

			$this->autoRender = false;

			$user['User']['username'] = $this->request->data['username'];
			$user['User']['password'] = $this->request->data['password'];

			$tmp['Images']['description'] = $this->request->data['description'];
			$tmp['Images']['lat'] = $this->request->data['lat'];
			$tmp['Images']['lng'] = $this->request->data['lng'];
			$tmp['Images']['acc'] = (int)$this->request->data['acc'];

			$this->data = $user;

			if(!$this->Auth->login()){
				echo 'login error';
			}

			$tmp['Images']['photo']['name'] = $_FILES['file']['name'];
			$tmp['Images']['photo']['type'] = $_FILES['file']['type'];
			$tmp['Images']['photo']['tmp_name'] = $_FILES['file']['tmp_name'];
			$tmp['Images']['photo']['error'] = $_FILES['file']['error'];
			$tmp['Images']['photo']['size'] = $_FILES['file']['size'];

			$this->request->data = $tmp;

			//var_dump($this->request->data);
			//var_dump($_REQUEST);
			//var_dump('user: ' . $this->Session->read('Auth.User.id'));

			//exit(0);
		}

        if ($this->request->is('post')) {
            $this->Images->create();

			$data = $this->request->data;

			$data['Images']['users_id'] = $this->Session->read('Auth.User.id');
			$data['Images']['date_add'] = date("Y-m-d H:i:s");

            if ($this->Images->save($data)) {
				if($mobile){
					echo 'ok';
					exit();
				}

				$this->Session->setFlash(__('The image has been saved.'), 'flash_success');
                $this->redirect(array('controller' => '/'));
            } else {
				if($mobile){				
					echo 'error';
					exit();
				}
            }
        }

    }

   public function edit($id, $comment) {
		$this->autoRender = false;

		$this->Images->read(null, $id);

		unset($this->Images->validate);

		$this->Images->set(array(
			'description' => $comment
		));

        if(!$this->Images->save()){
    		echo 'error';
		}
    }

    public function remove($id = null) {
		$this->autoRender = false;
		
		$img = $this->Images->find('first', array('conditions' => array('Images.id' => $id)));

		if($img){
			if($img['Images']['users_id'] == $this->Session->read('Auth.User.id')){
				if(!$this->Images->delete($id)){
					echo "error";
				}

				if(!$this->Comments->deleteAll(array('image_id' => $id))){
					echo "error";
				}
			}
		}
		else{
			echo "error";
		}
    }
}

?>
