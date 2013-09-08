<?php
/**
 * Created by JetBrains PhpStorm.
 * User: podko_000
 * Date: 14.06.13
 * Time: 11:55
 * To change this template use File | Settings | File Templates.
 */

class WebClient
{
    public static function downloadString($url)
    {
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($c);
        if (!$result) {
            echo curl_error($c);
        }
        curl_close($c);
        return $result;
    }

    public static function downloadStringT($url)
    {
        //return file_get_contents(SITE_PATH . 'matches.json');
    }
}