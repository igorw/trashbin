<?php

require_once __DIR__.'/../vendor/autoload.php';

use Silex\Application;
use Silex\Extension\TwigExtension;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app = new Application();

require __DIR__.'/bootstrap.php';

$app->before(function () use ($app) {
    // set up some template globals
    $app['twig']->addGlobal('base_path', $app['request']->getBasePath());
    $app['twig']->addGlobal('index_url', $app['url_generator']->generate('homepage'));
    $app['twig']->addGlobal('create_url', $app['url_generator']->generate('create'));
    $app['twig']->addGlobal('languages', $app['app.languages']);
});

$app->get('/', function () use ($app) {
    $parentPasteId = $app['request']->get('parent');

    $parentPaste = null;
    if ($parentPasteId) {
        $parentPaste = $app['app.storage']->get($parentPasteId);
    }

    return $app['twig']->render('index.html', array(
        'paste'     => $parentPaste,
    ));
})
->bind('homepage');

$app->post('/', function () use ($app) {
    list($id, $paste) = $app['app.parser']->createPasteFromRequest($app['request']);

    $errors = $app['app.validator']->validate($paste);
    if ($errors) {
        $page = $app['twig']->render('index.html', array(
            'errors'    => $errors,
            'paste'     => $paste,
        ));
        return new Response($page, 400);
    }

    $app['app.storage']->set($id, $paste);

    return $app->redirect($app['url_generator']->generate('view', array('id' => $id)));
})
->bind('create');

$app->get('/about', function () use ($app) {
    return $app['twig']->render('about.html');
});

$app->get('/{id}', function ($id) use ($app) {
    $paste = $app['app.storage']->get($id);

    if (!$paste) {
        $app->abort(404, 'paste not found');
    }

    return $app['twig']->render('view.html', array(
        'copy_url'	=> $app['url_generator']->generate('homepage', array('parent' => $id)),
        'paste'		=> $paste,
    ));
})
->bind('view')
->assert('id', '[0-9a-f]{8}');

$app->error(function (Exception $e) use ($app) {
    if (!$app['catch_exceptions']) {
        return;
    }

    $code = ($e instanceof HttpException) ? $e->getStatusCode() : 500;

    return new Response($app['twig']->render('error.html', array(
        'message'	=> $e->getMessage(),
    )), $code);
});

return $app;
