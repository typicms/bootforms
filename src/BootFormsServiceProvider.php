<?php

namespace TypiCMS\BootForms;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Form\ErrorStore\IlluminateErrorStore;
use TypiCMS\Form\FormBuilder;
use TypiCMS\Form\OldInput\IlluminateOldInputProvider;

class BootFormsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerErrorStore();
        $this->registerOldInput();
        $this->registerFormBuilder();
        $this->registerBasicFormBuilder();
        $this->registerHorizontalFormBuilder();
        $this->registerBootForm();
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'bootform');

        Blade::anonymousComponentNamespace('bootform::components', 'bootform');
    }

    protected function registerErrorStore(): void
    {
        $this->app->singleton('typicms.form.errorstore', fn (Application $app): IlluminateErrorStore => new IlluminateErrorStore($app['session.store']));
    }

    protected function registerOldInput(): void
    {
        $this->app->singleton('typicms.form.oldinput', fn (Application $app): IlluminateOldInputProvider => new IlluminateOldInputProvider($app['session.store']));
    }

    protected function registerFormBuilder(): void
    {
        $this->app->singleton('typicms.form', function (Application $app): FormBuilder {
            $formBuilder = new FormBuilder;
            $formBuilder->setErrorStore($app['typicms.form.errorstore']);
            $formBuilder->setOldInputProvider($app['typicms.form.oldinput']);
            $formBuilder->setToken($app['session.store']->token());

            return $formBuilder;
        });
    }

    protected function registerBasicFormBuilder(): void
    {
        $this->app->singleton('typicms.bootform.basic', fn (Application $app): BasicFormBuilder => new BasicFormBuilder($app['typicms.form']));
    }

    protected function registerHorizontalFormBuilder(): void
    {
        $this->app->singleton('typicms.bootform.horizontal', fn (Application $app): HorizontalFormBuilder => new HorizontalFormBuilder($app['typicms.form']));
    }

    protected function registerBootForm(): void
    {
        $this->app->singleton('typicms.bootform', fn (Application $app): BootForm => new BootForm($app['typicms.bootform.basic'], $app['typicms.bootform.horizontal']));
    }
}
