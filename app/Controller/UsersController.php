<?php

class UsersController extends AppController {
	var $helpers = array('Js', 'datagrid', 'lightbox');
	var $uses = array('Users', 'Province', 'City', 'Group');

    public function beforeFilter() {
        parent::beforeFilter();

		$province = $this->Province->find('list', array('order' => 'name ASC'));

		if($this->data){
			$provinces = $this->data['Users']['province_id'];
		}
		else{
			$provinces = $this->Province->find('first', array('order' => 'name ASC'));
			$provinces = $provinces['Province']['id'];
		}

		$this->set('city', $this->City->find('list', array('conditions' => array('province_id' => $provinces))));
		$this->set('province', $province);
		$this->set('group', $this->Group->find('list'));

		if($this->Auth->user('group_id') == '1'){
			$this->set('is_admin', 1);
		}
		else{
			$this->set('is_admin', 0);
		}
    }

	public function login() {
		if ($this->request->is('post')) {
		    if ($this->Auth->login()) {
				if($this->data['mobile']){
					echo 'ok';
					exit();
				}

		        $this->redirect($this->Auth->redirect());
		    } else {
				if($this->data['mobile']){
					echo 'error';
					exit();
				}
				else{
		        	$this->Session->setFlash(__('Invalid username or password, try again'), 'flash_error');
				}
		    }
		}

		if($this->data['mobile']){
			echo 'error';
			exit();
		}
	}

	public function logout() {
		$this->redirect($this->Auth->logout());
	}

    public function index() {
		$this->Users->validate = array();

		$filters = array();

		if($this->Auth->user('group_id') == '1'){
		    if (array_key_exists('Users', $this->data) && strlen($this->data['Users']['name']) > 0) {
		        $filters['Users.name LIKE'] = '%' . $this->data['Users']['name']. '%';
		    }

		    if (array_key_exists('Users', $this->data) && array_key_exists('city_id', $this->data['Users']) && is_numeric($this->data['Users']['city_id'])) {
		        $filters['Users.city_id'] = $this->data['Users']['city_id'];
		    }

			if (array_key_exists('Users', $this->data) && array_key_exists('group_id', $this->data['Users']) && is_numeric($this->data['Users']['group_id'])) {
		        $filters['Users.group_id'] = $this->data['Users']['group_id'];
		    }
		}
        else{
			$filters['Users.group_id'] = $this->Auth->user('group_id');
		}

        $this->Users->recursive = 0;
        $this->set('users', $this->paginate('Users', $filters));
    }

    public function view($id = null) {
        $this->Users->id = $id;

        if (!$this->Users->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        $this->set('users', $this->User->read(null, $id));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Users->create();

			$user = $this->Session->read('Auth.User');
			$this->request->data['Users']['added_by_user_id'] = $user['id'];

            if ($this->Users->save($this->request->data)) {
                $this->Session->setFlash(__('The user has been saved.'), 'flash_success');
                $this->redirect(array('action' => '/'));
            } else {
                $this->Session->setFlash(__('The user could not be saved. Please, try again.'), 'flash_error');
            }
        }
    }

    public function edit($_id) {
		if($this->Auth->user('group_id') == '1'){
			$id = $_id;
		}
		else{
			$id = $this->Auth->user('id');
		}

        $this->Users->id = $id;

		$a = $this->Users->find('first', array('conditions' => array('Users.id' => $id)));

		$b = $this->City->find('first', array('conditions' => array('id' => $a['Users']['city_id'])));

		$this->set('city', $this->City->find('list', array('conditions' => array('province_id' => $b['City']['province_id']))));
		$this->set('default_province', $b['City']['province_id']);

		unset($this->Users->validate['photo']);
		unset($this->Users->validate['password']);

        if (!$this->Users->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
			if($this->request->data['Users']['password'] == ""){
				unset($this->request->data['Users']['password']);
			}

			if($this->request->data['Users']['photo']['name'] == ""){
				unset($this->request->data['Users']['photo']);
				unset($this->Users->actsAs);
			}

            if ($this->Users->save($this->request->data)) {
				if(array_key_exists('photo', $this->request->data['Users']) && $this->request->data['Users']['photo']['name'] != ""){
					$this->Session->write('Auth.User.photo', $this->request->data['Users']['photo']['name']);
				}

                $this->Session->setFlash(__('The user has been saved.'), 'flash_success');
                $this->redirect(array('action' => '/'));
            } else {
				$this->request->data['Users']['photo'] = $a['Users']['photo'];
				$this->request->data['Users']['id'] = $a['Users']['id'];
				
                //$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Users->read(null, $id);
            unset($this->request->data['Users']['password']);
        }
    }

    public function delete($id = null) {
		if($this->Auth->user('group_id') != '1'){
			$this->Session->setFlash(__('You cannot access this.'), 'flash_error');
            $this->redirect(array('controller' => '/'));
		}

        $this->Users->id = $id;

        if (!$this->Users->exists()) {
            throw new NotFoundException(__('Invalid user'), 'flash_error');
        }
        if ($this->Users->delete()) {
            $this->Session->setFlash(__('User deleted'), 'flash_success');
            $this->redirect(array('action' => 'index'));
        }

        $this->Session->setFlash(__('User was not deleted'), 'flash_error');
        $this->redirect(array('action' => 'index'));
    }
}

?>
