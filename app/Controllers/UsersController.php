<?php
class UsersController extends BaseController
{
    private $userModel;
    public function __construct()
    {
        $this->userModel = new Users();
    }

    public function list()
    {
        $filter = filterData();
        $chuoiWhere = '';
        $group = '0';
        $keyword = '';

        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }
            if (isset($filter['group'])) {
                $group = $filter['group'];
            }

            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "a.fullname LIKE '%$keyword%' OR a.email LIKE '%$keyword%' ";
            }

            if (!empty($group)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= " a.group_id = $group ";
            }
        }

        //Xu ly phan trang
        $maxData = $this->userModel->getRowUsers("SELECT id FROM users"); // tổng dữ liệu
        $perPage = 5; //số dòng dữ liệu 1 trang
        $maxPage = ceil($maxData / $perPage); //tính max page
        $offset = 0;
        $page = 1;
        //get page
        if (isset($filter['page'])) {
            $page = $filter['page'];
        }

        if ($page > $maxPage || $page < 1) {
            $page = 1;
        }

        if (isset($page)) {
            $offset = ($page - 1) * $perPage;
        }

        $getDetailUser = $this->userModel->getAllUsers("SELECT a.id, a.fullname, a.email, a.created_at, b.name
        FROM users a INNER JOIN `groups` b
        ON a.group_id = b.id $chuoiWhere
        ORDER BY created_at DESC
        LIMIT $offset, $perPage
        ");

        $getGroup = $this->userModel->getAllGroups();
        // Xử lý query
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            //Xu ly &page= dồn trên url
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }

        if ($group > 0 || !empty($keyword)) {
            $maxData2 = $this->userModel->getRowUsers("SELECT id FROM users a $chuoiWhere");
            $maxPage = ceil($maxData2 / $perPage);
        }

        $data = [
            'getDetailUser' => $getDetailUser,
            'maxData' => $maxData,
            'perPage' => $perPage,
            'maxPage' => $maxPage,
            'offset' => $offset,
            'page' => $page,
            'getGroup' => $getGroup,
            'group' => $group,
            'keyword' => $keyword
        ];


        $this->renderView('layout-part/users/list', $data);
    }

    public function showAdd()
    {
        $data = [
            'userModel' => $this->userModel
        ];
        $this->renderView('layout-part/users/add', $data);
    }

    public function add()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            // validate fullname
            if (empty(trim($filter['fullname']))) {
                $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
            } else {
                if (strlen(trim($filter['fullname'])) < 5) {
                    $errors['fullname']['length'] = 'Họ tên phải lớn hơn 5 ký tự';
                }
            }

            // Validate email 
            if (empty(trim($filter['email']))) {
                $errors['email']['required'] = 'Email bắt buộc phải nhập';
            } else {
                // Đúng định dạng email, email này đã tồn tại trong CSDL chưa
                if (!validateEmail(trim($filter['email']))) {
                    $errors['email']['isEmail'] = 'Email không đúng định dạng';
                } else {
                    $email = $filter['email'];

                    $checkEamil = $this->userModel->getRowUsers("SELECT * FROM users WHERE email = '$email' ");
                    if ($checkEamil > 0) {
                        $errors['email']['check'] = 'Email đã tồn tại';
                    }
                }
            }

            // Validate phone
            if (empty($filter['phone'])) {
                $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
            } else {
                if (!isPhone($filter['phone'])) {
                    $errors['phone']['isPhone'] = 'Số điện thoại ko đúng định dạng';
                }
            }

            // Validate Password MK > 6 ký tự
            if (empty(trim($filter['password']))) {
                $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
            } else {
                if (strlen(trim($filter['password'])) < 6) {
                    $errors['password']['length'] = 'Mật khẩu phải lớn hơn 6 ký tự';
                }
            }
            $getGroup = $this->userModel->getAllUsers("SELECT * FROM `groups`");
            if (empty($errors)) {
                $dataInsert = [
                    'fullname' => $filter['fullname'],
                    'email' => $filter['email'],
                    'phone' => $filter['phone'],
                    'group_id' => $filter['group'],
                    'password' => password_hash($filter['password'], PASSWORD_DEFAULT),
                    'status' => $filter['status'],
                    'avartar' => '/templates/uploads/9-anh-dai-dien-trang-inkythuatso-03-15-27-03.jpg',
                    'address' => (!empty($filter['address']) ? $filter['address'] : null),
                    'created_at' => date('Y:m:d H:i:S')
                ];
                $insertStatus = $this->userModel->insert('users', $dataInsert);
                if ($insertStatus) {
                    setSessionFlash('msg', 'Thêm người dùng thành công.');
                    setSessionFlash('msg_type', 'success');
                    reload('/users');
                } else {
                    setSessionFlash('msg', 'Thêm người dùng thất bại.');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/users/add');
            }
            $this->renderView('layout-part/users/list');
        }
    }

    public function showEdit()
    {
        // Lấy id từ URL
        $filter = filterData('get');
        $user_id = !empty($filter['id']) ? $filter['id'] : 0;
        $getGroup = $this->userModel->getAllGroups();
        // Lấy thông tin user từ database
        $condition = 'id =' . $user_id;
        $detailUser = $this->userModel->getOneUsers($condition);
        $data = [
            'detailUser' => $detailUser,
            'getGroup' => $getGroup
        ];
        $this->renderView('layout-part/users/edit', $data);
    }

    public function edit()
    {
        $filter = filterData('get');
        $user_id = !empty($filter['id']) ? $filter['id'] : 0;
        // Lấy thông tin user từ database
        $condition = 'id =' . $user_id;
        $detailUser = $this->userModel->getOneUsers($condition);
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            // validate fullname
            if (empty(trim($filter['fullname']))) {
                $errors['fullname']['required'] = 'Họ tên bắt buộc phải nhập';
            } else {
                if (strlen(trim($filter['fullname'])) < 5) {
                    $errors['fullname']['length'] = 'Họ tên phải lớn hơn 5 ký tự';
                }
            }


            if ($filter['email'] != $detailUser['email']) {
                // Validate email
                if (empty(trim($filter['email']))) {
                    $errors['email']['required'] = 'Email bắt buộc phải nhập';
                } else {
                    // Đúng định dạng email, email này đã tồn tại trong CSDL chưa
                    if (!validateEmail(trim($filter['email']))) {
                        $errors['email']['isEmail'] = 'Email không đúng định dạng';
                    } else {
                        $email = $filter['email'];

                        $checkEamil = $this->userModel->getRowUsers("SELECT * FROM users WHERE email = '$email' ");
                        if ($checkEamil > 0) {
                            $errors['email']['check'] = 'Email đã tồn tại';
                        }
                    }
                }
            }

            // Validate phone
            if (empty($filter['phone'])) {
                $errors['phone']['required'] = 'Số điện thoại bắt buộc phải nhập';
            } else {
                if (!isPhone($filter['phone'])) {
                    $errors['phone']['isPhone'] = 'Số điện thoại ko đúng định dạng';
                }
            }

            // Validate Password MK > 6 ký tự
            if (empty(trim($filter['password']))) {
                if (strlen(trim($filter['password'])) < 6) {
                    $errors['password']['length'] = 'Mật khẩu phải lớn hơn 6 ký tự';
                }
            }
            if (empty($errors)) {
                $data = [
                    'fullname' => $filter['fullname'],
                    'email' => $filter['email'],
                    'phone' => $filter['phone'],
                    'group_id' => $filter['group'],
                    'status' => $filter['status'],
                    'address' => (!empty($filter['address']) ? $filter['address'] : null),
                    'updated_at' => date('Y:m:d H:i:S')
                ];

                if (!empty($filter['password'])) {
                    $data['password'] = password_hash($filter['password'], PASSWORD_DEFAULT);
                }
                $condition = "id=" . $user_id;
                $updateStatus = $this->userModel->update('users', $data, $condition);
                if ($updateStatus) {
                    setSessionFlash('msg', 'Cập nhật người dùng thành công.');
                    setSessionFlash('msg_type', 'success');
                    reload('/users');
                } else {
                    setSessionFlash('msg', 'Cập nhật người dùng thất bại.');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
            }
            $this->renderView('layout-part/users/edit');
        }
    }

    public function delete()
    {

        $getData = filterData('get');

        if (!empty($getData['id'])) {
            $user_id = $getData['id'];
            $checkUser = $this->userModel->getRowUsers("SELECT * FROM users WHERE id = $user_id");
            if ($checkUser > 0) {
                // xoá tài khoản
                $checkToken = $this->userModel->getRowUsers("SELECT * FROM token_login WHERE user_id = $user_id");
                if ($checkToken > 0) {
                    $this->userModel->deleteTokenLogin("user_id = $user_id");
                }
                $condition = 'id =' . $user_id;
                $checkDelete = $this->userModel->deleteUsers($condition);
                if ($checkDelete) {
                    setSessionFlash('msg', 'Xóa người dùng thành công.');
                    setSessionFlash('msg_type', 'success');
                    reload('/users');
                } else {
                    setSessionFlash('msg', 'Xóa người dùng thất bại.');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Người dùng không tồn tại.');
                setSessionFlash('msg_type', 'danger');
                reload('users');
            }
        }
    }
    public function dashboard()
    {
        $this->renderView('layout-part/dashboard');
    }
}
