<?php

class CommentsController extends AppController {
	var $uses = array('Images', 'Comments', 'Users');

    public function add($photo_id, $comment) {
		$this->autoRender = false;

		$data['Comments']['users_id'] = $this->Session->read('Auth.User.id');
		$data['Comments']['image_id'] = $photo_id;
		$data['Comments']['comment'] = $comment;
		$data['Comments']['date'] = date("Y-m-d H:i:s");

		$image = $this->Images->find('first', array('conditions' => 'Images.id = ' . $photo_id));

		if(!(is_array($image) && $image)){
			echo "error";
		}
		else{		
			$group = $this->Users->find('first', array('conditions' => 'Users.id = ' . $image['Images']['users_id']));
			$group = $group['Users']['group_id'];

			if(!(($group == $this->Auth->user('group_id')) || $group == '1')){
				echo "error";
			}
			else{
				if ($result = $this->Comments->save($data)) {
					echo $result['Comments']['id'] . '|' . date("d/m/Y H:i:s", strtotime($result['Comments']['date']));
				} else {
				    echo "error";
				}
			}
		}
    }

	public function remove($id){
		$this->autoRender = false;
		
		$comment = $this->Comments->find('first', array('conditions' => array('Comments.id' => $id)));

		if($comment){
			$image = $this->Images->find('first', array('conditions' => array('Images.id' => $comment['Comments']['image_id'])));

			if($comment['Comments']['users_id'] == $this->Session->read('Auth.User.id') || ($image && $image['Images']['users_id'] == $this->Session->read('Auth.User.id'))){
				if(!$this->Comments->delete($comment['Comments']['id'])){
					echo "error";
				}
			}
		}
		else{
			echo "error";
		}
	}

	public function edit($id, $comment) {
		$this->autoRender = false;

		$this->Comments->read(null, $id);

		$this->Comments->set(array(
			'comment' => $comment
		));

        if(!$this->Comments->save()){
			echo 'error';
		}
    }
}

?>
