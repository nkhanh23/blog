<?php
class CategoryController extends BaseController
{
    private $categoryModel;
    public function __construct()
    {
        $this->categoryModel = new Category;
    }
}
