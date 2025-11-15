<?php
class HomeController extends BaseController
{
    private $postModel;
    public function __construct()
    {
        $this->postModel = new Post();
    }
    public function index()
    {
        $checkGetAllPosts = $this->postModel->getAllPosts();
        $checkGetAllPostsAuthor = $this->postModel->getAllPostsAuthor();
        $data = [
            'getAllPosts' => $checkGetAllPosts,
            'getAllPostsAuthor' => $checkGetAllPostsAuthor
        ];
        $this->renderView('layout/main-layout', $data);
    }
}
