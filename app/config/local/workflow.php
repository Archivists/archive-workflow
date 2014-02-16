<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Workflow Application Specific Settings
	|--------------------------------------------------------------------------
	|
	| Settings here are specific to the digitization workflow application, 
	| incuding repository location, digitial audio workstation pick-up location
	| etc.
	|
	*/

	'repository'  => '/Users/tony/Projects/Archivists/Data/repository/',
    'daw_application'   => 'QUADRIGA',
	'daw_pickup'   => '/Users/tony/Projects/Archivists/Data/daw_pickup/',
	
    'library'     => 'gd',
    'upload_dir'  => 'uploads',
    'upload_path' => public_path() . '/uploads/',
    'quality'     => 85,
    'dimensions' => array(
        'thumb'  => array('thumbnails', 100, true,  80),
        'medium' => array('previews', 800, false, 90),
    ),
);