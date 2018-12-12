<?php

namespace App;

use Aptoma\Twig\Extension\MarkdownEngine\ParsedownEngine;
use Aptoma\Twig\Extension\MarkdownExtension;
use Symfony\Component\Yaml\Yaml;

class Application
{
    protected $loader;
    protected $twig;
    protected $config;
    protected $global;

    public function __construct()
    {
        $this->loader = new \Twig_Loader_Filesystem(__DIR__ . '/../templates');
        $this->twig =new \Twig_Environment($this->loader, [
            // 'cache' => __DIR__ . '/../cache',
        ]);

        $engine = new ParsedownEngine;

        $this->twig->addExtension(new MarkdownExtension($engine));

        $this->config = Yaml::parseFile(__DIR__ . '/../config/config.yml');
        $this->global = Yaml::parseFile(__DIR__ . '/../config/global.yml');
        foreach ($this->global['global'] as $key => $value) {
            $this->twig->addGlobal($key, $value);
        }
    }

    public function run()
    {

        $pages = scandir(__DIR__ . '/../templates/pages');
        foreach ($pages as $page) {
            $p = __DIR__ . '/../templates/pages/' . $page;
            if (is_file($p)) {
                $info = pathinfo($p);
                if ($info['extension'] === 'twig') {
                    $template = $this->twig->load('pages/' . $page);

                    $vars = $this->global['index'];
                    if ($info['filename'] === 'index.html') {
                        $dirname = dirname(__DIR__ . '/../web/' . $info['filename']);
                        if (!is_dir($dirname))
                        {
                            mkdir($dirname, 0755, true);
                        }

                        $f = fopen(__DIR__ . '/../web/' . $info['filename'], 'w');

                        if (file_exists(__DIR__ . '/../content/index.md')) {
                            $vars['pagecontent'] = file_get_contents(__DIR__ . '/../content/index.md');
                        }
                        $vars['pagename'] = 'index';
                    } else {
                        $pageName = substr($info['filename'], 0, -5);
                        $dirname = dirname(__DIR__ . '/../web/' . $pageName. '/' . 'index.html');
                        if (!is_dir($dirname))
                        {
                            mkdir($dirname, 0755, true);
                        }
                        $f = fopen(__DIR__ . '/../web/' . $pageName . '/' . 'index.html', 'w');

                        $vars = [];
                        if (isset($this->global[$pageName])) {
                            $vars = $this->global[$pageName];
                        }
                        if (file_exists(__DIR__ . '/../content/'.$pageName.'.md')) {
                            $vars['pagecontent'] = file_get_contents(__DIR__ . '/../content/'.$pageName.'.md');
                        }
                        $vars['pagename'] = $pageName;
                    }
                    fwrite($f, $template->render($vars));
                    fclose($f);
                }
            }
        }
    }
}


