<?php

namespace Selene\StaticSite;

use Aptoma\Twig\Extension\MarkdownEngine\ParsedownEngine;
use Aptoma\Twig\Extension\MarkdownExtension;
use Symfony\Component\Yaml\Yaml;

class Application
{
    protected $loader;
    protected $twig;
    protected $config;
    protected $global;
    protected $dir;

    public function __construct()
    {
        $this->dir = getced();
        $this->loader = new \Twig_Loader_Filesystem($this->dir . '/templates');
        $this->twig =new \Twig_Environment($this->loader, [
            // 'cache' => __DIR__ . '/../cache',
        ]);

        $engine = new ParsedownEngine;

        $this->twig->addExtension(new MarkdownExtension($engine));

        $this->config = Yaml::parseFile($this->dir . '/config/config.yml');
        $this->global = Yaml::parseFile($this->dir . '/config/global.yml');
        foreach ($this->global['global'] as $key => $value) {
            $this->twig->addGlobal($key, $value);
        }
    }

    public function run()
    {

        $pages = scandir($this->dir . '/templates/pages');
        foreach ($pages as $page) {
            $p = $this->dir . '/templates/pages/' . $page;
            if (is_file($p)) {
                $info = pathinfo($p);
                if ($info['extension'] === 'twig') {
                    $template = $this->twig->load('pages/' . $page);

                    $vars = $this->global['index'];
                    if ($info['filename'] === 'index.html') {
                        $dirname = dirname($this->dir . '/web/' . $info['filename']);
                        if (!is_dir($dirname))
                        {
                            mkdir($dirname, 0755, true);
                        }

                        $f = fopen($this->dir . '/web/' . $info['filename'], 'w');

                        if (file_exists($this->dir . '/content/index.md')) {
                            $vars['pagecontent'] = file_get_contents($this->dir . '/content/index.md');
                        }
                        $vars['pagename'] = 'index';
                    } else {
                        $pageName = substr($info['filename'], 0, -5);
                        $dirname = dirname($this->dir . '/web/' . $pageName. '/' . 'index.html');
                        if (!is_dir($dirname))
                        {
                            mkdir($dirname, 0755, true);
                        }
                        $f = fopen($this->dir . '/web/' . $pageName . '/' . 'index.html', 'w');

                        $vars = [];
                        if (isset($this->global[$pageName])) {
                            $vars = $this->global[$pageName];
                        }
                        if (file_exists($this->dir . '/content/'.$pageName.'.md')) {
                            $vars['pagecontent'] = file_get_contents($this->dir . '/content/'.$pageName.'.md');
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


