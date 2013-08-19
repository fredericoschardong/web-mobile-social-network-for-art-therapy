<?php

class GroupController extends AppController {
	var $helpers = array('Js', 'datagrid', 'lightbox');
	var $uses = array('Group', 'Users');

	public function beforeFilter(){
		if($this->Auth->user('group_id') != '1'){
			$this->Session->setFlash(__('You cannot access this.'), 'flash_error');
            $this->redirect(array('controller' => '/'));
		}
	
		parent::beforeFilter();
	}

    public function index() {
		$this->Group->validate = array();

		$filters = array();

	    if(array_key_exists('Group', $this->data) && strlen($this->data['Group']['name']) > 0){
	        $filters['Group.name LIKE'] = '%' . $this->data['Group']['name']. '%';
	    }

        $this->Group->recursive = 0;
        $this->set('group', $this->paginate('Group', $filters));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->Group->create();

            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash(__('The group has been saved.'), 'flash_success');
                $this->redirect(array('action' => '/'));
            } else {
                $this->Session->setFlash(__('The group could not be saved. Please, try again.'), 'flash_error');
            }
        }
    }

    public function edit($id) {
        $this->Group->id = $id;

        if (!$this->Group->exists()) {
            throw new NotFoundException(__('Invalid group'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Group->save($this->request->data)) {
                $this->Session->setFlash(__('The group has been saved.'), 'flash_success');
                $this->redirect(array('action' => '/'));
            } else {
                $this->Session->setFlash(__('The group could not be saved. Please, try again.'), 'flash_error');
            }
        } else {
            $this->request->data = $this->Group->read(null, $id);
        }
    }

    public function delete($id = null) {
        $this->Group->id = $id;

        if (!$this->Group->exists()) {
            throw new NotFoundException(__('Invalid group'));
        }

		$a = $this->Users->find('all', array('conditions' => array('group_id' => $id)));

		if($a){
			$b = '';

			foreach($a as $c){
				$b .= $c['Users']['name'] . ', ';
			}

			$b = substr($b, 0, -2);

			$this->Session->setFlash(__('Group was not deleted because it has users (' . $b . ')'), 'flash_error');
 	        $this->redirect(array('action' => 'index'));
		}
		else{
		    if ($this->Group->delete()) {
		        $this->Session->setFlash(__('Group deleted'));
		        $this->redirect(array('action' => 'index'));
		    }
		}
    }
}

?>
