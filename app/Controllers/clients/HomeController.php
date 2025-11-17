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
        $getPostsLimit7 = $this->postModel->get7Posts();
        $getAllCategory = $this->categoryModel->getAllCategory();
        $getAllPostsAuthorLimit = $this->postModel->getAllPostsAuthorLimit();
        $getAllPostCategoryTechnologyLimit = $this->postModel->getAllPostCategoryTechnologyLimit();
        $getTopPostViewLimit = $this->postModel->getTopPostViewLimit();

        //Top Story
        $getTopPostView = $this->postModel->getTopPostView();
        // What'news 
        $getAllPostCategorySports = $this->categoryModel->getAllPostCategorySports();
        $getAllPostCategoryMagazine = $this->categoryModel->getAllPostCategoryMagazine();
        $getAllPostCategoryPolitics = $this->categoryModel->getAllPostCategoryPolitics();
        $getAllPostCategoryTechnology = $this->categoryModel->getAllPostCategoryTechnology();
        $getAllPostCategoryFashion = $this->categoryModel->getAllPostCategoryFashion();
        //What'news side
        $getAllPostCategorySportsSide = $this->postModel->getAllPostCategorySportsSide();
        $getAllPostCategoryPoliticsSide = $this->postModel->getAllPostCategoryPoliticsSide();
        $getAllPostCategoryTechnologySide = $this->postModel->getAllPostCategoryTechnologySide();
        $getAllPostCategoryMagazineSide = $this->postModel->getAllPostCategoryMagazineSide();
        $getAllPostCategoryFashionSide = $this->postModel->getAllPostCategoryFashionSide();

        $data = [
            'getAllPosts' => $checkGetAllPosts,
            'getAllPostsAuthor' => $checkGetAllPostsAuthor,
            'getCategoryLimit4' => $getCategoryLimit4,
            'getPostsLimit7' => $getPostsLimit7,
            'getAllCategory' => $getAllCategory,
            'getAllPostCategoryTechnologyLimit' => $getAllPostCategoryTechnologyLimit,
            'getAllPostCategorySports' => $getAllPostCategorySports,
            'getAllPostCategoryMagazine' => $getAllPostCategoryMagazine,
            'getAllPostCategoryPolitics' => $getAllPostCategoryPolitics,
            'getAllPostCategoryTechnology' => $getAllPostCategoryTechnology,
            'getAllPostCategoryFashion' => $getAllPostCategoryFashion,
            'getAllPostCategorySportsSide' => $getAllPostCategorySportsSide,
            'getAllPostCategoryPoliticsSide' => $getAllPostCategoryPoliticsSide,
            'getAllPostCategoryTechnologySide' => $getAllPostCategoryTechnologySide,
            'getAllPostCategoryMagazineSide' => $getAllPostCategoryMagazineSide,
            'getAllPostCategoryFashionSide' => $getAllPostCategoryFashionSide,
            'getTopPostView' => $getTopPostView,
            'getTopPostViewLimit' => $getTopPostViewLimit,
            'getAllPostsAuthorLimit' => $getAllPostsAuthorLimit

        ];
        $this->renderView('layout/main-layout', $data);
    }
}
