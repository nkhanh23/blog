<?php
class Groups extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllGroups()
    {
        return $this->getALL("SELECT * FROM groups");
    }

    public function insertUser($data)
    {
        return $this->insert('groups', $data);
    }
}
