<?php
class BaseController
{
    protected function renderView($view, $data = [])
    {
        //Đổi các key với item trong mảng thành biến với giá trị
        extract($data); // chi ap dung voi mang 1 chieu
        require_once './app/Views/' . $view . '.php';
    }
}
