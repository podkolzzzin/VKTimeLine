<?php
/**
 * Created by JetBrains PhpStorm.
 * User: podko_000
 * Date: 07.09.13
 * Time: 23:09
 * To change this template use File | Settings | File Templates.
 */

class VK
{
    private $token, $user;

    private function VK($data)
    {
        $this->token = $data['access_token'];
        $this->user = $data['user_id'];
    }

    public static function login($code)
    {
        $url = sprintf(App::LOGIN_PATTERN, $code);
        $data = json_decode(WebClient::downloadString($url), true);
        return new VK($data);
    }

    public function getFriends($uid = 'this')
    {
        if ($uid == 'this')
            $uid = $this->user;

        $result = $this->call('friends.get', array(
            'fields' => 'last_seen,online',
            'user_id' => $uid,
        ));

        return $result['response'];
    }

    private function call($method, $params)
    {
        return json_decode(WebClient::downloadString($this->buildRequest($method, $params)), true);
    }

    private function buildRequest($method, $params)
    {
        $paramsStr = "";
        foreach ($params as $key => $p) {
            $paramsStr .= $key . '=' . $p;
            if ($p != end($params)) $paramsStr .= '&';
        }
        return sprintf(App::API_LINK, $method, $this->token, $paramsStr);
    }

    public function getUser()
    {
        return $this->user;
    }
}