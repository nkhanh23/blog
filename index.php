    <?php
    session_start();
    /*
    Luồng chạy MVC
    M V C

    Load index.php -> router (xử lý url và điều hướng) -> gọi ra controller của url
    -> Xử lý bên trong controller -> gọi đến model để lấy dữ liệu ()
    -> trả dữ liệu về cho controller -> tại controller truyền dữ liệu đẩy cho view
    -> view hiển thị kết quả cho người dùng.
    */
    //Dung ham glob de noi toi cac file php trong 1 folder
    foreach (glob(__DIR__ . '/configs/*.php') as $filename) {
        require_once $filename;
    }

    foreach (glob(__DIR__ . '/core/*.php') as $filename) {
        require_once $filename;
    }

    require_once './core/mailer/Exception.php';
    require_once './core/mailer/PHPMailer.php';
    require_once './core/mailer/SMTP.php';

    $router = new Router();

    foreach (glob(__DIR__ . '/routers/*.php') as $filename) {
        require_once $filename;
    }

    foreach (glob(__DIR__ . '/app/Models/*.php') as $filename) {
        require_once $filename;
    }

    foreach (glob(__DIR__ . '/app/Controllers/*.php') as $filename) {
        require_once $filename;
    }

    $projectName = '/MVC';
    //lay url tren thanh dia chi
    $requestUrl =  str_replace($projectName, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $methodRes = $_SERVER['REQUEST_METHOD'];
    $router->xulyPath($methodRes, $requestUrl);

    //Dùng để lấy tên và avartar bỏ vào header
    $corer = new CoreModel();
    $getInfor = $corer->getUserInfo();
    setSession('getInfor', $getInfor);
