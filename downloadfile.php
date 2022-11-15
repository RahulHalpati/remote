<?php 
    // ## conncet to database
    include("../config.php");

    /* 
		 Sub:- When File is Reday using this we are Going to Download the File
		 rem:- Using I/O Streem we Download the File from Path
		 by:-Raj	   
          */

    // ## data passes with execution of script in using get method
    // ## local file that should be send to the client 
    $filename=trim($_GET['filename']);
    $admin=trim($_GET['admin']);
    $username=trim($_GET['loginuser']);
    $backuptype=trim($_GET['backuptype']);

    // ## check if filenname passed is not empty
    if (!empty($filename))
    {
        // ## path at which file can be found.
        $local_file='/var/www/html/backup/'.$admin.'/'.$username.'/'.$filename;
        $download_file = $filename;
    
        // ## set the download rate limit (=> 2000.5' kb/s)
        $download_rate = 2000.5; 
        if(file_exists($local_file) && is_file($local_file)) {
            // ## send headers
            header('Cache-control: private');
            header('Content-Type: application/octet-stream'); 
            header('Content-Length: '.filesize($local_file));
            header('Content-Disposition: filename='.$download_file);
        
            // ## flush content
            flush();    
            // ## open file stream
            $file = fopen($local_file, "r");    
            while(!feof($file)) {
        
                // ## send the current file part to the browser
                print fread($file, round($download_rate * 1024));    
        
                // ## flush the content to the browser
                flush();
        
                // ## sleep one second
                sleep(1);    
            }    
            
            // ## close file stream
            fclose($file);
                // ## update file downloaded in backup logs
                $run_querry = mysqli_query($conn, "INSERT INTO backuplogs ( date , time, admin, user, action, type, destination ) VALUES ( CURDATE() , CURTIME(), '$admin','$username','sent','$backuptype','local' )");

                // ## Use unlink() function to delete a file 
                // ## delete file once downlaoding complete
                if (!unlink($local_file)) { 
                    echo ("$local_file cannot be deleted due to an error"); 
                } 
                else { 
                    echo ("$local_file has been deleted"); 
                } 
            }
        else {
            die('Error: The file '.$local_file.' does not exist!');
        }
    }

?>