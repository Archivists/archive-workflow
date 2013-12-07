<?php namespace ArchWorkflow;

use Filesystem, Config, Log;

class FileRepository
{
    
    /**
     * Image processor
     * @var Image
     */
    protected $image;

    /**
     * Inject the model.
     * @param Carrier $carrier
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
        
    }

    public function move_file($archive_id, $uploaded, $name)
    {
        $repo = Config::get('workflow.repository');
        $dir = $repo . $archive_id;
        if (!file_exists($dir) && !is_dir($dir)) {
            mkdir($dir);         
        } 
        $destination =  $dir . DIRECTORY_SEPARATOR . $name;
        move_uploaded_file($uploaded, $destination);
        $this->image->createDimensions($destination);

        return true;
    }
}
