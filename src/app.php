#!/usr/bin/env php
<?php

require __DIR__.'/../vendor/autoload.php';

$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates');
$twig =new Twig_Environment($loader, [
    // 'cache' => $path . '/var/cache',
]);

$pages = scandir(__DIR__ . '/../templates/pages');
foreach ($pages as $page) {
    $p = __DIR__ . '/../templates/pages/' . $page;
    if (is_file($p)) {
        $info = pathinfo($p);
        if ($info['extension'] === 'twig') {
            $template = $twig->load('pages/' . $page);
            if ($info['filename'] === 'index.html') {
                // var_dump(__DIR__ . '/../web/' . $info['filename']);die();
                $dirname = dirname(__DIR__ . '/../web/' . $info['filename']);
                if (!is_dir($dirname))
                {
                    mkdir($dirname, 0755, true);
                }

                $f = fopen(__DIR__ . '/../web/' . $info['filename'], 'w');
            } else {
                $dirname = dirname(__DIR__ . '/../web/' . substr($info['filename'], 0, -5). '/' . 'index.html');
                if (!is_dir($dirname))
                {
                    mkdir($dirname, 0755, true);
                }
                $f = fopen(__DIR__ . '/../web/' . substr($info['filename'], 0, -5). '/' . 'index.html', 'w');
            }
            fwrite($f, $template->render([]));
            fclose($f);
        }
    }
}
