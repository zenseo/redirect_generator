<?php

class Redirect_generator { 

    public function init() {

        $out = ''; 

        $action = isset($_POST['action']) ?  preg_replace('~[^a-z]~i', '', $_POST['action']) : '';
        $format = isset($_POST['format']) ?  preg_replace('~[^a-z]~i', '', $_POST['format']) : '';
      
        if(!empty($action) && !empty($format)) {

            $file = $this->get_file(); 

            if(isset($file)) {

                $data =  file($file[0]);

                foreach ($data as $urls) {

                    $urls = explode(',', $urls);

                    if(!empty($urls[0]) && !empty($urls[1]) && count($urls) == 2) {

                        $urls[0] = trim(str_replace(array("\r\n", "\r", "\n"), ' ',  $urls[0]));
                        $urls[1] = trim(str_replace(array("\r\n", "\r", "\n"), ' ',  $urls[1]));

                        $out .= $this->set_format($urls, $action, $format); 
                    }
                }
            } 
        }

        if($action == 'redirect' && $format == 'smarty') {
            $out_temp = "{php}\r\n"; 
            $out_temp .= $out;
            $out_temp .= "{/php}\r\n";
            $out = $out_temp;
        }

        return $out;   
    }

    private function set_format($urls = array(), $action = '', $format = '') {
        $out = '';

        switch ($action) {
           case 'redirect':

                $urls = $this->compress_domens($urls);

                switch ($format) {
                    case 'php':
                       $urls[0] = '/'.$urls[0];
                       $out = $this->_set_redirect_php($urls);
                       break;
                    case 'smarty': 
                        $urls[0] = '/'.$urls[0];
                        $out = $this->_set_redirect_php($urls); 
                       break;
                    case 'htaccess':
                       $out = $this->_set_redirect_htaccess($urls);
                       break;
                   default: 
                       break;
               }

               break;
           case 'canonical':

                $urls[0] = '/'.$this->_delete_domen_name($urls[0]);

                switch ($format) {
                    case 'php':
                       $out = $this->_set_canonical_php($urls); 
                       break;
                    case 'smarty':
                       $out = $this->_set_canonical_smarty($urls);
                       break;
                   default: 
                       break;
               }

               break;
           default:
               break;
       }

       return $out;
    }

    private function _set_canonical_php($urls = array()) {
        $out = "if(strcmp('".$urls[0]."', ".'$_SERVER["REQUEST_URI"]'.") == 0) {\r\n";
        $out .= htmlspecialchars("  echo \"<link rel='canonical' href='".$urls[1]."' />\";\r\n");
        $out .= "}\r\n";
        return $out;
    }

    private function _set_canonical_smarty($urls = array()) {
        $out = "{if ".'$smarty.server.REQUEST_URI'." == '".$urls[0]."'}\r\n";
        $out .= htmlspecialchars("  <link rel='canonical' href='".$urls[1]."' /> \r\n");
        $out .= "{/if}\r\n";
        return $out;
    }

    private function _set_redirect_php($urls = array()) {
        $out = "if(strcmp('".$urls[0]."', ".'$_SERVER["REQUEST_URI"]'.") == 0) {
           header('HTTP/1.1 301 Moved Permanently');
           header('Location: ".$urls[1]."');\r\n}\r\n";
        return $out;
    }

    private function _set_redirect_htaccess($urls = array()) {
        $out = '';

        if(preg_match('~\?~', $urls[0])) {

            $param = explode('?', $urls[0]);

            $out .= "RewriteCond %{QUERY_STRING} ".$param[1]." \r\n";
            $out .= "RewriteRule ^".$param[0]."$ ".$urls[1]."? [R=301,L] \r\n";

        } else {
            $out = 'RewriteRule ^'.preg_replace('/[\.\/\-]+/i','\\\\\0',$urls[0]).'$ '.$urls[1]." [R=301,L] \r\n";
        }

        return  $out;          
    } 
 
    protected function compress_domens($urls = array()) {
        
        //проверяем если домен, если нет, то оставляем как есть
        if(preg_match('~http://~i', $urls[1])) {

            //проверяем совпадают ли домены у обоих ссылок
            //если да, то обрезаем домен
            //если нет, то оставляем как есть

            $main_url = $this->_get_domen_from_url($urls[0]);
            $redirect_url = $this->_get_domen_from_url($urls[1]); 

            if(strcmp($main_url, $redirect_url) == 0) {
                $urls[1] = '/'.$this->_delete_domen_name($urls[1]);
            }

        }
 
        //со страницы редиректа ссылка всегда относительная
        $urls[0] = $this->_delete_domen_name($urls[0]);
 
        return $urls;
    }

    private function _get_domen_from_url($url = '') {
        $out = '';
        if(preg_match('~http://~', $url)) { 
            $url = explode('http://', $url);
            $url = explode('/', $url[1]);
            $out = trim(strtolower($url[0]));
        }
        return $out;
    }

    private function _delete_domen_name($url = '') {

        if(preg_match('~http://~', $url)) {
            $url = explode('http://', $url);
            $url = explode('/', $url[1]);
            unset($url[0]);
            $url = implode('/', $url);
        }

        return $url;
    }

    function get_file() {
        foreach ($_FILES as $name_upload_file => $files) {
            if(isset($_FILES[$name_upload_file]["name"])) {
                $files_count = sizeof($_FILES[$name_upload_file]["name"]);
                for ($i = 0; $i <= $files_count - 1; $i++) {    
                    if (isset($_FILES[$name_upload_file]) && $_FILES[$name_upload_file]['error'][$i] == UPLOAD_ERR_OK) {
                        return $_FILES[$name_upload_file]['tmp_name'];
                    }
                }
            }
        }
    }
}