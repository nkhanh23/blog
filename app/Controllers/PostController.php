<?php
class PostController extends BaseController
{
    private $postModel;

    public function __construct()
    {
        $this->postModel = new Post;
    }

    public function list()
    {
        $filter = filterData();
        $chuoiWhere = '';
        $cate = '0';
        $keyword = '';

        if (isGet()) {
            if (isset($filter['keyword'])) {
                $keyword = $filter['keyword'];
            }
            if (isset($filter['cate'])) {
                $cate = $filter['cate'];
            }

            if (!empty($keyword)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= "a.name LIKE '%$keyword%' OR a.description LIKE '%$keyword%' ";
            }

            if (!empty($cate)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= " a.category_id = $cate ";
            }
        }

        //Xu ly phan trang
        $maxData = $this->postModel->getRowPosts("SELECT id FROM post"); // tổng dữ liệu
        $perPage = 3; //số dòng dữ liệu 1 trang
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

        $courseDetail =  $this->postModel->getAllPosts("SELECT * from posts $chuoiWhere
        LIMIT $offset, $perPage
        ");


        // Xử lý query
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            //Xu ly &page= dồn trên url
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }


        $data = [
            'postModel' => $this->postModel,
            'courseDetail' => $courseDetail,
            'maxData' => $maxData,
            'perPage' => $perPage,
            'maxPage' => $maxPage,
            'offset' => $offset,
            'page' => $page
        ];
        $this->renderView('layout-part/posts/list', $data);
    }

    public function showAdd()
    {
        $this->renderView('layout-part/posts/add');
    }

    public function add()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            // validate fullname
            if (empty(trim($filter['tittle']))) {
                $errors['tittle']['required'] = 'Tên bắt buộc phải nhập';
            }

            // Validate slug
            if (empty(trim($filter['content']))) {
                $errors['content']['required'] = 'Đường dẫn bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $dataInsert = [
                    'tittle' => $filter['tittle'],
                    'content' => $filter['content'],
                    'tags' => $filter['tags'],
                    'minute_reads' => $filter['minute_reads'],
                    'views' => $filter['views'],
                    'comments' => $filter['comments'],
                    'shares' => $filter['shares'],
                    'created_at' => date('Y:m:d H:i:s')
                ];
                $insertStatus = $this->postModel->insertPosts($dataInsert);
                if ($insertStatus) {
                    setSessionFlash('msg', 'Thêm bài viết thành công.');
                    setSessionFlash('msg_type', 'success');
                    reload('/posts');
                } else {
                    setSessionFlash('msg', 'Thêm bài viết thất bại.');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/posts/add');
            }
            $this->renderView('layout-part/posts/list');
        }
    }

    public function showEdit()
    {
        $this->renderView('layout-part/posts/edit');
    }

    public function edit()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            // validate fullname
            if (empty(trim($filter['tittle']))) {
                $errors['tittle']['required'] = 'Tên bắt buộc phải nhập';
            }

            // Validate slug
            if (empty(trim($filter['content']))) {
                $errors['content']['required'] = 'Đường dẫn bắt buộc phải nhập';
            }

            if (empty($errors)) {
                $dataInsert = [
                    'tittle' => $filter['tittle'],
                    'content' => $filter['content'],
                    'tags' => $filter['tags'],
                    'minute_reads' => $filter['minute_reads'],
                    'views' => $filter['views'],
                    'comments' => $filter['comments'],
                    'shares' => $filter['shares'],
                    'created_at' => date('Y:m:d H:i:s')
                ];
                $insertStatus = $this->postModel->insertPosts($dataInsert);
                if ($insertStatus) {
                    setSessionFlash('msg', 'Thêm bài viết thành công.');
                    setSessionFlash('msg_type', 'success');
                    reload('/posts');
                } else {
                    setSessionFlash('msg', 'Thêm bài viết thất bại.');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/posts/add');
            }
            $this->renderView('layout-part/posts/list');
        }
    }
}
