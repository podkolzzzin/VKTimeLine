<?php
/**
 * Created by JetBrains PhpStorm.
 * User: podko_000
 * Date: 07.09.13
 * Time: 23:52
 * To change this template use File | Settings | File Templates.
 */

class Analyzer
{
    /**@var $VK VK */
    private $VK;
    /**@var $db DataBase */
    private $db;

    public function Analyzer($db, $VK)
    {
        $this->db = $db;
        $this->VK = $VK;
    }

    public function findObservables($uid = 'this', $deepness = 2)
    {
        if ($uid == 'this')
            $uid = $this->VK->getUser();
        $friends = $this->getUsers($uid, $deepness - 1);
        $this->updateDataBase($friends);

        $this->db->update('tUsers', $this->VK->getUser(),
            array(
                new DBFilter('cDeepnessChecked', $deepness, PDO::PARAM_INT)
            ));

        return $friends;
    }

    private function getUsers($uid, $deepness)
    {
        $result = $this->VK->getFriends($uid);
        echo "friends for $uid get<br>\r\n";
        echo "deepness: $deepness<br>\r\n";
        for ($i = 0; $i < $deepness; $i++)
        {
            $users = $result;
            foreach ($users as $u) {
                $result = array_merge($result, $this->VK->getFriends($u['uid']));
                echo "friends for {$u['uid']} get<br>\r\n";
            }
        }

        return $this->toUniqueArray($result);
    }

    private function toUniqueArray($friends)
    {
        $result = array();
        foreach ($friends as $f) {
            $result[$f['uid']] = $f;
        }

        return $result;
    }

    private function updateDataBase($friends)
    {
        foreach ($friends as $f) {
            if (!$this->db->getById($f['uid'], 'tUsers')) {
                $this->db->insert('tUsers', array(
                    new DBFilter('cId', $f['uid'], PDO::PARAM_INT),
                    new DBFilter('cName', $f['first_name'], PDO::PARAM_STR),
                    new DBFilter('cSurname', $f['last_name'], PDO::PARAM_STR)));
            }
        }
    }

    public function analyze()
    {
        DBSession::init($this->db);
        $state = $this->getCurrentState();

        foreach ($state as $userInfo) {
            $this->updateUser($userInfo);
        }
    }

    public function updateUser($uInfo)
    {
        $s = DBSession::getLatest($uInfo['uid']);
        if ($uInfo['online']) {

            if (!$s || $s['cUntil'] != -1) {
                #was offline
                $app = -1;
                $device = 1;
                if (isset($uInfo['online_app'])) {
                    $device = 3;
                    $app = $uInfo['online_app'];
                }
                if (isset($uInfo['online_mobile']))
                    $device = 2;

                DBSession::add($uInfo['uid'], time(), $device, $app);
            }
        } else {
            if ($s['cUntil'] == -1) {
                #was online
                print_r($uInfo['last_seen']['time']);
                //print_r($uInfo['last_seen']['time']);
                DBSession::finish($s['cId'], $uInfo['last_seen']['time']);
            }
        }
    }

    private function getCurrentState()
    {
        $users = $this->db->select('tUsers', array(new DBFilter('cDeepnessChecked', ANALYZE_LEVEL, PDO::PARAM_INT)))->fetchAll();
        echo 'analyzables: '.count($users)."<br>\r\n";
        $sessions = array();
        foreach ($users as $u) {
            $sessions = array_merge($sessions, $this->findObservables($u['cId'], ANALYZE_LEVEL));
        }

        return $sessions;
    }
}