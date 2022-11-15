<?php

// ## get adminname and path from post method .
$adminname = $_POST['admin'];
$paths = $_POST['paths'];

// ## final destination on which file can be found
$uploadDir2="/home/$adminname/.$adminname/drive_c/restore/";


// ## removing previously restored data file 
`rm -rf /home/$adminname/.$adminname/drive_c/restore/* `;

// ## creating a nested structure directory.
if (!mkdir($uploadDir2, 0777, true))
  {
	`sudo chown $adminname:$adminname "$uploadDir2"`;
    // echo('Folders cannot be created recursively');
  }
else
  {
	// echo('Folders cannot be created recursively');
  }

// ## checking size of uploading file if its not 0. This is PHP File 
if(sizeof($_FILES) > 0)
	$fileUploader = new FileUploader($_FILES);

class FileUploader{

	public function __construct($uploads,$uploadDir="/var"){

		// Split the string containing the list of file paths into an array 
		//The explode() function breaks a string into an array.
		$paths = explode("###",rtrim($_POST['paths'],"###"));
		$adminname = $_POST['admin']; 

		// ## Loop through files sent
        /* 
		 Sub:- $this is a reserved keyword in PHP that refers to the calling object. It is usually the object to which the method belongs, 
		       but possibly another object if the method is called statically from the context of a secondary object. 
			   This keyword is only applicable to internal methods.
		 rem:- We are using it in our File Uploader and File Download
		 by:-Raj	   
          */
		foreach($uploads as $key => $current)
		{
			// ## Stores full destination path of file on server
            // ## $pathsend = $_POST['folderpath'];
            $uploadDir2="/home/$adminname/.$adminname/drive_c/restore/";                
			$this->uploadFile=$uploadDir2.rtrim($paths[$key],"/.");
			// Stores containing folder path to check if dir later
			$this->folder = substr($this->uploadFile,0,strrpos($this->uploadFile,"/"));
             
			//The substr() function returns a part of a string.

			// Check whether the current entity is an actual file or a folder (With a . for a name)
			if(strlen($current['name'])!=1)
				// ## Upload current file
				if($this->upload($current,$this->uploadFile))
					 "The file ".$paths[$key]." has been uploaded\n";
					
				else 
				 "";
		}
		echo "sucess";	 
		die;
	}
	
	private function upload($current,$uploadFile){
		// ## Checks whether the current file's containing folder exists, if not, it will create it.
		$test2 = $this->folder;
		$adminname = $_POST['admin'];//get session name in login page 
		`sudo chown $adminname:$adminname "$test2"`;
		$acessfile = shell_exec("echo $test2 | awk -F 'drive_c' '{print $2}'");
		if(!is_dir($this->folder)){
			mkdir($this->folder,0777,true);
			$newcreate = $this->folder;
			`sudo chown $adminname:$adminname "$newcreate"`;
			`sudo chmod 777 "$newcreate"`;

		}
		// ## Moves current file to upload destination
		if(move_uploaded_file($current['tmp_name'],$uploadFile))
		{
			`sudo chown $adminname:$adminname "$uploadFile"`;
			`sudo chmod 777 "$uploadFile"`;

			// echo $uploadFile;
			// echo "done";
			return true;
		}
	
		else 	{
			return false;
		}
	}
}
?>