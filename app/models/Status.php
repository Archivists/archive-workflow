<?php

class Status extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'status';

     /**
     * The one-to-many relationship with Carrier
     *
     */
    public function carriers()
    {
        return $this->hasMany('Carrier');
    }

}