    <?php
    $router->get('/dashboard', 'UsersController@dashboard');


    $router->get('/users', 'UsersController@index');
    $router->post('/users', 'UsersController@index');

    $router->get('/group', 'UsersController@index');
    $router->post('/group', 'UsersController@index');

    //khi truy cập vào /login thì sẽ truy cập vào AuthController và tới phương thức showLogin
    $router->get('/login', 'AuthController@showLogin');
    $router->post('/login', 'AuthController@login');

    $router->get('/register', 'AuthController@showRegister');
    $router->post('/register', 'AuthController@register');

    $router->get('/forgot', 'AuthController@showForgot');
    $router->post('/forgot', 'AuthController@forgot');

    $router->get('/active', 'AuthController@active');

    $router->get('/reset', 'AuthController@showReset');
    $router->post('/reset', 'AuthController@reset');

    // GIAO DIỆN POST ADMIN
    $router->get('/posts', 'PostController@list');

    $router->get('/posts/add', 'PostController@showAdd');
    $router->post('/posts/add', 'PostController@add');

    $router->get('/posts/edit', 'PostController@showEdit');
    $router->post('/posts/edit', 'PostController@edit');

    $router->get('/posts/delete', 'PostController@delete');

    // GIAO DIỆN USER ADMIN
    $router->get('/users', 'UsersController@list');

    $router->get('/users/add', 'UsersController@showAdd');
    $router->post('/users/add', 'UsersController@add');

    $router->get('/users/edit', 'UsersController@showEdit');
    $router->post('/users/edit', 'UsersController@edit');

    $router->get('/users/delete', 'UsersController@delete');


    $router->get('/', 'HomeController@index');
