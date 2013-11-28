<?php

class Carrier extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'carriers';

    
    /**
     * The inverse belongs to relationship with CarrierType
     *
     */
    public function carrierType()
    {
        return $this->belongsTo('CarrierType');
    }

    /**
     * The one-to-many relationship with Artifcat
     *
     */
    public function artifacts()
    {
        return $this->hasMany('Artifact');
    }

}