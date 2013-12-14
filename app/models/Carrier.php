<?php

class Carrier extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'carriers';

    
    /**
     * Our computed archive_id column - included here so that JSON or other 
     * auto-generating property enumerators will include it.
     *
     * @var string
     */
    protected $appends = array('archive_id');

    
    /**
     * Static event wire-up method
     *
     */
    public static function boot()
    {
        parent::boot();

        // Setup event bindings...

        Carrier::creating(function($carrier)
        {
            //We de-normalize username.
            $carrier->created_by = Auth::user()->username;
            $carrier->updated_by = Auth::user()->username;

        });


        Carrier::updating(function($carrier)
        {
            $carrier->updated_by = Auth::user()->username;

        });

    }

    /**
     * The inverse belongs to relationship with CarrierType
     *
     */
    public function carrierType()
    {
        return $this->belongsTo('CarrierType');
    }

    /**
     * The inverse belongs to relationship with Status
     *
     */
    public function status()
    {
        return $this->belongsTo('Status');
    }

    /**
     * The one-to-many relationship with Artifcat
     *
     */
    public function artifacts()
    {
        return $this->hasMany('Artifact');
    }

    /**
     * Caluclated Archive ID column based on ID, Shelf number, parts, and sides.
     *
     */
    public function getArchiveIdAttribute()
    {
        return str_pad($this->id, 6, '0', STR_PAD_LEFT) . "-" . $this->shelf_number;
    }
}