<?php
if (!defined('_nkhanh')) {
    die('Truy cập không hợp lệ');
}
$data = [
    'tittle' => 'Reset mật khẩu'
];
layout('header-auth', $data);




$msg = getSessionFlash('msg');
$msg_type = getSessionFlash('msg_type');
$oldData = getSessionFlash('oldData');
$errorsArr  = getSessionFlash('errors');
?>



<div class="container">
    <section class="vh-100">
        <div class="container-fluid h-custom">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-md-9 col-lg-6 col-xl-5">
                    <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                        class="img-fluid" alt="Sample image">
                </div>
                <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                    <?php
                    if (!empty($msg) && !empty($msg_type)) {
                        getMsg($msg, $msg_type);
                    }
                    ?>
                    <form style="width: 23rem;" method="POST" action="" enctype="multipart/form-data">

                        <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Đặt lại mật khẩu</h3>

                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="password" name="password" class="form-control form-control-lg" />
                            <label class="form-label" for="form2Example28">Mật khẩu mới</label>
                            <?php
                            if (!empty($errorsArr)) {
                                echo formError($errorsArr, 'password');
                            }
                            ?>
                        </div>
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="password" name="confirm_pass" class="form-control form-control-lg" />
                            <label class="form-label" for="form2Example28">Nhập lại mật khẩu</label>
                            <?php
                            if (!empty($errorsArr)) {
                                echo formError($errorsArr, 'confirm_pass');
                            }
                            ?>
                        </div>
                        <div class="pt-1 mb-4">
                            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-info btn-lg btn-block"
                                type="submit">Gửi</button>
                        </div>
                        <p style="margin-top: 15px;"><a href="<?php echo _HOST_URL . '/login'; ?>"
                                class="link-danger">Đăng
                                nhập</a></p>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
layout('footer');
