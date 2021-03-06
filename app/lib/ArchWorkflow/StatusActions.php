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
     * DAW manifest location
     * @var String
     */
    protected $daw_pickup;

    /**
     * DAW application
     * @var String
     */
    protected $daw_application;

    /**
     * Inject the model.
     * @param Image $image
     */
    public function __construct()
    {
        $this->daw_pickup = Config::get('workflow.daw_pickup');
        $this->daw_application = Config::get('workflow.daw_application');
        $this->repository = Config::get('workflow.repository');

    }

    /**
     * Invoke the action for the corresponding status change.
     * @param String $side* 
     * @param Carrier $carrier
     * 
     */
    public function invoke_action($carrier)
    {
        $result = true;

        Log::info("[DEBUG] Invoke_action called");
        Log::info("[DEBUG] Calling action for " . $carrier->status->name);

        switch ($carrier->status->name) {
            case "Preparation":
                $result  = ($this->return_to_preparation($carrier) ? true : false);
                break;
            case "Digitisation":
                $result  = ($this->create_daw_manifest($carrier) ? true : false);
                break;
            case "QC and Part Editing":
                $result  = ($this->move_to_qc($carrier) ? true : false);
                break;
            case "Complete":
                $result  = ($this->complete($carrier) ? true : false);
                break;
        }

        return $result;
    }


    /**
     * Return to preparation and clean up XML manifests and other files.
     * The default status for all carriers is 'Preparation', and so the 
     * only way this method could be called, is if we've returned to 
     * preparation from some other status.
     * @param Carrier $carrier
     */
    public function return_to_preparation($carrier)
    {
        $result = true;
        Log::info("Return to preparation called.");
        return $result;
    }


    /**
     * Create an XML manifest for the digital audio workstation. A seperate
     * manifest, or 'job' will be created for each side of any carrier that 
     * may have more than one side.
     * @param Carrier $carrier
     */
    public function create_daw_manifest($carrier)
    {
        $result = true;

        // A bit cludgy - but needed for correct import of SIP into the catalogue system
        $this->write_category($carrier);

        // Dynamically load the DAW application specific module.
        switch ($this->daw_application) {
            case "QUADRIGA":
                include('Cubetec.php');
                $result = create_cubetec_manifest($carrier, $this->daw_pickup);
                break;
            case "OTHER DAW":
                include('Other.php');
                $result = create_other_manifest($carrier, $this->daw_pickup);
                break;
        }

        return $result;
    }


    /**
     * Move to QC and part editing, removing XML manifests from the digitization
     * queue.
     * @param Carrier $carrier
     */
    public function move_to_qc($carrier)
    {
        $result = true;
        Log::info("Move to QC and Part Editing called");
        return $result;
    }


    /**
     * Create the submission for ingest package. This will create the final bundle, 
     * including all audio files, parts, sides, and supporting artifacts for submussion 
     * to the data management system (typically a Catalogue, or index).
     * @param String $archive_id
     * @param String $name
     */
    public function complete($carrier)
    {
        //Log::info("Compelte called");
        return true;
    }

    /**
     * Write our main category token for the SIP import.
     * @param Carrier 
     */
    public function write_category($carrier)
    {

        try {

            $directory = $this->repository . $carrier->archive_id;
            if (!file_exists($directory) && !is_dir($directory)) {
                mkdir($directory);         
            } 
            
            $filename = $this->repository . $carrier->archive_id . DIRECTORY_SEPARATOR . "category.txt";
            file_put_contents($filename, $carrier->category->name);

        }
        catch (\Exception $e)
        {
            Log::error('[STATUS ACTION SERVICE] Failed to write category token for "' . $carrier->archive_id . '" [' . $e->getMessage() . ']');
            $result = false;
        }

    }
}
