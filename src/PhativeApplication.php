<?php

declare(strict_types=1);

namespace Phative\Framework;

use Phative\Render\Renderer;
use Phative\Render\Widget\Factory\FrameWidgetFactory;
use Tkui\DotEnv;
use Tkui\TclTk\TkAppFactory;
use Tkui\TclTk\TkApplication;
use Tkui\Windows\MainWindow;

abstract class PhativeApplication
{
    protected TkApplication $app;
    protected MainWindow $window;

    public function __construct(string $appName)
    {
        $factory = new TkAppFactory($appName);
        $this->app = $factory->createFromEnvironment(DotEnv::create(dirname(__DIR__)));
        $this->window = new MainWindow($this->app, $appName);
    }

    public function loadTheme(string $path): void
    {
        $this->app->tclEval('source', $path);
    }

    public function setTheme(string $name): void
    {
        $this->app->tclEval('set_theme', $name);
    }

    public function run(): void
    {
        $layout = $this->render();

        [$rendered, $styles] = (new Renderer())->render($this->window, $layout);

        $this->window->pack($rendered, $styles);

        $this->app->run();
    }

    abstract public function render(): FrameWidgetFactory;
}
