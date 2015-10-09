<?php $router = new \Slim\Slim();

//$router->hook('slim.before.dispatch', function () use ($router) {
//    ClientAuthenticator();
//});
/* ==============================
* ROUTES START HERE
=================================*/
    $router->get('/', function () {
        echo "Home sweet home!";
    });


    /* -----------
    * Posts
    --------------*/
    $Post = new Post;

    $router->get('/post', function () { header('Location: '.URI); exit; });

    $router->get('/post-term', function () { header('Location: '.URI); exit; });
    $router->get('/post-term/:params', function ($params) use ($Post) {
        $args = make_args($params);
        json( $Post->term($args) );
    });
    $router->post('/post-term', function () use ($Post, $router) {
        json( $Post->term_update( $router->request()->params() ) );
    });

    $router->get('/post-post', function () { header('Location: '.URI); exit; });
    $router->get('/post-post/:params', function ($params) use ($Post) {
        $args = make_args($params);
        json( $Post->post($args) );  
    });
    $router->post('/post-post', function () use ($Post, $router) {
        json( $Post->post_update( $router->request()->params() ) );
    });


    /* -----------
    * Comments
    --------------*/
    $Comment = new Comment;

    $router->get('/comment', function () { header('Location: '.URI); exit; });
    $router->get('/comment/:params', function ($params) use ($Comment) {
        $args = make_args($params);     
        json( $Comment->get($args) );        
    });

    // Post comment
    $router->post('/comment', function () use ($router, $Comment) {        
        json( $Comment->post( $router->request()->params() ) ); 
    });


    /* -----------
    * Media
    --------------*/
    






    $router->get('/server', function () {
        json($_SERVER);
    });

    $router->get('/info', function () {
        phpinfo();
    });

    $router->get('/test', function () use ($Comment) {
        echo 'Test test test ...';       
    });

/* ==============================
* ROUTES END HERE
=================================*/
$router->run();