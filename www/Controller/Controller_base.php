<?php
/**
 * Created by JetBrains PhpStorm.
 * User: podko_000
 * Date: 07.09.13
 * Time: 21:32
 * To change this template use File | Settings | File Templates.
 */

abstract class Controller_base {
    protected $db;

    public function view($template)
    {
        include "/View/".$template.'.php';
        return true;
    }

    public function setDataBase($db)
    {
        $this->db = $db;
    }
}