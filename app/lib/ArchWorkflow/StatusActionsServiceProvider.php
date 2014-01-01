<?php namespace ArchWorkflow;

use Illuminate\Support\ServiceProvider;

class StatusActionsServiceProvider extends ServiceProvider {

    /**
     * Register the binding
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('statusactions', function()
        {
            return new StatusActions;
        });
    }
}