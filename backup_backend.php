<?php



//sub:- All the Type of Backend Work is done here API 
//re:- This is Call Page for Ajax, We are Add Update delete Data using this APi and Run Linux shell Script 
//by:- Raj


// ## start session to use session value.
session_start();

include("../config.php");
include("../config_regServer.php");

// ## get server ipaddress.
$ip_server = $_SERVER['SERVER_ADDR'];


//sub:- The isset function in PHP is used to determine whether a variable is set or not. 
//re:- We are Using thi Isset for check we have Recived the Value or not  
//by:- Raj



// ## configure google drive to here on server using google code
if (isset($_POST['save_config']) && isset($_POST['google_email_id']) && isset($_POST['googlecode']) && isset($_POST['admin']) ) 
{
        $email = trim($_POST['google_email_id']);
        $googlecode = trim($_POST['googlecode']);
        $admin = trim($_POST['admin']);

        // ## check if all data passed is not empty.
        if (!empty($email) && !empty($googlecode) && !empty($admin)) {
            // ## backupconfig.sh script will configiure google drive on server using google code passed from here
            $value=`sudo /var/www/html/sugam/backup/backupconfig.sh configdrive "$admin" "$email" "$googlecode" `;
        }
        // ## backupconfig.sh will update the status of configuring google drive as success or fail this file
        $status = trim(`cat /home/$admin/$admin-drive`);
        // ## return content of file as success or failed 
        echo trim($status);
}

// ## get previously configured detail of backup  from database table manage_backup.
if (isset($_POST['get_backup_detail']) && isset($_POST['admin']) )
{
    $admin = trim($_POST['admin']);
    $testadmin = $_POST['admin'];
    // ## query to get all detail from databse of specific admin
    $sth = mysqli_query($conn, "SELECT * from manage_backup where admin = '$admin' limit 1");
    // ## create an empty array
    $rows = array();
    // ## while loop on data fetched from database
    while($r = mysqli_fetch_assoc($sth)) {
        // ## added array data to rows[] array.
        $rows[] = $r;
    }
    // ## json encode array data and return as response. 
    print json_encode($rows);

}

// ## update change selected for destination of backup to database 
if (isset($_POST['destination_selected']) && isset($_POST['admin']) && isset($_POST['selected']) )
{
    $admin = trim($_POST['admin']);
    $selected = trim($_POST['selected']);
    
    // ## query to update value in sugam databse.
    $run_querry = mysqli_query($conn, "UPDATE `manage_backup` SET `destination`= '$selected' WHERE `admin` LIKE '$admin' ");
    // ## query to update value in sugam registartion databse.
    $run_querry2 = mysqli_query($regServConn, "UPDATE `manage_backup` SET `destination`= '$selected' WHERE `admin` LIKE '$admin' AND `ipaddress` LIKE '$ip_server' ");

    echo $selected;
    echo $admin;
    // echo ("UPDATE `manage_backup` SET `destination`= '$selected' WHERE `admin` LIKE '$admin' ");
}

// ## delete configired mail id for backup
if (isset($_POST['delete_mail']) && isset($_POST['admin']) )
{
    $admin = trim($_POST['admin']);
    // ## backupconfig.sh script will remove google drive configured 
    $value=`sudo /var/www/html/sugam/backup/backupconfig.sh delete_mail $admin `;
    $run_querry = mysqli_query($conn, "UPDATE `manage_backup` SET `mail` = '--' WHERE `admin` LIKE '$admin' ");
    $run_querry2 = mysqli_query($regServConn, "UPDATE `manage_backup` SET `mail` = '--' WHERE `admin` LIKE '$admin' AND `ipaddress` LIKE '$ip_server' ");
    // echo $admin;
    echo $sucessfull;
}

