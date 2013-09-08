<?php
/**
 * Created by JetBrains PhpStorm.
 * User: podko_000
 * Date: 07.09.13
 * Time: 22:38
 * To change this template use File | Settings | File Templates.
 */

define('ANALYZE_LEVEL', 2, true);
class Controller_app extends Controller_base
{
    public function observables()
    {
        set_time_limit(300000);
        /**@var $VK VK*/
        $VK = unserialize(file_get_contents('vk'));
        $analyzer = new Analyzer($this->db, $VK);

        echo print_r($analyzer->findObservables(ANALYZE_LEVEL));
    }

    public function save()
    {
        $s = serialize($_SESSION['VK']);
        file_put_contents('vk',$s);
    }

    public function load()
    {
        ob_implicit_flush();
        set_time_limit(300000);
        $VK = unserialize(file_get_contents('vk'));
        $analyzer = new Analyzer($this->db, $VK);
        $analyzer->analyze();
    }

    public function test()
    {
        print_r($_SESSION['VK']->getFriends());
    }

    public function finishlogin()
    {
        $_SESSION['VK'] = VK::login($_GET['code']);
        $s = serialize($_SESSION['VK']);
        file_put_contents('vk',$s);
        header('Location: /app/observables');
    }

    public function login()
    {
        header("Location: " . App::AUTH_LINK);
    }
}