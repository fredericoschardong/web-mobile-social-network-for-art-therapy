<?php

class CityController extends AppController {
	var $uses = array('City');
 
	public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow('getByProvince');
    }

	public function getByProvince() {
		$province_id = $this->request->data['Users']['province_id'];
 
		$cities = $this->City->find('list', array(
			'conditions' => array('City.province_id' => $province_id),
			'recursive' => -1
			));
 
		$this->set('city', $cities);
		$this->layout = 'ajax';
	}
}

?>
