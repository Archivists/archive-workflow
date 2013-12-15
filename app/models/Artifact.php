<?php

class Artifact extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'artifacts';

    /**
     * Add auditable events.
     *
     * @var string
     */
    public static function boot()
    {
        parent::boot();
        Artifact::observe(new AuditableObserver);
    }

    
     /**
     * The inverse belongs to relationship with Carrier
     *
     */
    public function carrier()
    {
        return $this->belongsTo('Carrier');
    }

}