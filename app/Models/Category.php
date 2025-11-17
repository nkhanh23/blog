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

    public function get4Category()
    {
        return $this->getALL("SELECT * FROM category LIMIT 4 ");
    }

    public function getAllPostCategory($sql = '')
    {
        if (!empty($sql)) {
            return $this->getALL($sql);
        } else {
            return $this->getALL("SELECT * FROM category LIMIT 4 ");
        }
    }



    public function getAllPostCategorySports()
    {
        return $this->getALL("SELECT p.*,c.name
        FROM posts p
        JOIN post_category pc ON p.id = pc.post_id
        JOIN category c ON pc.category_id = c.id
        WHERE c.name = 'Sports';");
    }

    public function getAllPostCategoryMagazine()
    {
        return $this->getALL("SELECT p.*,c.name
        FROM posts p
        JOIN post_category pc ON p.id = pc.post_id
        JOIN category c ON pc.category_id = c.id
        WHERE c.name = 'Magazine';");
    }

    public function getAllPostCategoryPolitics()
    {
        return $this->getALL("SELECT p.*,c.name
        FROM posts p
        JOIN post_category pc ON p.id = pc.post_id
        JOIN category c ON pc.category_id = c.id
        WHERE c.name = 'Politics';");
    }

    public function getAllPostCategoryTechnology()
    {
        return $this->getALL("SELECT p.*,c.name
        FROM posts p
        JOIN post_category pc ON p.id = pc.post_id
        JOIN category c ON pc.category_id = c.id
        WHERE c.name = 'Technology';");
    }

    public function getAllPostCategoryFashion()
    {
        return $this->getALL("SELECT p.*,c.name
        FROM posts p
        JOIN post_category pc ON p.id = pc.post_id
        JOIN category c ON pc.category_id = c.id
        WHERE c.name = 'Fashion';");
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
