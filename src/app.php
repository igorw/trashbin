<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once __DIR__.'/../vendor/.composer/autoload.php';

use Silex\Application;
use Silex\Extension\TwigExtension;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BaseHttpException;
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
    $parentId = $app['request']->get('parent', false);
    $parent = false;
    if ($parentId) {
        $parent = $app['predis']->hgetall($parentId);
    }

    return $app['twig']->render('index.html', array(
        'paste'     => $parent,
    ));
})
->bind('homepage');

$app->get('/create', function () use ($app) {
    return $app->redirect($app['url_generator']->generate('homepage'));
});

$app->post('/create', function () use ($app) {
    $content = preg_replace('#\r?\n#', "\n", $app['request']->get('content', ''));

    $id = substr(hash('sha512', $content . time() . rand(0, 255)), 0, 8);

    $paste = array(
        'content'   => $content,
    );

    if ('' === trim($paste['content'])) {
        return $app['twig']->render('index.html', array(
            'error_msg'	=> 'you must enter some content',
            'paste'		=> $paste,
        ));
    }

    $language = $app['request']->get('language', '');
    if (in_array($language, $app['app.languages'])) {
        $paste['language'] = $language;
    }

    $app['predis']->hmset($id, $paste);

    return $app->redirect($app['url_generator']->generate('view', array('id' => $id)));
})
->bind('create');

$app->get('/about', function () use ($app) {
    return $app['twig']->render('about.html');
});

$app->get('/{id}', function ($id) use ($app) {
    $paste = $app['predis']->hgetall($id);

    if (!$paste) {
        throw new NotFoundHttpException('paste not found');
    }

    return $app['twig']->render('view.html', array(
        'copy_url'	=> $app['url_generator']->generate('homepage', array('parent' => $id)),
        'paste'		=> $paste,
    ));
})
->bind('view')
->assert('id', '[0-9a-f]{8}');

$app->error(function (Exception $e) use ($app) {
    $code = ($e instanceof BaseHttpException) ? $e->getStatusCode() : 500;

    return new Response($app['twig']->render('error.html', array(
        'message'	=> $e->getMessage(),
    )), $code);
});

return $app;
