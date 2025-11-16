<?php
class PostController extends BaseController
{
    private $postModel;
    private $categoryModel;

    public function __construct()
    {
        $this->postModel = new Post;
        $this->categoryModel = new Category;
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
                $chuoiWhere .= "p.tittle LIKE '%$keyword%' OR p.tags LIKE '%$keyword%' ";
            }

            if (!empty($cate)) {
                if (strpos($chuoiWhere, 'WHERE') == false) {
                    $chuoiWhere .= ' WHERE ';
                } else {
                    $chuoiWhere .= ' AND ';
                }
                $chuoiWhere .= " c.id = $cate ";
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
        $sql = $sql = "SELECT p.*, GROUP_CONCAT(c.name SEPARATOR ', ') AS categories
        FROM posts AS p
        LEFT JOIN post_category pc ON p.id = pc.post_id
        LEFT JOIN category c ON pc.category_id = c.id
        $chuoiWhere
        GROUP BY p.id
        ORDER BY p.created_at DESC
        LIMIT $offset, $perPage
        ";
        $postDetail =  $this->postModel->getAllPosts($sql);
        $getCate = $this->categoryModel->getAllCategory();

        // Xử lý query
        if (!empty($_SERVER['QUERY_STRING'])) {
            $queryString = $_SERVER['QUERY_STRING'];
            //Xu ly &page= dồn trên url
            $queryString = str_replace('&page=' . $page, '', $queryString);
        }


        $data = [
            'postModel' => $this->postModel,
            'getCate' => $getCate,
            'postDetail' => $postDetail,
            'maxData' => $maxData,
            'perPage' => $perPage,
            'maxPage' => $maxPage,
            'offset' => $offset,
            'page' => $page,
            'cate' => $cate
        ];
        $this->renderView('layout-part/posts/list', $data);
    }

    public function showAdd()
    {
        $getGroup = $this->categoryModel->getAllCategory();
        $data = [
            'getGroup' => $getGroup
        ];
        $this->renderView('layout-part/posts/add', $data);
    }

    public function add()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            // echo '<pre>';
            // print_r($filter);
            // echo '</pre>';
            // die();
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
                    //Lấy ra id post vừa tạo
                    $postId = $this->postModel->getLastIdPosts();
                    $category = $filter['category_ids'];
                    //Kiểm tra xem người dùng có chọn category nào không
                    if (!empty($category)) {
                        foreach ($category as $item) {
                            $data = [
                                'post_id' => $postId,
                                'category_id' => $item
                            ];
                            //insert vào bảng post_category
                            $checkPostCategory = $this->categoryModel->insertPostCategory($data);
                            //Kiểm tra
                            if ($checkPostCategory) {
                                setSessionFlash('msg', 'Thêm bài viết thành công.');
                                setSessionFlash('msg_type', 'success');
                            } else {
                                setSessionFlash('msg', 'Thêm post_category thất bại.');
                                setSessionFlash('msg_type', 'danger');
                            }
                        }
                    }
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
        $filter = filterData('get');

        $condition = 'id=' . $filter['id'];
        $rel = $this->postModel->getOnePost($condition);
        $data = [
            'oldData' => $rel,
            'idPost' => $filter['id']
        ];

        $this->renderView('layout-part/posts/edit', $data);
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
                $dataUpdate = [
                    'tittle' => $filter['tittle'],
                    'content' => $filter['content'],
                    'tags' => $filter['tags'],
                    'minute_reads' => $filter['minute_reads'],
                    'views' => $filter['views'],
                    'comments' => $filter['comments'],
                    'shares' => $filter['shares'],
                    'updated_at' => date('Y:m:d H:i:s')
                ];
                $condition = 'id=' . $filter['idPost'];
                $insertStatus = $this->postModel->updatePosts($dataUpdate, $condition);
                if ($insertStatus) {
                    setSessionFlash('msg', 'Sửa bài viết thành công.');
                    setSessionFlash('msg_type', 'success');
                    reload('/posts');
                } else {
                    setSessionFlash('msg', 'Sửa bài viết thất bại.');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
                reload('/posts/edit');
            }
            $this->renderView('layout-part/posts/edit');
        }
    }

    public function delete()
    {
        $filter = filterData('get');
        if (!empty($filter)) {
            $posts_id = $filter['id'];
            $condition = 'id=' . $posts_id;
            $checkID = $this->postModel->getOnePost($condition);
            if (!empty($checkID)) {
                $deletePostCategoryStatus = $this->postModel->deletePostsCategory("post_id = $posts_id");
                if ($deletePostCategoryStatus) {
                    $deleteStatus = $this->postModel->deletePosts("id = $posts_id");
                    if ($deleteStatus) {
                        setSessionFlash('msg', 'Xoá bài viết thành công.');
                        setSessionFlash('msg_type', 'success');
                        reload('/posts');
                    }
                } else {
                    setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau.');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Bài viết không tồn tại.');
                setSessionFlash('msg_type', 'danger');
                reload('/posts');
            }
        } else {
            setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau.');
            setSessionFlash('msg_type', 'danger');
        }
    }
}
