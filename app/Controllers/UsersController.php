<?php
class UsersController extends BaseController
{
    public function index()
    {
        $user = new Users;
        $userDetail = $user->getAllUsers();

        // ob_start();
        // $this->renderView('layout-part/users', $userDetail);
        // $data = [
        //     'content' => ob_get_clean()
        // ];

        $this->renderView('layout/main-layout');
    }

    public function dashboard()
    {
        $this->renderView('layout-part/dashboard');
    }
}
