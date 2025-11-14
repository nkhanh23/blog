<?php
class Post extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPosts()
    {
        return $this->getALL("SELECT * FROM posts");
    }

    public function getRowPosts()
    {
        return $this->getRows('SELECT * from posts');
    }

    public function insertPosts($data)
    {
        return $this->update('posts', $data);
    }

    public function updatePosts($data, $condition)
    {
        return $this->update('posts', $data, $condition);
    }

    public function deletePosts($condition)
    {
        return $this->delete('post', $condition);
    }
}
