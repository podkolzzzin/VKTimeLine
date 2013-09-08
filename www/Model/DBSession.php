<?php
/**
 * Created by JetBrains PhpStorm.
 * User: podko_000
 * Date: 08.09.13
 * Time: 2:00
 * To change this template use File | Settings | File Templates.
 */

class DBSession
{
    /**@var $db DataBase */
    static $db;

    public static function init($db)
    {
        self::$db = $db;
    }

    public static function getLatest($uid)
    {
        $sql = self::$db->db->prepare("SELECT * , MAX(`cFrom`) FROM tsessions WHERE cUserId=?");

        $sql->bindValue(1, $uid);
        $sql->execute();
        if(!($r = $sql->fetch()))
            return null;
        elseif(!empty($r['cId']))
            return $r;
        else
            return null;
    }

    public static function add($uid, $time, $device, $app)
    {
        $sql = self::$db->db->prepare("INSERT INTO tsessions(cUserId, cFrom, cUntil, cDevice, cAppId) VALUES(?, ?, -1, ?, ?)");
        $sql->bindParam(1, $uid, PDO::PARAM_INT);
        $sql->bindParam(2, $time, PDO::PARAM_INT);
        $sql->bindParam(3, $device, PDO::PARAM_INT);
        $sql->bindParam(4, $app, PDO::PARAM_INT);
        $sql->execute();
    }

    public static function finish($cId, $last_seen)
    {
        $sql = self::$db->db->prepare("UPDATE tsessions SET cUntil=? WHERE cId=?");
        $sql->bindParam(1, $last_seen, PDO::PARAM_INT);
        $sql->bindParam(2, $cId, PDO::PARAM_INT);
        $sql->execute();
    }
}