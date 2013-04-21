<?php

class ImageHandler
{
	// The folder in which to save images
	public $save_dir;
	
	// Sets the $save_dir on instantiation
	public function __construct($save_dir)
	{
		$this->save_dir = $save_dir;
	}
	
	/**
	 * Resizes/resamples an image uploaded via a web form
	 *
	 * @param array $upload the array contained in $_FILES
	 * @return string the path to the resized uploaded file
	 */
	public function processUploadedImage($file)
	{
		// Separate the uploaded file array
		list($name, $type, $tmp, $err, $size) = array_values($file);
		
		
		// If an error occurred, throw an exception
		if($err != UPLOAD_ERR_OK) {
			throw new Exception('An error occurred with the upload!');
			return;
		}
		
		// Create the full path to the image for saving
		$filepath = $this->save_dir . $name;
		
		// Store the absolute path to move the image
		$absolute = $_SERVER['DOCUMENT_ROOT'] . $filepath;
		
		// Save the image
		if(!move_uploaded_file($tmp, $absolute))
		{
			throw new Exception("Couldn't save the uploaded file!");
		}
		return $filepath;
		
			
	}
	
	/**
	 * Ensures that the save directory exists
	 *
	 * Checks for the existence of the supplied save directory,
	 * and creates the directory if it doesn't exist. Creation is
	 * recursive.
	 *
	 * @param void
	 * @return void
	 */
	
	private function checkSaveDir()
	{
		// Determines the path to check
		$path = $_SERVER['DOCUMENT_ROOT'] . $this->save_dir;
		
		// Check for the dir
		// Checks if the directory exists
		if(!is_dir($path))
		{
			
			// Creates the directory
			if(!mkdir($path, 0777, TRUE))
			{
				// On failure, throws an error
				throw new Exception("Can't create the directory!");
			}
		}
	}
	
	
}

?>