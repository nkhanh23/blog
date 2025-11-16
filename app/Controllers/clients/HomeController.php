<?php
class HomeController extends BaseController
{
    private $postModel;
    private $categoryModel;
    public function __construct()
    {
        $this->postModel = new Post();
        $this->categoryModel = new Category();
    }
    public function index()
    {
        $checkGetAllPosts = $this->postModel->getAllPosts();
        $checkGetAllPostsAuthor = $this->postModel->getAllPostsAuthor();
        $getCategoryLimit4 = $this->categoryModel->get4Category();
        $data = [
            'getAllPosts' => $checkGetAllPosts,
            'getAllPostsAuthor' => $checkGetAllPostsAuthor,
            'getCategoryLimit4' => $getCategoryLimit4
        ];
        $this->renderView('layout/main-layout', $data);
    }
}
