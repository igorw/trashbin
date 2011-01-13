<?php
/*
 * This file is part of trashbin.
 *
 * (c) 2010 Igor Wiedler
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

require_once __DIR__.'/silex.phar';
require dirname(__FILE__) . '/bootstrap.php';

use Silex\Framework;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Response;

$app = $container->get('framework');

$app->get('/', function() use ($app, $container) {
    $request = $app->getRequest();
    $twig = $container->get('twig');

    $parentId = $request->get('parent', false);
    $parent = false;
    if ($parentId) {
        $pastes = $container->get('mongo.pastes');
        $parent = $pastes->findOne(array("_id" => $parentId));
    }

    $template = $twig->loadTemplate('index.html');

    return $template->render(array(
        'base_path'	=> $request->getBasePath(),
        'create_url'    => $request->getBasePath().'/create',
        'languages' => getLanguages(),
        'paste'     => $parent,
        'footer'    => $container->getParameter('app.footer'),
    ));
});

$app->get('/create', function() use ($app) {
    $request = $app->getRequest();

    $response = new Response;
    $response->setRedirect($request->getBasePath().'/');
    return $response; 
});

$app->post('/create', function() use ($app, $container) {
    $request = $app->getRequest();
    $twig = $container->get('twig');

    $content = preg_replace('#\\r?\\n#', "\n", (string) $request->get('content', ''));

    $paste = array(
        '_id'       => substr(hash('sha512', $content . time() . rand(0, 255)), 0, 8),
        'content'   => $content,
        'createdAt' => new MongoDate(),
    );

    $languages = getLanguages();

    if ('' === trim($paste['content'])) {
        $errorMsg = 'you must enter some content';

        $template = $twig->loadTemplate('index.html');

        return $template->render(array(
            'base_path'	=> $request->getBasePath(),
            'create_url'	=> $request->getBasePath().'/create',
            'languages'	=> $languages,
            'error_msg'	=> $errorMsg,
            'paste'		=> $paste,
            'footer'	=> $container->getParameter('app.footer'),
        ));
    }

    $language = (string) $request->get('language', '');
    if (in_array($language, $languages)) {
        $paste['language'] = $language;
    }

    $pastes = $container->get('mongo.pastes');
    $pastes->insert($paste);

    $response = new Response;
    $response->setRedirect($request->getBasePath().'/view/'.$paste['_id']);
    return $response;
});

$app->get('/view/{id}', function($id) use ($app, $container) {
    $request = $app->getRequest();
    $twig = $container->get('twig');

    $pastes = $container->get('mongo.pastes');
    $paste = $pastes->findOne(array("_id" => $id));

    if (!$paste) {
        throw new FileNotFoundException('paste not found');
    }

    $template = $twig->loadTemplate('view.html');

    return $template->render(array(
        'base_path'	=> $request->getBasePath(),
        'index_url'	=> $request->getBasePath().'/',
        'copy_url'	=> $request->getBasePath().'/?parent='.$paste['_id'],
        'paste'		=> $paste,
        'footer'	=> $container->getParameter('app.footer'),
    ));
});

$app->get('/about', function() use ($app, $container) {
    $request = $app->getRequest();
    $twig = $container->get('twig');

    $template = $twig->loadTemplate('about.html');

    return $template->render(array(
        'base_path'	=> $request->getBasePath(),
        'index_url'	=> $request->getBasePath().'/',
        'footer'	=> $container->getParameter('app.footer'),
    ));
});

$app->error(function(Exception $e) use ($app, $container) {
    $request = $app->getRequest();
    $twig = $container->get('twig');

    $code = 500;
    if ($e instanceof FileNotFoundException) {
        $code = 404;
    }

    $template = $twig->loadTemplate('error.html');

    return new Response($template->render(array(
        'base_path'	=> $request->getBasePath(),
        'index_url'	=> $request->getBasePath().'/',
        'message'	=> $e->getMessage(),
        'footer'	=> $container->getParameter('app.footer'),
    )), $code);
});

return $app;

function getLanguages() {
    $languages = array();
    $finder = new Finder();
    foreach ($finder->name('*.min.js')->in(__DIR__.'/vendor/shjs/lang') as $file) {
        if (preg_match('#sh_(.+).min.js#', basename($file), $matches)) {
            $languages[] = $matches[1];
        }
    }
    return $languages;
}
