<?php

namespace Selene\StaticSite;

use Aptoma\Twig\Extension\MarkdownEngine\ParsedownEngine;
use Aptoma\Twig\Extension\MarkdownExtension;
use Symfony\Component\Yaml\Yaml;
use Twig\Extension\ExtensionInterface;

class Application
{
    protected $loader;

    protected $twig;

    protected $config;

    protected $global;

    protected $dir;

    protected $plugin = [];

    public function __construct()
    {
        $this->dir = getcwd();
        $this->loader = new \Twig_Loader_Filesystem($this->dir.'/templates');
        $this->twig = new \Twig_Environment($this->loader, [
            // 'cache' => __DIR__ . '/../cache',
        ]);

        $engine = new ParsedownEngine();

        $this->twig->addExtension(new MarkdownExtension($engine));

        $this->config = Yaml::parseFile($this->dir.'/config/config.yml');
        $this->global = Yaml::parseFile($this->dir.'/config/global.yml');
        foreach ($this->global['global'] as $key => $value) {
            $this->twig->addGlobal($key, $value);
        }
    }

    public function addExtension(ExtensionInterface $extension)
    {
        $this->twig->addExtension($extension);
    }

    public function addPlugin(PluginInterface $plugin)
    {
        $this->plugin[] = $plugin;
    }

    public function run()
    {
        foreach ($this->plugin as $plugin) {
            $plugin->start();
        }

        $pages = scandir($this->dir.'/templates/pages');
        foreach ($pages as $page) {

            $p = $this->dir.'/templates/pages/'.$page;
            if (is_file($p)) {
                $info = pathinfo($p);
                if ('twig' === $info['extension']) {
                    $template = $this->twig->load('pages/'.$page);

                    $vars = $this->global['index'];
                    if ('index.html' === $info['filename']) {
                        $dirname = dirname($this->dir.'/web/'.$info['filename']);
                        if (!is_dir($dirname)) {
                            mkdir($dirname, 0755, true);
                        }

                        $f = fopen($this->dir.'/web/'.$info['filename'], 'w');

                        if (file_exists($this->dir.'/content/index.md')) {
                            $vars['pagecontent'] = file_get_contents($this->dir.'/content/index.md');
                        }
                        $vars['pagename'] = 'index';
                    } else {
                        $pageName = substr($info['filename'], 0, -5);
                        $dirname = dirname($this->dir.'/web/'.$pageName.'/'.'index.html');
                        if (!is_dir($dirname)) {
                            mkdir($dirname, 0755, true);
                        }
                        $f = fopen($this->dir.'/web/'.$pageName.'/'.'index.html', 'w');

                        $vars = [];
                        if (isset($this->global[$pageName])) {
                            $vars = $this->global[$pageName];
                        }
                        if (file_exists($this->dir.'/content/'.$pageName.'.md')) {
                            $vars['pagecontent'] = file_get_contents($this->dir.'/content/'.$pageName.'.md');
                        }
                        $vars['pagename'] = $pageName;
                    }

                    foreach ($this->plugin as $plugin) {
                        if (!isset($render)) {
                            $render = $plugin->render($vars['pagename'], $template->render($vars));
                        } else {
                            $render = $plugin->render($vars['pagename'], $render);
                        }
                    }

                    if (!isset($render)) {
                        fwrite($f, $template->render($vars));
                    } else {
                        fwrite($f, $render);
                    }
                    unset($render);
                    fclose($f);
                }
            }
        }

        foreach ($this->plugin as $plugin) {
            $plugin->end();
        }
    }
}
