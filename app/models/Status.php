<?php

class Status extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'status';


    /**
     * Add auditable events.
     *
     * @var string
     */
    public static function boot()
    {
        parent::boot();
        Status::observe(new AuditableObserver);
    }

     /**
     * The one-to-many relationship with Carrier
     *
     */
    public function carriers()
    {
        return $this->hasMany('Carrier');
    }


    /**
     *  Status scope based on order.
     *
     */
    public function scopeSequence($query)
    {
        return $query->orderBy('order');
    }

}