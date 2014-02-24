<?php

class Category extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'categories';

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