<?php

class CarrierType extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'carrier_types';

    public function carriers()
    {
        return $this->hasMany('Carrier');
    }

}