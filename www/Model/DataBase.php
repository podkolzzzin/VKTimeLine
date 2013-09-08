<?php
/**
 * Created by JetBrains PhpStorm.
 * User: podko_000
 * Date: 07.09.13
 * Time: 22:42
 * To change this template use File | Settings | File Templates.
 */

class DBFilter
{
    public $field, $value, $type;

    public function DBFilter($field, $value, $type)
    {
        $this->field = $field;
        $this->value = $value;
        $this->type = $type;
    }
}

class DataBase {
    public $db;

    public function DataBase()
    {
        $this->db = new PDO("mysql:host=localhost;dbname=vksessions", "root");
    }

    public function getById($id, $table)
    {
        return $this->select($table, array(new DBFilter('cId', $id, PDO::PARAM_INT)))->fetch();
    }

    public function insert($table, $values)
    {
        $table=strtolower($table);
        $sqlString = "INSERT INTO `$table` ( ";
        $i = 0;
        foreach ($values as $item) {
            $i++;
            $sqlString .= '`' . $item->field . '`';
            if ($i != count($values)) {
                $sqlString .= ', ';
            }
        }
        $i = 0;
        $sqlString .= ") VALUES(";
        foreach ($values as $item) {
            $i++;
            $sqlString .= '?';
            if ($i != count($values)) {
                $sqlString .= ', ';
            }
        }

        $sqlString .= ");";
        $i = 1;
        $sql = $this->db->prepare($sqlString);
        foreach ($values as $item) {
            $sql->bindParam($i, $item->value, $item->type);
            $i++;
        }

        if (!$sql->execute()) {
            print_r($sql->errorInfo());
            return false;
        }
        $sqlResult = $this->db->query("SELECT LAST_INSERT_ID() FROM `$table`")->fetch();
        return $sqlResult[0];
    }

    public function select($table, $filters)
    {
        $table=strtolower($table);
        $sqlString = "SELECT * FROM `$table` WHERE ";
        $i = 0;
        foreach ($filters as $item) {
            $i++;
            $sqlString .= '`' . $item->field . '`=?';
            if ($i != count($filters)) {
                $sqlString .= ' AND ';
            }
        }
        $sqlString.=" ORDER BY `cId` DESC";
        $sql = $this->db->prepare($sqlString);
        $i = 1;
        foreach ($filters as $item) {
            $sql->bindParam($i, $item->value, $item->type);
            $i++;
        }
        if (!$sql->execute()) {
            print_r($sql->errorInfo());
            return null;
        }
        return $sql;
    }

    public  function update($table, $id, $values)
    {
        $table=strtolower($table);
        $sqlString = "UPDATE `$table` SET ";
        $i = 0;
        foreach ($values as $item) {
            $i++;
            $sqlString .= '`' . $item->field . '` = ?';
            if ($i != count($values)) {
                $sqlString .= ', ';
            }
        }
        $sqlString .= " WHERE `cId`=?";
        $i = 1;
        $sql = $this->db->prepare($sqlString);
        foreach ($values as $item) {
            $sql->bindParam($i, $item->value, $item->type);
            $i++;
        }
        $sql->bindParam($i, $id, PDO::PARAM_INT);
        if (!$sql->execute()) {
            print_r($sql->errorInfo());
            return false;
        }
        return true;
    }
}