<?php
if (!defined('_nkhanh')) {
    die('Truy cập không hợp lệ');
}

$data = [
    'title' => 'Danh sách khóa học'
];
layout('header', $data);
layout('sidebar');


$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$oldData = getSessionFlash('oldData');
$errorsArr  = getSessionFlash('errors');

?>
<div class="container add-user">
    <?php
    if (!empty($msg) && !empty($msg_type)) {
        getMsg($msg, $msg_type);
    }
    ?>
    <form action="" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-12 pb-3">
                <label for="name">Tiêu đề</label>
                <input id="fullname" name="tittle" type="text" value="<?php
                                                                        if (!empty($oldData)) {
                                                                            echo oldata($oldData, 'tittle');
                                                                        }
                                                                        ?>" class="form-control" placeholder="Tiêu đề">
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'tittle');
                }
                ?>
            </div>
            <div class="col-12 pb-3">
                <label for="content">Nội dung</label>
                <textarea id="content" name="content" type="text" value="<?php
                                                                            if (!empty($oldData)) {
                                                                                echo oldata($oldData, 'content');
                                                                            } ?>" class="form-control"
                    placeholder="Nội dung">
                </textarea>
                <?php
                if (!empty($errorsArr)) {
                    echo formError($errorsArr, 'content');
                }
                ?>
            </div>
            <div class="col-12 pb-3">
                <label for="tags">Tag</label>
                <textarea id="tags" name="tags" type="text" value="<?php
                                                                    if (!empty($oldData)) {
                                                                        echo oldata($oldData, 'tags');
                                                                    } ?>" class="form-control" placeholder="Tag">
                </textarea>
            </div>
            <div class="col-3 pb-3">
                <label for="minute_reads">Thời gian đọc</label>
                <input id="minute_reads" name="minute_reads" type="text" value="<?php
                                                                                if (!empty($oldData)) {
                                                                                    echo oldata($oldData, 'minute_reads');
                                                                                } ?>" class="form-control"
                    placeholder="Mật khẩu">
            </div>
            <div class="col-3 pb-3">
                <label for="views">Lượt xem</label>
                <input id="views" name="views" type="text" value="<?php
                                                                    if (!empty($oldData)) {
                                                                        echo oldata($oldData, 'views');
                                                                    } ?>" class="form-control" placeholder="Mật khẩu">
            </div>
            <div class="col-3 pb-3">
                <label for="comments">Bình luận</label>
                <input id="comments" name="comments" type="text" value="<?php
                                                                        if (!empty($oldData)) {
                                                                            echo oldata($oldData, 'comments');
                                                                        } ?>" class="form-control"
                    placeholder="Mật khẩu">
            </div>
            <div class="col-3 pb-3">
                <label for="shares">Lượt chia sẻ</label>
                <input id="shares" name="shares" type="text" value="<?php
                                                                    if (!empty($oldData)) {
                                                                        echo oldata($oldData, 'shares');
                                                                    } ?>" class="form-control" placeholder="Mật khẩu">
            </div>

        </div>
        <button type="submit" class="btn btn-success">Xác nhận</button>

    </form>

</div>

<?php
layout('footer');
