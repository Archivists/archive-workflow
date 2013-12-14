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
     * Repository location
     * @var String
     */
    protected $repository;

    /**
     * Inject the model.
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
        $this->repository = Config::get('workflow.repository');
    }

    /**
     * Move a file into the repository, and generate image sets if appropriate
     * @param String $archive_id
     * @param String $uploaded
     * @param String $name
     */
    public function move_file($archive_id, $uploaded, $name)
    {
        // Create directory if missing
        try {

            $directory = $this->repository . $archive_id;
            if (!file_exists($directory) && !is_dir($directory)) {
                mkdir($directory);         
            } 
            
            $destination =  $directory . DIRECTORY_SEPARATOR . $name;
            
            move_uploaded_file($uploaded, $destination);
            
            //If this is an image jpg, jpeg, gif, tiff, then we'll create thumbnails and previews.
            if ($this->is_image($name)){
                if ($this->image->createImageSet($destination)) {
                    return true;
                } else {
                    Log::error('[FILE REPOSITORY SERVICE] Failed to create image set for "' . $name . '"');
                    return false;
                }
            }
            return true;
        }
        catch (\Exception $e)
        {
            Log::error('[FILE REPOSITORY SERVICE] Failed to move file "' . $name . '" [' . $e->getMessage() . ']');
            return false;
        }
    }

    /**
     * Delete a file from the repository, and remove image sets if appropriate
     * @param String $archive_id
     * @param String $name
     */
    public function delete_file($archive_id, $name)
    {
        // Create directory if missing
        try {

            $dir = $this->repository . $archive_id;
            $path = $dir . DIRECTORY_SEPARATOR . $name;

            if (file_exists($path)) {
                unlink($path);    
            }
            
            //If this is an image then remove thumbnails and previews.
            if ($this->is_image($name)){
                $thumbnail_path = $dir . DIRECTORY_SEPARATOR . "thumbnails";
                $thumbnail = $thumbnail_path . DIRECTORY_SEPARATOR . $name;
                $preview_path = $dir . DIRECTORY_SEPARATOR . "previews";
                $preview = $preview_path . DIRECTORY_SEPARATOR . $name;
                if (file_exists($thumbnail)) {
                    unlink($thumbnail);    
                }
                if ($this->is_dir_empty($thumbnail_path)){
                    rmdir($thumbnail_path);
                }
                if (file_exists($preview)) {
                    unlink($preview);    
                }
                if ($this->is_dir_empty($preview_path)){
                    rmdir($preview_path);
                }
            }

            //If there are no artifacts left for this archive id - then remove the directory.
            if ($this->is_dir_empty($dir)){
                rmdir($dir);
            }

            return true;
        }
        catch (\Exception $e)
        {
            Log::error('[FILE REPOSITORY SERVICE] Failed to delete file "' . $name . '" [' . $e->getMessage() . ']');
            return false;
        }
    }

    /**
     * Private helper function to test whether a file is an image.
     * @param String $name
     */
    private function is_image($name) {
        if (preg_match('/^.*\.(jpg|jpeg|png|gif|tiff|tif)$/i', $name) === 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Private helper function to test whether a directory is empty.
     * @param String $dir
     */
    private function is_dir_empty($dir) {
      if (!is_readable($dir)) return NULL; 
      return (count(scandir($dir)) == 2);
    }
}
