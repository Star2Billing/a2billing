<?php

class JsonFormat implements IApi {
    
    public function init($api) {
        
        $api->registerFormat('json', array($this, 'format'));
        
    }

    public function format($data) {
        
        if (!is_array($data))
            $data = array('data' => $data);
        
        return json_encode($data);
        
    }
    
}
