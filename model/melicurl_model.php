<?php

/**
 * melicurl_model
 */

class Curl
{
    

    public static function request_multiple($urls, $opciones = array())
    {
        //para ver diferentes $opciones ver https://www.php.net/manual/es/function.curl-setopt.php

        $curly = array();
        $resultado = array();
        $pm = curl_multi_init();
        foreach ($urls as $id => $d) {
            $curly[$id] = curl_init();
            if (is_array($d) && !empty($d['url'])) {
                $url = $d['url'];
            } else {
                $url = $d;
            }
            curl_setopt($curly[$id], CURLOPT_URL, $url);
            curl_setopt($curly[$id], CURLOPT_HEADER, 0);
            curl_setopt($curly[$id], CURLOPT_RETURNTRANSFER, 1);
            if (!empty($d['post'])) {
                curl_setopt($curly[$id], CURLOPT_POST, 1);
                curl_setopt($curly[$id], CURLOPT_POSTFIELDS, $d['post']);
            }
            if (!empty($opciones)) {
                curl_setopt_array($curly[$id], $opciones);
            }
            curl_multi_add_handle($pm, $curly[$id]);
        }
        $ejecutando = null;
        do {
            curl_multi_exec($pm, $ejecutando);
        } while ($ejecutando > 0);
        foreach ($curly as $id => $c) {
            $resultado[$id] = curl_multi_getcontent($c);
            curl_multi_remove_handle($pm, $c);
        }
        curl_multi_close($pm);
        return $resultado;
    }

    public static function file_get_contents_curl($url, $opciones = array())
    {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt_array($ch, $opciones);
        
        $data = curl_exec($ch);
        curl_close($ch);
        
        return $data;
    }    
}
