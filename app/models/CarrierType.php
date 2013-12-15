<?php

class CarrierType extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'carrier_types';

    /**
     * Add auditable events.
     *
     * @var string
     */
    public static function boot()
    {
        parent::boot();
        CarrierType::observe(new AuditableObserver);
    }

     /**
     * The one-to-many relationship with Carrier
     *
     */
    public function carriers()
    {
        return $this->hasMany('Carrier');
    }

}