// ## update change selected for type of backup to database 
if (isset($_POST['backup_type_selected']) && isset($_POST['admin']) && isset($_POST['selected']) )
{
    $admin = trim($_POST['admin']);
    $selected = trim($_POST['selected']);

    // ## query to update value in sugam databse.
    $run_querry = mysqli_query($conn, "UPDATE `manage_backup` SET `type`= '$selected' WHERE `admin` LIKE '$admin' ");
    // ## query to update value in sugam registartion databse.
    $run_querry2 = mysqli_query($regServConn, "UPDATE `manage_backup` SET `type`= '$selected' WHERE `admin` LIKE '$admin' AND `ipaddress` LIKE '$ip_server' ");
    echo ('sucessfull');
}

// ## create tree structure html data of backup data on google drive.
if (isset($_POST['backup_tree_data']) && isset($_POST['admin']))
{

    $admin = trim($_POST['admin']);
    $loginuser = trim($_POST['admin']);
    `sudo mkdir /home/"$admin"/Grive/ `;
    `sudo setfacl -m other:rwx /home/"$admin"/Grive`; 
    `sudo setfacl -R -d -m o::rwx /home/"$admin"/Grive `;
    // ## create file on server.
    `sudo touch /home/"$admin"/Grive/.datalist `;
    // ## get list of data from google drive to to a file on server.
    `cd /home/$admin/.$admin"_prefix"/drive_c/; sudo /opt/tigdrive/bin/drive ls -recursive -no-prompt  backup/ | cut -d'/' -f3- | uniq >/home/"$admin"/Grive/.datalist`;
    // ## get data list from file to variable.
    $get_google_drive_list = `cat /home/$admin/Grive/.datalist`;

    
    if (!empty($get_google_drive_list)) {
       // $get_googl/opt/tigdrive/bin/drive ls -recursive -no-prompt -directories Data/ | cut -d'/' -f3 | uniqe_drive_list = str_replace('/Data/', '', $get_google_drive_list);
       
        // ## explode data list with new line
        $g_data = explode("\n", $get_google_drive_list);
        // ## delete last empty element
        array_pop($g_data);
    
        // ## create dir if not exist /home/$loginuser/Grive/Data/
        if (!file_exists("/home/$loginuser/Grive/Data")) {
            `sudo mkdir -p /home/$loginuser/Grive/Data`;
        }
    
        $gd = "/home/$loginuser/Grive/Data/";
        chdir("$gd");
        
        // ## Remove All home Data Folder Structure Before Creating Fresh
        `sudo rm -rf /home/$loginuser/Grive/Data/*`;
        // ## run for each on data list and crete same structure on server like its on google drive.
        foreach ($g_data as  $value) {
            if (!empty($value) && !file_exists($value)) {
                    // ## create dir.
                    `sudo mkdir -p $value`; 
            }
        }
        

        // ## scan directory to get list of file's as array.  
        function listFolders($dir)
        {
            $dh = preg_grep("/(\w+)(_Backup|_not_rename)/", scandir($dir), PREG_GREP_INVERT);
            $return = array();
            foreach ($dh as $folder)
            {
                if ($folder != '.' && $folder != '..')
                {
                    if (is_dir($dir . '/' . $folder))
                    {
                        // ## adding data to array.
                        $return[] = array($folder => listFolders($dir . '/' . $folder));
                    }
                }
            }
            // ## return array genrated of data
            return $return;
        }

        $a2 = listFolders("/home/$admin/Grive/Data/");

        // ## convert array data to json by encodeing.
        $treedata1 = json_encode($a2);
        $final_tree_data1 = json_decode($treedata1,true);
        $finaltree = '';
        // ## function to genrate html tree data from array of file list.  
        function mygenerateTreeMenu($final_tree_data,$limit = 0)
        {
            $key = '';
            $tree = '';
            if ($limit > 1000) return '';
            foreach ($final_tree_data as $key => $value)
            {
                // ## if name of file is not all integer then it will be shown in UI as folder
                // ## else it will be shown as backupfile in UI.
                if (!is_int($key))
                {
                    $tree .= '<li class="tree-title" >';
                    $tree .= "<a href='#' class=".$key." id='google_".$key."_$limit' onmousedown='toc_data(this.id);'><span class='context-menu-one'>".$key."</span></a><ul>";
                    $tree .= mygenerateTreeMenu($value,$limit++);
                    $tree .= "</ul></li>\n";
                }
                else
                {
                    if($key>1000){
                        $tree .= "<li><a href='#' class=".$key." id='google_".$key."_$limit' onmousedown='toc_data(this.id);'><span class='context-menu-one'>".$key."</span></a>";
                        $tree .= mygenerateTreeMenu($value,$limit++);
                        $tree .= "</li>\n";
                    }
                    else{
                        $tree .= mygenerateTreeMenu($value,$limit++);
                    }
                }
            }
            // ## tree variable holds HTML data of tree structure 
            return $tree;
        }

        // $finaltree_google_data= mygenerateTreeMenu($final_tree_data1);
        // echo $finaltree_google_data;
        $finaltree_google_data= mygenerateTreeMenu($final_tree_data1);
        $finaltree_cti='<li><a class="context-menu-Data" href="#">backup</a><ul id="google_basicTree">'.$finaltree_google_data.'</ul></li>';
        // ## return html tree data.
        echo $finaltree_cti;
    }

}


