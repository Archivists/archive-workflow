<?php namespace ArchWorkflow;

use Illuminate\Support\ServiceProvider;

class DawPickupServiceProvider extends ServiceProvider {

    /**
     * Register the binding
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('dawpickup', function()
        {
            return new DawPickup;
        });
    }
}