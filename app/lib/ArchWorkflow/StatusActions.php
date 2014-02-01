<?php namespace ArchWorkflow;

use Filesystem, Config, Log;

class StatusActions
{
    /**
     * Repository location
     * @var String
     */
    protected $repository;


    /**
     * Repository location
     * @var String
     */
    protected $dawpickup;

    /**
     * Inject the model.
     * @param Image $image
     */
    public function __construct()
    {
        $this->dawpickup = Config::get('workflow.dawpickup');
        $this->repository = Config::get('workflow.repository');
    }

    /**
     * Invoke the action for the corresponding status change.
     * @param String $side* 
     * @param Carrier $carrier
     * 
     */
    public function invoke_action($action, $carrier)
    {
        Log::info("invoke_action called");
    }

    /**
     * Create an XML manifest for the digital audio workstation. A seperate
     * manifest, or 'job' will be created for each side of any carrier that 
     * may have more than one side.
     * @param Carrier $carrier
     * @param String $side
     */
    public function create_daw_manifest($carrier)
    {
        Log::info("Create daw manifest called");
    }

    /**
     * Create the submission for ingest package. This will create the final bundle, including
     * all audio files, parts, sides, and supporting artifacts for submussion to the
     * data management system (typically a Catalogue, or index).
     * @param String $archive_id
     * @param String $name
     */
    public function create_sip($archive_id, $name)
    {
        Log::info("Create sip called");
        
    }
}
