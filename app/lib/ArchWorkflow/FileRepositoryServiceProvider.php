<?php namespace ArchWorkflow;

use Illuminate\Support\ServiceProvider;

class FileRepositoryServiceProvider extends ServiceProvider {

    /**
     * Register the binding
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('filerepository', function()
        {
            return new FileRepository;
        });
    }
}