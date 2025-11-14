<?php
class AuthController extends BaseController
{
    private $coreModel;
    public function __construct()
    {
        $this->coreModel = new CoreModel;
    }

    public function showLogin()
    {
        $this->renderView('layout-part/login');
    }

    //Xử lý đăng nhập
    public function login()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];
            //validate email
            if (empty(trim($filter['email']))) {
                $errors['email']['required'] = 'Email bắt buộc phải nhập';
            } else {
                //Kiểm tra đúng định dạng email
                if (!validateEmail($filter['email'])) {
                    $errors['email']['isEmail'] = 'Email không đúng định dạng';
                }
            }

            //validate mật khẩu
            if (empty(trim($filter['password']))) {
                $errors['password']['required'] = 'Mật khẩu bắt buộc nhập';
            } else {
                if (strlen(trim($filter['password'])) < 8) {
                    $errors['password']['length'] = ' Mật khẩu phải trên 8 kí tự';
                }
            }

            if (empty($errors)) {
                //Kiem tra dữ liệu
                $email = $filter['email'];
                $password = $filter['password'];
                // $checkEmail = getOne("SELECT * FROM users WHERE email ='$email'");
                $sql = "SELECT id, password FROM users WHERE email = '$email' AND status='1'";
                $checkStatus = $this->coreModel->getOne($sql);
                if (!empty($checkStatus)) {
                    if (!empty($checkStatus['password'])) {
                        $checkPass = password_verify($password, $checkStatus['password']);
                        if ($checkPass) {
                            // TK chi login o 1 noi
                            $user_Id = $checkStatus['id'];
                            // Tạo token và insert vào bảng token_login
                            $tokenLogin = sha1(uniqid() . time());

                            $data = [
                                'user_id' => $user_Id,
                                'created_at' => date("Y:m:d H:i:s"),
                                'token' => $tokenLogin
                            ];

                            //Insert vao bang login token
                            $insertLogin = $this->coreModel->insert('token_login', $data);
                            if ($insertLogin) {
                                //Chuyen huong sang trang dashboard
                                setSession('tokenLogin', $tokenLogin);
                                reload('/dashboard');
                            } else {
                                setSessionFlash('msg', 'Lỗi hệ thống! Bạn không thể đăng nhập lúc này');
                                setSessionFlash('msg_type', 'danger');
                            }
                        } else {
                            setSessionFlash('msg', 'Password hoặc mật khẩu không đúng.');
                            setSessionFlash('msg_type', 'danger');
                        }
                    } else {
                        setSessionFlash('msg ', 'Mật khẩu không đúng, hoặc email của bạn chưa được kích hoạt');
                        setSessionFlash('msg_type', 'danger');
                    }
                } else {
                    setSessionFlash('msg', 'Mật khẩu không đúng, hoặc email của bạn chưa được kích hoạt');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
            }

            reload('/login');
        }
    }

    public function showRegister()
    {
        $this->renderView('layout-part/register');
    }

    public function active()
    {
        $data = [
            'coreModel' => $this->coreModel
        ];
        $this->renderView('layout-part/active', $data);
    }

    public function showReset()
    {
        $this->renderView('layout-part/reset');
    }

    public function reset()
    {
        $filterGet = filterData('get');

        if (!empty($filterGet['token'])) {
            $tokenReset = $filterGet['token'];
        }

        if (!empty($tokenReset)) {
            // Check token có chính xác hay không
            $checkToken = $this->coreModel->getOne("SELECT * FROM users WHERE forget_token = '$tokenReset'");
            if (!empty($checkToken)) {
                if (isPost()) {
                    $filter = filterData();
                    $errors = [];

                    // Validate Password MK > 6 ký tự
                    if (empty(trim($filter['password']))) {
                        $errors['password']['required'] = 'Mật khẩu bắt buộc phải nhập';
                    } else {
                        if (strlen(trim($filter['password'])) < 6) {
                            $errors['password']['length'] = 'Mật khẩu phải lớn hơn 6 ký tự';
                        }
                    }

                    // Validate confirm password
                    if (empty(trim($filter['confirm_pass']))) {
                        $errors['confirm_pass']['required'] = 'Vui lòng nhập lại mật khẩu';
                    } else {
                        if (trim($filter['password']) !== trim($filter['confirm_pass'])) {
                            $errors['confirm_pass']['like'] = 'Mật khẩu nhập lại không khớp';
                        }
                    }

                    if (empty($errors)) {
                        $password = password_hash($filter['password'], PASSWORD_DEFAULT);
                        $data = [
                            'password' => $password,
                            'forget_token' => null,
                            'updated_at' => date('Y:m:d H:i:s')
                        ];

                        $condition = "id=" . $checkToken['id'];
                        $updateStatus = $this->coreModel->update('users', $data, $condition);

                        if ($updateStatus) {
                            $emailTo = $checkToken['email'];
                            // Subject từ nội dung trong ảnh
                            $subject = 'Đổi mật khẩu thành công!!';

                            // Content được cập nhật nội dung từ ảnh, giữ nguyên định dạng HTML
                            $content = '<div style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 30px;">
  <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    
    <div style="background-color: #007bff; color: #ffffff; padding: 20px; text-align: center;">
      <h2 style="margin: 0;">Đổi mật khẩu thành công!</h2>
    </div>
    
    <div style="padding: 30px; color: #333333; line-height: 1.6;">
      
      <p>Chúc mừng bạn đã đổi mật khẩu thành công trên nkhanh.</p>
      
      <p>Nếu không phải bạn thao tác đổi mật khẩu thì hãy liên hệ ngay với admin.</p>

      <hr style="border: none; border-top: 1px solid #eee; margin: 25px 0;">
      
      <p style="text-align: center; font-size: 14px; color: #888;">Cảm ơn các bạn đã ủng hộ nkhanh!!!</p>
    </div>
  </div>
</div>';

                            // Gửi email
                            sendMail($emailTo, $subject, $content);
                            setSessionFlash('msg', 'Đổi mật khẩu thành công.');
                            setSessionFlash('msg_type', 'success');
                        } else {
                            setSessionFlash('msg', 'Đã có lỗi xảy ra, vui lòng thử lại sau.');
                            setSessionFlash('msg_type', 'danger');
                        }
                    } else {
                        setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu vào.');
                        setSessionFlash('msg_type', 'danger');
                        setSessionFlash('oldData', $filter);
                        setSessionFlash('errors', $errors);
                    }
                }
            } else {
                getMsg('Liên kết đã hết hạn hoặc không tồn tại', 'danger');
            }
        } else {
            getMsg('Liên kết đã hết hạn hoặc không tồn tại', 'danger');
        }
        reload('/reset');
    }

    public function register()
    {
        $msg = $msg_type = null;
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

                    $checkEamil = $this->coreModel->getRows("SELECT * FROM users WHERE email = '$email' ");
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

            // Validate confirm password
            if (empty(trim($filter['password']))) {
                $errors['confirm_pass']['required'] = 'Vui lòng nhập lại mật khẩu';
            } else {
                if (trim($filter['password']) !== trim($filter['confirm_pass'])) {
                    $errors['confirm_pass']['like'] = 'Mật khẩu nhập lại không khớp';
                }
            }

            if (empty($errors)) {
                $activeToken = sha1(uniqid() . time());
                $data = [
                    'fullname' => $filter['fullname'],
                    'email' => $filter['email'],
                    'phone' => $filter['phone'],
                    'password' => password_hash($filter['password'], PASSWORD_DEFAULT),
                    'avartar' => _HOST_URL . '/public/uploads/9-anh-dai-dien-trang-inkythuatso-03-15-27-03.jpg',
                    'active_token' => $activeToken,
                    'created_at' => date('Y:m:d H:i:s'),

                ];
                $insertStatus = $this->coreModel->insert('users', $data);

                if ($insertStatus) {
                    $emailTo = $filter['email'];
                    $subject = 'Kích hoạt tài khoản';
                    $content = '<div style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 30px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    
        <div style="background-color: #007bff; color: #ffffff; padding: 20px; text-align: center;">
        <h2 style="margin: 0;">Kích hoạt tài khoản của bạn</h2>
        </div>
    
        <div style="padding: 30px; color: #333333; line-height: 1.6;">
        <p>Xin chào <strong>' . htmlspecialchars($filter["fullname"]) . '</strong>,</p>
        <p>Chúc mừng bạn đã đăng ký tài khoản thành công trên hệ thống <strong>nkhanh</strong>!</p>
        <p>Để kích hoạt tài khoản, vui lòng nhấn vào nút bên dưới:</p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="' . _HOST_URL . '/active?token=' . $activeToken . '" 
            style="background-color: #007bff; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; font-weight: bold; display: inline-block;">
            Kích hoạt tài khoản
            </a>
        </div>

        <p>Nếu nút trên không hoạt động, bạn có thể truy cập đường link sau:</p>
        <p style="word-break: break-all; color: #007bff;">' . _HOST_URL . '/active?token=' . $activeToken . '</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 25px 0;">
        
        <p style="text-align: center; font-size: 14px; color: #888;">Cảm ơn bạn đã tin tưởng và ủng hộ <strong>nkhanh</strong> ❤️</p>
        </div>
  </div>
    </div>';
                    sendMail($emailTo, $subject, $content);

                    setSessionFlash('msg', 'Đăng kí thành công, vui lòng kích hoạt tài khoản');
                    setSessionFlash('msg_type', 'success');
                } else {
                    setSessionFlash('msg', 'Đăng kí không thành công, vui lòng thử lại sau');
                    setSessionFlash('msg_type', 'danger');
                }
            } else {

                setSessionFlash('msg', 'Vui lòng kiểm tra dữ liệu nhập vào.');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
            }
            reload('/register');
        }
    }

    public function showForgot()
    {
        $this->renderView('layout-part/forgot');
    }

    public function forgot()
    {
        if (isPost()) {
            $filter = filterData();
            $errors = [];

            // Validate email
            if (empty(trim($filter['email']))) {
                $errors['email']['required'] = 'Email bắt buộc phải nhập';
            } else {
                // Đúng định dạng email, email này đã tồn tại trong CSDL chưa
                if (!validateEmail(trim($filter['email']))) {
                    $errors['email']['isEmail'] = 'Email không đúng định dạng';
                }
            }
            if (empty($errors)) {
                //xử lý và gửi mail
                if (!empty($filter['email'])) {
                    $email = $filter['email'];

                    $checkEmail = $this->coreModel->getOne("SELECT * FROM users WHERE email = '$email'");
                    if (!empty($checkEmail)) {
                        // Update forgot_token vào bảng users.
                        $forgot_token = sha1(uniqid() . time());
                        $data = [
                            'forget_token' => $forgot_token
                        ];
                        $condition = "id=" . $checkEmail['id'];
                        $updateStatus = $this->coreModel->update('users', $data, $condition);
                        if ($updateStatus) {
                            $emailTo = $filter['email'];
                            $subject = 'Yêu cầu đặt lại mật khẩu - nkhanh';

                            $content = '<div style="font-family: Arial, sans-serif; background-color: #f5f7fa; padding: 30px;">
  <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
    
    <div style="background-color: #007bff; color: #ffffff; padding: 20px; text-align: center;">
      <h2 style="margin: 0;">Đặt lại mật khẩu của bạn</h2>
    </div>
    
    <div style="padding: 30px; color: #333333; line-height: 1.6;">
      <p>Xin chào</p>
      <p>Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn trên hệ thống <strong>nkhanh</strong>.</p>
      <p>Để tạo mật khẩu mới, vui lòng nhấn vào nút bên dưới:</p>

      <div style="text-align: center; margin: 30px 0;">
        <a href="' . _HOST_URL . '/reset?token=' . $forgot_token . '" 
           style="background-color: #007bff; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; font-weight: bold; display: inline-block;">
          Đặt lại mật khẩu
        </a>
      </div>

      <p>Nếu nút trên không hoạt động, bạn có thể truy cập đường link sau:</p>
      <p style="word-break: break-all; color: #007bff;">' . _HOST_URL . '/reset?token=' . $forgot_token . '</p>
      
      <p style="font-size: 14px; color: #888; margin-top: 20px;">Lưu ý: Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email. Tài khoản của bạn vẫn an toàn.</p>

      <hr style="border: none; border-top: 1px solid #eee; margin: 25px 0;">
      
      <p style="text-align: center; font-size: 14px; color: #888;">Cảm ơn bạn đã tin tưởng và ủng hộ <strong>nkhanh</strong> ❤️</p>
    </div>
  </div>
</div>';

                            // Gửi email
                            sendMail($emailTo, $subject, $content);

                            setSessionFlash('msg', 'Reset mật khẩu thành công.');
                            setSessionFlash('msg_type', 'success');
                        } else {
                            setSessionFlash('msg', 'Đã có lỗi. Vui lòng thử lại sau');
                            setSessionFlash('msg_type', 'danger');
                        }
                    }
                }
            } else {
                setSessionFlash('msg', 'Vui lòng kiểm tra lại dữ liệu nhập vào.');
                setSessionFlash('msg_type', 'danger');
                setSessionFlash('oldData', $filter);
                setSessionFlash('errors', $errors);
            }
            reload('/forgot');
        }
    }
}
