<?php namespace ArchWorkflow;

use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider {

    /**
     * Register the binding
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('image', function()
        {
            return new Image;
        });
    }
}