// ## restore data from googledrive to sugam server.
if (isset($_POST['single_upload_only']) && $_POST['single_upload_only']=='single_upload_only' && isset($_POST['admin']) && isset($_POST['company_name'])) {

    $company_name = trim($_POST['company_name']);
    $admin = trim($_POST['admin']);
    $count=trim(`sudo echo "$checkcompany" | awk 'length($1) == 5 { print $1 }'`);
    // ## backupconfig.sh script will restore data from googlr drive.
    $value=`sudo /var/www/html/sugam/backup/backupconfig.sh restoredata $admin $company_name`;
    $restore=trim(`cat /home/$admin/$admin-restore`);
    // ## /home/$admin/$admin-restore file crontain status of restoring.
    echo ($restore);
}

// ## delete data of googledrive.
if (isset($_POST['trash_this']) && $_POST['trash_this']=='trash_this' && isset($_POST['admin']) && isset($_POST['company_name'])) {

    // ## name and path of file to remove.
    $company_name = trim($_POST['company_name']);
    $admin = trim($_POST['admin']);
    if ($company_name){
        // ## backupconfig.sh script will remove data from google drive .
        $result=`sudo /var/www/html/sugam/backup/backupconfig.sh trash_data '$admin' '$company_name'`;
        // ## status of deleteing file updated in this file. 
        $status=trim(`cat /home/$admin/$admin-trash`);
        echo ($status);
    }
    else
    {
        echo ('error');
    }
}

// ## check if backup file available in backend.
if (isset($_POST['checkbackup']) && $_POST['checkbackup']=='checkbackup' && isset($_POST['admin']) && isset($_POST['loginuser'])) {


    $admin=trim($_POST['admin']);
    $user=trim($_POST['loginuser']);
    `sudo mkdir -p /var/www/html/backup/$admin/$user`;
    $chown = 'sudo chmod -R 777 /var/www/html/backup/$admin/$user';
    $chowns = shell_exec($chown);
    
    if (!empty($user)){
        // ## /var/www/html/backup/$admin/$user is the folder where data is checked if available
        // ## using find command backup file is checked 
        $command = "cd /var/www/html/backup/$admin/$user && find -maxdepth 5 -type f | cut -c 3- ";
    }
    
    $plist = shell_exec($command);
    if (!empty($plist)){
        // ## if files found in the folder then 
        // ## using foreach loop on list of all file's found in folder, 
        // ## json data is generated

        $job_list = (explode("\n",$plist));
        // ## empty array is declared
        $printer_file_list = array();
        
        foreach ($job_list as $job)
        {
                $jfile = $job;
                // ## list all file in /var/www/html/backup/$admin/$user/ folder 
                $command2 = "ls -latr /var/www/html/backup/$admin/$user/ | grep -w ".$job." | awk '{print $6,$7,$8}' ";
                $job_time = shell_exec($command2);
    
                if (!empty($jfile))
                {
                    // ## check if any notification available in database for this user
                    $queryresult = mysqli_query($conn, "SELECT * FROM backup_notify where admin LIKE '$admin' AND username LIKE '$user' ");
                    if (mysqli_num_rows($queryresult)==0) {
                        // ## if no notification is available in database then 
                        // ## notify variable will be set as NO.
                        $notify = 'NO';
                    }else
                    {
                        // ## if nofication is avalable in database then.
                        // ## notify variable will be set as Yes.
                        $querrydel=mysqli_query($conn, "DELETE FROM backup_notify WHERE admin LIKE '$admin' AND username LIKE '$user' ");
                        $notify = 'YES';
                    }
                    // ## adding details to array list
                    $printer_file_list[] = array("jfile" => $jfile, "jtime" => $job_time , "notify" => $notify );

                }

                
            //}
        }
        
        // ## json encode array
        $final_list = json_encode($printer_file_list);
        
        echo $final_list;
    }
    else{
        echo ('no files');
    }
}


