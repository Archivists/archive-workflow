<?php

class Carrier extends Eloquent {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'carriers';


    /**
     * Add auditable events.
     *
     * @var string
     */
    public static function boot()
    {
        parent::boot();
        Carrier::observe(new AuditableObserver);
    }

    
    /**
     * Our computed archive_id column - included here so that JSON or other 
     * auto-generating property enumerators will include it.
     *
     * @var string
     */
    protected $appends = array('archive_id');


    /**
     * Workflow status scope based on user session value.
     *
     */
    public function scopeWorkflow($query)
    {
        $value = Session::get('status', 'all');
        if ($value != 'all') {
            return $query->where('status_id', '=', $value);
        } else {
            return $query;
        }
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