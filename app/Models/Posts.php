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

    public function get7Posts()
    {
        return $this->getALL("SELECT * FROM posts LIMIT 7 ");
    }

    public function getAllPostsAuthor()
    {
        return $this->getALL("SELECT posts.*, authors.name as author_name FROM posts JOIN authors ON posts.author_id = authors.id");
    }

    public function getAllPostsAuthorLimit()
    {
        return $this->getALL("SELECT posts.*, authors.name as author_name FROM posts JOIN authors ON posts.author_id = authors.id ORDER BY views DESC LIMIT 5");
    }

    public function getOnePost($condition)
    {
        return $this->getOne("SELECT * FROM posts Where $condition");
    }

    public function getTopPostView()
    {
        return $this->getALL("SELECT * FROM posts ORDER BY views DESC LIMIT 1");
    }

    public function getTopPostViewLimit()
    {
        return $this->getALL("SELECT * FROM posts ORDER BY views DESC LIMIT 5");
    }

    public function getAllPostCategorySportsSide()
    {
        return $this->getALL("SELECT p.*, c.name
        FROM posts p
        JOIN post_category pc ON p.id = pc.post_id
        JOIN category c ON pc.category_id = c.id
        WHERE c.name = 'Sports'
        ORDER BY p.id ASC
        LIMIT 5 OFFSET 1;");
    }

    public function getAllPostCategoryMagazineSide()
    {
        return $this->getALL("SELECT p.*, c.name
        FROM posts p
        JOIN post_category pc ON p.id = pc.post_id
        JOIN category c ON pc.category_id = c.id
        WHERE c.name = 'Magazine'
        ORDER BY p.id ASC
        LIMIT 5 OFFSET 1;");
    }

    public function getAllPostCategoryPoliticsSide()
    {
        return $this->getALL("SELECT p.*, c.name
        FROM posts p
        JOIN post_category pc ON p.id = pc.post_id
        JOIN category c ON pc.category_id = c.id
        WHERE c.name = 'Politics'
        ORDER BY p.id ASC
        LIMIT 5 OFFSET 1;");
    }

    public function getAllPostCategoryTechnologySide()
    {
        return $this->getALL("SELECT p.*, c.name
        FROM posts p
        JOIN post_category pc ON p.id = pc.post_id
        JOIN category c ON pc.category_id = c.id
        WHERE c.name = 'Technology'
        ORDER BY p.id ASC
        LIMIT 5 OFFSET 1;");
    }

    public function getAllPostCategoryTechnologyLimit()
    {
        return $this->getALL("SELECT p.*, c.name, a.name as author_name
        FROM posts p
        JOIN post_category pc ON p.id = pc.post_id
        JOIN category c ON pc.category_id = c.id
        JOIN authors a ON p.author_id = a.id
        WHERE c.name = 'Technology'
        ORDER BY p.id ASC
        LIMIT 2;");
    }

    public function getAllPostCategoryFashionSide()
    {
        return $this->getALL("SELECT p.*, c.name
        FROM posts p
        JOIN post_category pc ON p.id = pc.post_id
        JOIN category c ON pc.category_id = c.id
        WHERE c.name = 'Fashion'
        ORDER BY p.id ASC
        LIMIT 5 OFFSET 1;");
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
