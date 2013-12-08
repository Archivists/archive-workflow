<?php namespace ArchWorkflow;

use Config, File, Log;
 
class Image {
 
    /**
     * Instance of the Imagine package
     * @var Imagine\Gd\Imagine
     */
    protected $imagine;
 
    /**
     * Type of library used by the service
     * @var string
     */
    protected $library;
 
    /**
     * Initialize the image service
     * @return void
     */
    public function __construct()
    {
        if ( ! $this->imagine)
        {
            $this->library = Config::get('workflow.library', 'gd');
 
            // Now create the instance
            if     ($this->library == 'imagick') $this->imagine = new \Imagine\Imagick\Imagine();
            elseif ($this->library == 'gmagick') $this->imagine = new \Imagine\Gmagick\Imagine();
            elseif ($this->library == 'gd')      $this->imagine = new \Imagine\Gd\Imagine();
            else                                 $this->imagine = new \Imagine\Gd\Imagine();
        }
    }

    /**
     * Resize an image
     * @param  string  $path
     * @param  integer $width
     * @param  integer $height
     * @param  boolean $crop
     * @return string
     */
    public function resize($path, $style = 'thumbnails', $long = 100, $crop = false, $quality = 90)
    {
        if (file_exists($path))
        {
            // URL info
            $info = pathinfo($path);
            // Quality
            $quality = Config::get('workflow.quality', $quality);
     
            // Directories and file names
            $fileName       = $info['basename'];
            $sourceDirPath  = $info['dirname'];
            $sourceFilePath = $path;
            $targetDirName  = $style;
            $targetDirPath  = $sourceDirPath . DIRECTORY_SEPARATOR . $targetDirName . DIRECTORY_SEPARATOR;
            $targetFilePath = $targetDirPath . $fileName;
     
            // Create directory if missing
            try
            {
                // Create dir if missing
                if ( ! File::isDirectory($targetDirPath) and $targetDirPath) @File::makeDirectory($targetDirPath);
     
                if ( ! File::exists($targetFilePath) or (File::lastModified($targetFilePath) < File::lastModified($sourceFilePath)))
                {

                    $image = $this->imagine->open($sourceFilePath);

                    if($crop) {

                      $newWidth = $long;
                      $newHeight = $long;

                    } else {
                      
                      $size = $image->getSize();
                      $width = $size->getWidth();
                      $height = $size->getHeight();

                      if ($width >= $height) {
                          
                          $newWidth  = $long;
                          $newHeight =  $height * ($long / $width);
                          // we center the crop in relation to the width
                          // $cropPoint = new Point((max($width - $this->box->getWidth(), 0))/2, 0);
                      } else {
                          $newHeight  = $long;
                          $newWidth =  $width * ($long / $height);
                          //we center the crop in relation to the height
                          //$cropPoint = new Point(0, (max($height-$this->box->getHeight(),0))/2);
                      }
                    }


                    // Set the size
                    $newSize = new \Imagine\Image\Box($newWidth, $newHeight);
         
                    // Now the mode
                    $mode = $crop ? \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND : \Imagine\Image\ImageInterface::THUMBNAIL_INSET;
       
                    $image->thumbnail($newSize, $mode)
                        ->save($targetFilePath, array('quality' => $quality));
                }
            }
            catch (\Exception $e)
            {
                Log::error('[IMAGE SERVICE] Failed to resize image "' . $path . '" [' . $e->getMessage() . ']');
                return false;
            }
     
            return true;
        }

        return false;
    }

    /**
    * Helper for creating thumbs
    * @param string $path
    * @param integer $width
    * @param integer $height
    * @return string
    */
    public function thumb($path, $long = 100)
    {
        return $this->resize($path, "thumbnails", $long, true);
    }


    /**
     * Creates image dimensions based on a configuration
     * @param  string $path
     * @param  array  $dimensions
     * @return void
     */
    public function createImageSet($path, $dimensions = array())
    {
        $result = true;

        // Get default dimensions
        $defaultDimensions = Config::get('workflow.dimensions');
     
        if (is_array($defaultDimensions)) $dimensions = array_merge($defaultDimensions, $dimensions);
     
        foreach ($dimensions as $dimension)
        {
            // Get dimmensions and quality
            $style   =  $dimension[0];
            $long   = (int) $dimension[1];
            $crop    = isset($dimension[2]) ? (bool) $dimension[2] : false;
            $quality = isset($dimension[3]) ?  (int) $dimension[3] : Config::get('workflow.quality');
     
            // Run resizer
            if (! $this->resize($path, $style, $long, $crop, $quality)) {
              return false;
            }
        }
        return true;
    }
}
