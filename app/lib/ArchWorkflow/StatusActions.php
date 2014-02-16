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
                $result  = ($this->create_sip($carrier) ? true : false);
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
        Log::info("Create daw manifest called");

        try {
            
            for ($x = 1; $x <= $carrier->sides; $x++) {

                $file = $this->dawpickup . $carrier->archive_id . "-" . $x . ".xml";

                $version = '1.0';
                $encoding = 'UTF-8';
                $rootName = 'root';

                $xml = new \XMLWriter(); 

                $xml->openMemory();
                $xml->setIndent(true);
                $xml->setIndentString("  ");
                $xml->startDocument($version, $encoding);
                $xml->startElement($rootName);
                    // .. add more elements here
                    $xml->writeElement('Description', 'Some descriptive text here'); 
                    $xml->startElement('Foo');
                        $xml->writeAttribute('reel', "0001");
                        $xml->text('Foo text'); 
                    $xml->endElement();

                $xml->endElement();
                $data = $xml->outputMemory();
                file_put_contents($file,$data);
            }
        }
        catch (\Exception $e)
        {
            Log::error('[STATUS ACTION SERVICE] Failed to write DAW manifest for"' . $carrier->archive_id . '" [' . $e->getMessage() . ']');
            $result = false;
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
    public function create_sip($carrier)
    {
        Log::info("Create sip called");
    }
}
