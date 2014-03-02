<?php

/**
 * Create an XML manifest for the digital audio workstation. A seperate
 * manifest, or 'job' will be created for each side of any carrier that 
 * may have more than one side.
 * @param Carrier $carrier
 */
function create_cubetec_manifest($carrier, $daw_pickup_path)
{
    $result = true;
    Log::info("Create cubtec manifest called");

    try {
        
        for ($x = 1; $x <= $carrier->sides; $x++) {

            $file = $daw_pickup_path . $carrier->archive_id . "-" . $x . ".imp.xml";

            $version = '1.0';
            $encoding = 'UTF-8';
            $rootName = 'REPORT';

            $xml = new \XMLWriter(); 

            $xml->openMemory();
            $xml->setIndent(true);
            $xml->setIndentString("  ");
            $xml->startDocument($version, $encoding);
            $xml->startElement($rootName);
                //BEXDATA
                $xml->startElement('BEXTDATA');
                	$xml->writeElement('DESCRIPTION', 'Import to QUADRIGA from PBC Workflow.'); 
                	$xml->writeElement('ORIGINATOR', 'PBC'); 
                	$xml->writeElement('ORIGINATIONDATE', date("Y-m-d")); 
                	$xml->writeElement('ORIGINATIONTIME', date("H:i:s")); 
                $xml->endElement();
                //QUALITYREPORT
                $xml->startElement('QUALITYREPORT');
                	//BASICDATA
                	$xml->startElement('BASICDATA');
                		$xml->writeElement('ARCHIVENUMBER', $carrier->archive_id . '-' . $x); 
                		$xml->writeElement('TITLE', 'PBC Analogue Digitization'); 
                		$xml->writeElement('OPERATOR', 'QUADRIGA User'); 	
                	$xml->endElement();
                	//QUALITYEVENTS
                	$xml->startElement('QUALITYEVENTS');
                	$xml->endElement();
                	//QUALITYPARAMETER
                	$xml->startElement('QUALITYPARAMETER');
                		$xml->writeElement('QUALITYFACTOR', '1'); 	
                		$xml->writeElement('FILESTATUS', 'N'); 	
                	$xml->endElement();
                $xml->endElement();
            $xml->endElement();
            $data = $xml->outputMemory();
            file_put_contents($file,$data);
        }
    }
    catch (\Exception $e)
    {
        Log::error('[STATUS ACTION SERVICE] Failed to write Cube-tec DAW manifest for"' . $carrier->archive_id . '" [' . $e->getMessage() . ']');
        $result = false;
    }

    return $result;
}