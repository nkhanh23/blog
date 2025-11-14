<?php
class Users extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllUsers()
    {
        return $this->getALL("SELECT * FROM users");
    }

    public function insertUser($data)
    {
        return $this->insert('users', $data);
    }
}
