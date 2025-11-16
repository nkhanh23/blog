<?php
class Category extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllCategory()
    {
        return $this->getALL("SELECT * FROM category");
    }

    public function getOneCategory($condition)
    {
        return $this->getOne("SELECT * FROM category Where $condition");
    }

    public function getRowCategory()
    {
        return $this->getRows('SELECT * from category');
    }

    public function insertCategory($data)
    {
        return $this->insert('category', $data);
    }

    public function insertPostCategory($data)
    {
        return $this->insert('post_category', $data);
    }

    public function updateCategory($data, $condition)
    {
        return $this->update('category', $data, $condition);
    }

    public function deleteCategory($condition)
    {
        return $this->delete('pocategorysts', $condition);
    }
}
