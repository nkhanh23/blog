<?php
class Users extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }


    public function getAllUsers($sql)
    {
        if (!empty($sql)) {
            return $this->getALL($sql);
        } else {
            return $this->getALL("SELECT * FROM users");
        }
    }

    public function getAllGroups()
    {
        return $this->getALL("SELECT * FROM groups");
    }

    public function getOneUsers($condition)
    {
        return $this->getOne("SELECT * FROM users Where $condition");
    }

    public function getRowUsers($sql)
    {
        if (!empty($sql)) {
            return $this->getRows($sql);
        } else {
            return $this->getRows("SELECT * FROM users");
        }
    }

    public function insertUsers($data)
    {
        return $this->insert('users', $data);
    }

    public function updateUsers($data, $condition)
    {
        return $this->update('users', $data, $condition);
    }

    public function deleteUsers($condition)
    {
        return $this->delete('users', $condition);
    }

    public function deleteTokenLogin($condition)
    {
        return $this->delete('token_login', $condition);
    }
}
