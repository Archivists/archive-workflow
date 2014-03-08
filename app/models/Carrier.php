<?php

class Carrier extends Eloquent {

	

    /**
     * Collection prefix - retrieved from workflow.php config section.
     *
     * @var string
     */
    protected $prefix;

    /**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'carriers';


    /**
     * Constructor
     *
     * @attributes Array
     */
    public function __construct(array $attributes = array())
    {
        // $this->setRawAttributes(array(
        //   'end_date' => Carbon::now()->addDays(10)
        // ), true);
        $this->prefix = Config::get('workflow.collection_prefix', 'ABC'); 
        parent::__construct($attributes);
    }


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
     * The inverse belongs to relationship with Category
     *
     */
    public function category()
    {
        return $this->belongsTo('Category');
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
        return str_pad($this->prefix, 6, '0', STR_PAD_LEFT) . "-" . str_pad($this->shelf_number, 6, '0', STR_PAD_LEFT);
    }
}