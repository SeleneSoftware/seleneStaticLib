<?php

namespace Selene\StaticSite;

interface PluginInterface
{
    public function setup(array $options);

    public function start();

    public function render(string $pageName, string $render = null): ?string;

    public function end();
}