// ## check if backup file available for google drive download and if availble then retrun json data with file name.
if (isset($_POST['checkbackuplocal']) && $_POST['checkbackuplocal']=='checkbackuplocal' && isset($_POST['admin']) && isset($_POST['loginuser'])) {

    $admin=trim($_POST['admin']);
    $user=trim($_POST['loginuser']);
    `sudo mkdir -p /home/$admin/.$admin/drive_c/backup/`;
    $chown = 'sudo chmod -R 777 /home/$admin/.$admin/drive_c/backup/';
    $chowns = shell_exec($chown);
    
    if (!empty($user)){
        // ## using find command check if file available for download.
        // ## /home/$admin/.$admin/drive_c/backup/ is the folder where file will be for googledrive.      
        $command = "cd /home/$admin/.$admin/drive_c/backup/ && find -maxdepth 5 -type f | cut -c 3- ";
    }

    $plist = shell_exec($command);
    if (!empty($plist)){
        // ## file found to download
        $job_list = (explode("\n",$plist));
        
        $printer_file_list = array();
        
        // ## foreach loop to all file's found in folder
        foreach ($job_list as $job)
        {
                $jfile = $job;
                // ## 
                $command2 = "ls -latr /home/$admin/.$admin/drive_c/backup/ | grep -w ".$job." | awk '{print $6,$7,$8}' ";
                $job_time = shell_exec($command2);
                if (!empty($jfile))
                {
                    $queryresult = mysqli_query($conn, "SELECT * FROM backup_notify where admin LIKE '$admin' AND username LIKE '$user' ");
                    if (mysqli_num_rows($queryresult)==0) {
                        $notify = 'NO';
                    }else
                    {
                        $querrydel=mysqli_query($conn, "DELETE FROM backup_notify WHERE admin LIKE '$admin' AND username LIKE '$user' ");
                        $notify = 'YES';
                    }
                    $printer_file_list[] = array("jfile" => $jfile, "jtime" => $job_time , "notify" => $notify );
                }                
        }
        $final_list = json_encode($printer_file_list);
        
        echo $final_list;
    }
    else{
        // ## file not found for download.
        echo ('no files');
    }
}

// ## save newly configiured emailid to database.
if (isset($_POST['save_email_database']) && isset($_POST['admin']) && isset($_POST['google_email_id']) )
{
    $admin = trim($_POST['admin']);
    // ## emailid is recived in post method
    $email = trim($_POST['google_email_id']);

    // ## update email id in sugam database
    $run_querry = mysqli_query($conn, "UPDATE `manage_backup` SET `mail`= '$email' WHERE `admin` LIKE '$admin' ");
    // ## update emialid in sugam registration databse.
    $run_querry2 = mysqli_query($regServConn, "UPDATE `manage_backup` SET `mail`= '$email' WHERE `admin` LIKE '$admin' AND `ipaddress` LIKE '$ip_server' ");
    echo ('sucessfull');
}

