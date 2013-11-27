<?php

class Carrier extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'carriers';

    public function carrierType()
    {
        return $this->belongsTo('CarrierType');
    }

}