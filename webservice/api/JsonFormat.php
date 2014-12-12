<?php

class JsonFormat implements IApi {
    
    public function init($api) {
        
        $api->registerFormat('json', array($this, 'format'));
        
    }

    public function format($data) {
        
        echo json_encode($data);
        
    }
    
}