// ## manuall download all data file to google drive.
if (isset($_POST['download_drive_manual']) && $_POST['download_drive_manual']=='download_drive_manual' && isset($_POST['admin'])) {
    $admin = trim($_POST['admin']);
    $username = trim($_POST['username']);
    echo $admin;
    // ## backupconfig.sh script will download drive data google drive.
    `sudo /var/www/html/sugam/backup/backupconfig.sh download_now $admin drive $username `;
}

// ## shown backup logs on drom google drive to UI
// ## this function will return html data of backup logs to google drive. 
if (isset($_POST['backuplog']) && $_POST['backuplog']=='backuplog' && isset($_POST['admin'])) {
    $admin = trim($_POST['admin']);
    echo "<p>";
    echo "<b>Last Backup:</b>";
    // ## logs of last backup create on server from logs in database 
    $query = 'SELECT * from	backuplogs WHERE `admin` LIKE "'.$admin.'" AND `action` LIKE "created" ORDER BY id DESC limit 1 ';
    $result = mysqli_query($conn, $query );
    $data = array();
    while($row = mysqli_fetch_array($result))
    {
        $destination = trim($row["destination"]);
        $type = trim($row["type"]);
        $action = trim($row["action"]);
        $time = trim($row["time"]);
        $user = trim($row["user"]);
        $date = trim($row["date"]);

        if($type == "auto"){
            $backuptype="Automatically";
        }else{
            $backuptype="Manually";
        }

        if($destination == 'local' ){
            $backu_destination='PC';
        }else{
            $backu_destination='GD';
        }

        // echo "</br>Backup";
        if ($action == 'created'){
            // ## line of log which for backup creted
            echo "</br>Backup $action on $date at $time by $user";
        }
        else{
            // ## line of log for sent/donwloaded from server.
            echo "</br>Backup $action to $backu_destination on $date at $time by $user $backuptype ";
        }
    }

    // ## logs of last backup sent from server in  logs of database 
    $query = 'SELECT * from	backuplogs WHERE `admin` LIKE "'.$admin.'" AND `action` LIKE "sent" ORDER BY id DESC limit 1 ';
    $result = mysqli_query($conn, $query );
    $data = array();
    while($row = mysqli_fetch_array($result))
    {
        $destination = trim($row["destination"]);
        $type = trim($row["type"]);
        $action = trim($row["action"]);
        $time = trim($row["time"]);
        $user = trim($row["user"]);
        $date = trim($row["date"]);

        if($type == "auto"){
            $backuptype="Automatically";
        }else{
            $backuptype="Manually";
        }

        if($destination == 'local' ){
            $backu_destination='PC';
        }else{
            $backu_destination='GD';
        }

        // echo "</br>Backup";
        if ($action == 'created'){
            echo "</br>Backup $action on $date at $time by $user";
        }
        else{
            echo "</br>Backup $action to $backu_destination on $date at $time by $user $backuptype ";
        }

        $a = new \DateTime($date);
        $b = new \DateTime;

        // ## check no of day backup not done in server
        $noofday = $a->diff($b)->days;

        // ## shwon in log if backup not done last 1 days 
        if($noofday != 0){
            echo "<br><br>";
            echo "<b>Backup not done since last $noofday days</b>";
        }

    }

    echo "<br><br>";

    echo "<b>Backup Logs:</b>";
    // get all logs of backups cretaed and sent from database.
    $query = 'SELECT * from	backuplogs WHERE `admin` LIKE "'.$admin.'" ORDER BY id DESC ';
    $result = mysqli_query($conn, $query );
    $data = array();
    // ## loop on all data fetched
    while($row = mysqli_fetch_array($result))
    {
        $destination = trim($row["destination"]);
        $type = trim($row["type"]);
        $action = trim($row["action"]);
        $time = trim($row["time"]);
        $user = trim($row["user"]);
        $date = trim($row["date"]);

        if($type == "auto"){
            $backuptype="Automatically";
        }else{
            $backuptype="Manually";
        }

        if($destination == 'local' ){
            $backu_destination='PC';
        }else{
            $backu_destination='GD';
        }

        if ($action == 'created'){
            // ## line of log which for backup creted
            echo "</br>Backup $action on $date at $time by $user";
        }
        else{
            // ## line of log for sent/donwloaded from server.
            echo "</br>Backup $action to $backu_destination on $date at $time by $user $backuptype ";
        }

    }
    echo "</p>";

}

