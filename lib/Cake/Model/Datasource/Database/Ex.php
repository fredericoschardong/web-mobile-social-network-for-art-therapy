<?php
App::uses('Mysql', 'Model/Datasource/Database');
    /**          
     * Logs MySQL queries into debug file
     * 
     * @author  Vladimir Bilyov 2012-03-04 19:03:03
     * @version 1.0
     */
    class Ex extends Mysql {

        function logQuery($sql, $a) {
            parent::logQuery($sql, $a);
            //if (Configure::read('Cake.logQuery')) {
                debug('sql[' . $this->_queriesCnt . ']:' . $sql);
            //}
        }
    }

?>
