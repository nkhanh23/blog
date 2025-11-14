<?php
// Khai báo database
const _HOST = 'localhost';
const _DB = 'blog';
const _USER = 'root';
const _PASS = '';
const _DRIVER = 'mysql';

define('_nkhanh', true);

// debug error
const _DEBUG = true; //Khi chạy chương trình và gặp lỗi thì bật true lên thì sẽ hiển thị lỗi, false sẽ tắt đi

// thiết lập đường dẫn host
define('_HOST_URL', 'http://' . ($_SERVER['HTTP_HOST']) . '/MVC');
define('_HOST_URL_PUBLIC', _HOST_URL . '/public');