//sub:- Backup Type we are checking the same 
//re:- Based on this we send the Backup  
//by:- Raj


// ## show notification to admin if other user has taken backup.
if (isset($_POST['checktypebackup'])  && isset($_POST['admin']) && isset($_POST['loginuser'])) {

    $adminname = $_POST['admin'];
    // ## check in database if  any notification is available in database table
    $query1 = "select * from backup_notify WHERE admin LIKE '$adminname' AND user_type LIKE 'user' limit 1";
    $result1 = mysqli_query($conn, $query1);
        $user_data1 = mysqli_fetch_assoc($result1);
        $checktime = $user_data1['date_time'];
        $username = $user_data1['username'];
        if(!empty($username)){
            // ## notification available in databse, return  notification statement
            echo "$username user has taken backup on $checktime,<br> This user is not allowed to take backup";      
            // ## remove all current notification in database.
            $querrydel=mysqli_query($conn, "DELETE FROM backup_notify WHERE admin LIKE '$adminname' AND username LIKE '$username' ");
        }
        else
        {
            // ## no notification for admin in database, then return as "no user"
             "no user";
        }
}

// ## save autobackup schedule time in on server.
if (isset($_POST['save_autobackup_time']) && $_POST['save_autobackup_time']=='save_autobackup_time' && isset($_POST['admin'])) {
    $admin = trim($_POST['admin']);
    $autohours = trim($_POST['autohours']);
    $autominutes = trim($_POST['autominutes']);

    $schedule=$autohours.':'.$autominutes;
    // ## backupconfig.sh will add schedule to server cron.
    `sudo /var/www/html/sugam/backup/backupconfig.sh autobackup $admin set_schedule $autohours $autominutes`;
    // ## get previously schedule time form database
    $query1="SELECT auto_time from manage_backup  WHERE  `admin` LIKE '$admin' limit 1 ";
    $result1 = mysqli_query($conn, $query1 );
    $row2= mysqli_fetch_assoc($result1);
    $autotime=$row2['auto_time'];

    // ## check if no previous schedule is set.
    if (empty($autotime)){
        // ## no previous schedule, add new schedule to databse simply using update query
        $query = 'UPDATE manage_backup SET auto_time="|'.$schedule.'|" WHERE  `admin` LIKE "'.$admin.'" limit 1 ';
        $query12 = 'UPDATE manage_backup SET auto_time="|'.$schedule.'|" WHERE  `admin` LIKE "'.$admin.'" AND `ipaddress` LIKE "'.$ip_server.'"  limit 1 ';
        $result = mysqli_query($conn, $query );
        $result = mysqli_query($regServConn, $query12 );
    }else{
        // ## there is previous schdule in databse, so add new schedule using concat in update querry.
        // upadte to sugam server querry
        $query ='UPDATE manage_backup SET auto_time = CONCAT(auto_time, "|'.$schedule.'|") WHERE  `admin` LIKE "'.$admin.'" limit 1 ';
        // ## update to registartion server querry.
        $query12 = 'UPDATE manage_backup SET auto_time = CONCAT(auto_time, "|'.$schedule.'|") WHERE  `admin` LIKE "'.$admin.'" AND `ipaddress` LIKE "'.$ip_server.'"  limit 1 ';
        $result = mysqli_query($conn, $query );
        $result = mysqli_query($regServConn, $query12 );
    }
    // ## return response as success.
    echo 'Success';
}

