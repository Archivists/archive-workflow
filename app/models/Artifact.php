<?php

class Artifact extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'artifacts';

    
     /**
     * The inverse belongs to relationship with Carrier
     *
     */
    public function carrier()
    {
        return $this->belongsTo('Carrier');
    }

}