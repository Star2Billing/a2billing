<?php

/**
 * Description of ApiClient
 *
 * @author Roman Davydov http://openvoip.co
 */
class ApiClient {
    
    private
        $url = null,
        $defaultFormat = null;
    
    public function __construct($api_security_key, $url, $defaultFormat) {
        $this->url = $url . '?api_security_key=' . md5($api_security_key);
        $this->defaultFormat = $defaultFormat;
    }
    
    public function __call($name, $arguments) {
        $params = is_array($arguments) && count($arguments) > 0 && is_array($arguments[0]) ? $arguments[0] : array();
        $format = is_array($arguments) && count($arguments) > 1 ? $arguments[1] : null;
        
        return $this->execute($name, $params, $format);
    }
    
    public function execute($method, $params = array(), $format = null) {
        $strParams = array();
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $strParams[] = "$key=" . urlencode($value);
            }
        }
        
        $format = is_null($format) ? $this->defaultFormat : $format;
        $url = $this->url . '&api_format=' . $format . '&api_method=' . $method . '&' . join('&', $strParams);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $this->formatResult($result, $format);
    }
    
    /**
     * Decodes result
     * 
     * @param mixed $data
     * @param string $format
     * @return mixed
     */
    private function formatResult($data, $format) {
        $result = $data;
        switch (strtolower($format)) {
            case 'json':
                $result = json_decode($data, true);
                break;
        }
        return $result;
    }
    
}