// ## remove specific autobackup schedule time.
if (isset($_POST['delete_autobackup_time']) && $_POST['delete_autobackup_time']=='delete_autobackup_time' && isset($_POST['admin'])) {
    // data from post.
    $admin = trim($_POST['admin']);
    $remove_time = trim($_POST['remove_time']);

    // ## remove schudule from server
    `sudo /var/www/html/sugam/backup/backupconfig.sh autobackup $admin remove_schedule '$remove_time' `;
    // ## remove schedule from sugam database
    $query='UPDATE manage_backup SET auto_time = REPLACE(auto_time, "|'.$remove_time.'|", "") WHERE  `admin` LIKE "'.$admin.'" limit 1';
    // ## remove scheudle from server
    $query12='UPDATE manage_backup SET auto_time = REPLACE(auto_time, "|'.$remove_time.'|", "") WHERE  `admin` LIKE "'.$admin.'" AND `ipaddress` LIKE "'.$ip_server.'"  limit 1';

    $result = mysqli_query($conn, $query );
    $result = mysqli_query($regServConn, $query12 );
    // return response as Success.
    echo 'Success';
}

// ## list all Data added in Datapath.xml file for autobackup
if (isset($_POST['list_datapath']) && $_POST['list_datapath']=='list_datapath' && isset($_POST['admin'])) {
    $admin = trim($_POST['admin']);

    // regenrate Datapath.xml for admin
    `sudo -u $admin /var/www/html/sugam/backup/backupconfig.sh autobackup $admin datapath `;
    // from xml data convert to list of data and  in a file 
    `sudo -u $admin /var/www/html/sugam/backup/backupconfig.sh autobackup $admin list_datapath `;
    // ## get list of data from file
    $output=shell_exec("cat /home/$admin/$admin\_datapath");
    // return as response.
    echo $output;
}

// ## get list of all previous schedule time for autobackup form database.
// ## this function will return html data of schedule time to show in UI.
if (isset($_POST['scheduledTime']) && $_POST['scheduledTime']=='scheduledTime' && isset($_POST['admin'])) 
{
    $admin = trim($_POST['admin']);
    // ## fetch prevous schedule in database.
    $query1="SELECT auto_time from manage_backup  WHERE  `admin` LIKE '$admin' limit 1 ";
    $result1 = mysqli_query($conn, $query1 );
    $row2= mysqli_fetch_assoc($result1);
    $autotime=$row2['auto_time'];
    $value='';
    // ## multile time is separted by pipe(|)
    $times = (explode("||",$autotime));
    $count=count($times);

    foreach ( $times as $time ){
        $time1=trim(str_replace("|","",$time));
        
        if (!empty($time1)){
            // ## html data of schdule time with delete button.
            $value .= "<div class='btn-group btn-outline-info' style='padding: 3px' role='group' >
            <button type='button' class='btn btn-secondary btn-sm'>$time1</button>
            <button type='button' class='btn btn-secondary btn-sm' onClick='delete_autotime(".'"'.$time1.'"'.")' >X</button>
            </div>";
        }
    }

    $job_list = (explode("\n",$plist));
    $schedule_list = array();
    // ## create array of data.
    $schedule_list[] = array("schedulings" => $value, "count" => $count );
    $final_list = json_encode($schedule_list);
    echo $final_list;
}


// ## verify password added entered from user with lock password in database 
if (isset($_POST['verifyPass']) && $_POST['verifyPass']=='verifyPass' && isset($_POST['admin'])) {

    // ## datapassed from UI
    $admin = trim($_POST['admin']);
    $checkpss = trim($_POST['checkpss']);

    if(!empty($checkpss) )
    {
        $serverurl = str_replace('.tallycloud.in','',$_SERVER['HTTP_HOST']);
        //read from database
        // ## match from database
        $query = "select * from `admininformation` where `admin` = '$admin' and lockPswd = '$checkpss' limit 1";
        $result = mysqli_query($conn, $query);
        $noofrow=mysqli_num_rows($result);
        // ## if no rows found return as failed else return as success
        if($result && mysqli_num_rows($result) > 0)
        {
            // ## set session varibale verifiedPasswd as True
            $_SESSION["verifiedPasswd"] = "True";
            echo 'Success';
        }
        else
        {
            // ## set session varibale verifiedPasswd as false
            $_SESSION["verifiedPasswd"] = "False";
            echo 'Failed';
        }
    }

}


?>