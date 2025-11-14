<?php
if (!defined('_nkhanh')) {
    die('Truy cập không hợp lệ');
}

$data = [
    'title' => 'Danh sách người dùng'
];
layout('header', $data);
layout('side_bar');

$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
?>
<?php
//Header
$this->renderView('parts/sidebar');
?>
<div class="container grip-user">
    <div class="container-fluid">
        <a href="<?php echo _HOST_URL; ?>/posts/add" class="btn btn-success mb-3"><i class="fa-solid fa-plus"></i>Thêm
            mới
            Post</a>
        <?php
        if (!empty($msg) && !empty($msg_type)) {
            getMsg($msg, $msg_type);
        }
        ?>
        <form class="mb-3" action="" method="get">
            <input type="hidden" name="module" value="course">
            <input type="hidden" name="action" value="list">
            <div class="row">
                <div class="col-7">
                    <input type="text" class="form-control" value="<?php echo (!empty($keyword)) ? $keyword : false; ?>"
                        name="keyword" placeholder="Nhập thông tin tìm kiếm...">
                </div>
                <div class="col-2"><button class="btn btn-primary" type="submit">Tìm kiếm</button></div>
            </div>
        </form>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">STT</th>
                    <th scope="col">Tiêu đề</th>
                    <th scope="col">Nội dung</th>
                    <th scope="col">Ngày viết</th>
                    <th scope="col">Sửa</th>
                    <th scope="col">Xoá</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($courseDetail as $key => $item): ?>
                    <tr>
                        <th scope="row"><?php echo $key + 1; ?></th>
                        <td><?php echo $item['tittle']; ?></td>
                        <td><?php echo $item['content']; ?></td>
                        <td><?php echo $item['created_at']; ?></td>
                        <td><a href="<?php echo _HOST_URL; ?>/post/edit" class="btn btn-warning"><i
                                    class="fa-solid fa-pencil"></i></a></td>
                        <td><a href="<?php echo _HOST_URL; ?>/post/delete"
                                onclick="return confirm('Bạn có chắc chắn muốn xoá không?')" class="btn btn-danger"><i
                                    class="fa-solid fa-trash"></i></a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <!-- Xu ly nut truoc -->
                <?php
                if ($page > 1):
                ?>
                    <li class="page-item"><a class="page-link" href="list&page=<?php echo $page - 1; ?>">Trước</a></li>
                <?php endif;
                ?>
                <!-- Tinh vi tri bat dau -->
                <?php
                $start = $page - 1;
                if ($start < 1) {
                    $start = 1;
                }
                ?>
                <?php
                if ($start > 1):
                ?>
                    <li class="page-item"><a class="page-link" href="list&page=<?php echo $page - 1; ?>">...</a></li>
                <?php endif;
                $end = $page + 1;
                if ($end > $maxPage) {
                    $end = $maxPage;
                }
                ?>
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : false; ?>"><a class="page-link"
                            href="list&page=<?php echo $i; ?>"><?php echo $i; ?></a></li>

                <?php
                endfor;
                if ($end < $maxPage):
                ?>
                    <li class="page-item"><a class="page-link"
                            href="?<?php echo $queryString; ?>&page=<?php echo $page + 1; ?>">...</a></li>
                <?php endif;
                $end = $page + 2;
                if ($end > $maxPage) {
                    $end = $maxPage;
                }
                ?>
                <!-- Xu ly nut sau -->
                <?php
                if ($page < $maxPage):
                ?>
                    <li class="page-item"><a class="page-link"
                            href="?<?php echo $queryString; ?>&page=<?php echo $page + 1; ?>">Sau</a></li>

                <?php endif; ?>
            </ul>
        </nav>
    </div>
</div>

<?php
layout('footer');
