<?php
class Post extends CoreModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPosts($sql = '')
    {
        if (!empty($sql)) {
            return $this->getALL($sql);
        } else {
            return $this->getALL("SELECT * FROM posts");
        }
    }

    public function getAllPostsAuthor()
    {
        return $this->getALL("SELECT posts.*, authors.name as author_name FROM posts JOIN authors ON posts.author_id = authors.id");
    }

    public function getOnePost($condition)
    {
        return $this->getOne("SELECT * FROM posts Where $condition");
    }

    public function getRowPosts()
    {
        return $this->getRows('SELECT * from posts');
    }

    public function getLastIdPosts()
    {
        return parent::getLastID();
    }

    public function insertPosts($data)
    {
        return $this->insert('posts', $data);
    }

    public function updatePosts($data, $condition)
    {
        return $this->update('posts', $data, $condition);
    }

    public function deletePosts($condition)
    {
        return $this->delete('posts', $condition);
    }

    public function deletePostsCategory($condition)
    {
        return $this->delete('post_category', $condition);
    }
}
