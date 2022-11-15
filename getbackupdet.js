    //#sub:- This File is Every Intervel Check Backup hase Taken bu User or NOt 
    //#re:- Backupmodel3.php and Backupcnfig.sh 
    //#By:- Raj


// ## geting value from window "global" variable of browser
var admin1 = window.admin;
var loginuser1 = window.loginuser;
var admin = admin1;
var loginuser = loginuser1;

// ## creating empty array name "downloading".
downloading = [];
console.log('getbackupjs running' + admin1);
var downloadinterval;
var checkbackupinterval;

// onload javasctript function
$(document).ready(function() {
    var admin = admin1;
    var loginuser = loginuser1;

    // ## get backup configration detail from data using ajax call to backup/backup_backend.php with get_backup_detail parameter .
    $.post("backup/backup_backend.php", { 'get_backup_detail': 'get_backup_detail', admin: admin }, function(result, status, xhr) {

        var admindetail = '';

        console.log("result--->" + result);
        // ## parse json data of result.
        var detail = JSON.parse(result);
        // ## now admin backup detail's is now in "admindetail" variable.
        var admindetail = detail[0];
        console.log(detail);
        console.log(admindetail.destination + "check detail in destionation");

        // ## add deatil to window vairable of browser
        //#sub:- window. variableName means that the variable is being declared at the global scope. 
        //This means any JS code will have access to this variable.
        //#re:- This is Related with Backup Type where we have to do the backup
        //#By:- Raj
        
        window.admindestination = admindetail.destination
        window.admintype = admindetail.type

        // ## if destination if local then we have to check if type is auto or not
        // ## if type is also auto then we have to start running a function at interval
        // ## to automaically download data in local.
        if (admindetail.destination == 'local') {
            if (admindetail.type == 'auto') {
                window.admintype = admindetail.type
                // ## stop previously function running in loop at interval
                clearInterval('downloadinterval');
                // ## backupdownload() will run at 5 sec interval to check and download file if available in backend.
                downloadinterval = setInterval(function() { backupdownload() }, 5000);                
                console.log(window.admintype + "check")
                console.log('running download function');
            } else {
                // ## checkbackupfile() will runa at 5 sec interval to  check if backup available in backend but will not download file. 
                checkbackupinterval = setInterval(function() { checkbackupfile() }, 5000);
            }
        } else {
            // ## checkbackupfile() will runa at 5 sec interval to  check if backup available in backend but will not download file.
            checkbackupinterval = setInterval(function() { checkbackupfile() }, 5000);
        }

    });
});

// ## check if backup file available in backend . if available then make required changes in UI but will not download file automaticall.
function checkbackupfile() {
    var admin = admin1;
    var loginuser = loginuser1;
    // ## check if backup download destination is set to local.
    if (window.admindestination == 'local') {
        console.log('checking backup available or not, ' + window.admindestination + ' / ' + window.admintype + ' selected');
        // ## ajax call to backup_backend.php will check and give response in json data if file available availbe
        // ## else it will response as no files.
        $.ajax({
            type: "POST",
            url: 'backup/backup_backend.php',
            data: ({ 'checkbackup': 'checkbackup', 'admin': admin, 'loginuser': loginuser }),
            success: async function(data) {
                if (data == 'no files') {
                    $('#backup-status').html('Backup Not Available');
                    $("#download-button").prop("disabled", true);
                    $("#download-button").css('cursor', 'not-allowed');
                    console.log("no files");
                } else {
                    console.log("YEs files");
                    $('#backup-status').html('Backup Available');
                    $("#download-button").prop("disabled", false);
                    $("#download-button").css('cursor', 'pointer');
                    var response = $.parseJSON(data);
                    var notifier;
                    for (const item of response) {
                        notifier = item.notify
                            // console.log(item.notify);
                    }
                    // this is the notifier of backup
                    // if backup is availle and type is not auto then it will automaticall open backup modal as notification. 
                    if (notifier == 'YES') {
                        if (window.admintype != 'auto') {
                            $('#backupmodalbutton').click();
                        }
                    }
                    console.log(notifier + '---notify')
                }
            },
            error: function() {
                // alert('Error occured in ajax');
                console.log("error in ajax call");
            }
        });
    } else {
        // ## if backup desination is not set to local.
        console.log('checking backup available or not, ' + window.admindestination + ' / ' + window.admintype + ' selected');
        // ## ajax call to backup_backend.php will check and give response in json data if file available availbe
        // ## else it will response as no files.
        $.ajax({
            type: "POST",
            url: 'backup/backup_backend.php',
            data: ({ 'checkbackuplocal': 'checkbackuplocal', 'admin': admin, 'loginuser': loginuser }),
            success: async function(data) {
                if (data == 'no files') {
                    $('#backup-status').html('Backup Not Available');
                    $("#download-button").prop("disabled", true);
                    $("#download-button").css('cursor', 'not-allowed');
                    console.log("no files");
                } else {
                    console.log("YEs files");
                    $('#backup-status').html('Backup Available');
                    $("#download-button").prop("disabled", false);
                    $("#download-button").css('cursor', 'pointer');
                    var response = $.parseJSON(data);
                    var notifier;
                    for (const item of response) {
                        notifier = item.notify
                            // console.log(item.notify);
                    }
                    if (notifier == 'YES') {
                        if (window.admintype != 'auto') {
                            $('#backupmodalbutton').click();
                        }
                    }
                    console.log(notifier + '---notify')
                }
            },
            error: function() {
                // alert('Error occured in ajax');
                console.log('Error occured in ajax');
            }
        });
    }
}

// ## backupdownload() function will check in backend if file available to download and donwload file if available . 
function backupdownload() {
    var admin = admin1;
    var loginuser = loginuser1;

    console.log('checking backup available or not, ' + window.admindestination + ' / ' + window.admintype + ' selected');
    // ## ajax call to check file
    $.ajax({
        type: "POST",
        url: 'backup/backup_backend.php',
        data: ({ 'checkbackup': 'checkbackup', 'admin': admin, 'loginuser': loginuser }),
        success: async function(data) {
            // ## check if file found or not.
            if (data == 'no files') {
                $('#backup-status').html('Backup Not Available');
                $("#download-button").prop("disabled", true);
                $("#download-button").css('cursor', 'not-allowed');
                console.log("no files");
            } else {
                console.log("yes files");
                console.log("newauto test");
                console.log(window.admintype + "check1")

                if (window.admintype == 'auto') {
                    //let data2 ='[{"jfile":"file3","jtime":"Jan 10 14:41"},{"jfile":"file5","jtime":"Jan 10 14:41"},{"jfile":"file2","jtime":"Jan 10 14:41"},{"jfile":"file4","jtime":"Jan 10 14:41"}]';
                    var response = $.parseJSON(data);
                    console.log("response of checkbackup");
                    console.log('response of checkbackup' + response);

                    var listHTML = '';
                    for (const item of response) {
                        console.log('in the loop' + item.jfile);
                        console.log(downloading);

                        // ## check if file is already downloading or downlaoded.
                        // ## avoid file downloading again if file is downloading or already downloaded.
                        var checkdownload = downloading.includes(item.jfile);
                        console.log(checkdownload + 'check download')
                        if (downloading.indexOf(item.jfile) !== -1) {
                            console.log('in else condition for checkdownload')
                        } else {
                            // ## download file using download() by passing filename to the function.
                            const val = await download(item.jfile);
                        }
                    }
                    // alert('loop completed');
                }
            }
        },
        error: function() {
            // alert('Error occured');
        }
    });
}

function downloadmanual() {
    var admin = admin1;
    var loginuser = loginuser1;

    // ## ajax call to get name of file to  download file from backend
    console.log('running backup downloadmanual(), ' + window.admindestination + ' , ' + window.admintype + ' selected');
    alert('Downloading Backup file')
    $.ajax({
        type: "POST",
        url: 'backup/backup_backend.php',
        data: ({ 'checkbackup': 'checkbackup', 'admin': admin, 'loginuser': loginuser }),
        success: async function(data) {

            if (window.admindestination == 'local') {

                if (data == 'no files') {
                    // ## no file exist to download
                    // ## keep backup  download button disabled
                    // ## and test as "Backup Not Available"
                    $('#backup-status').html('Backup Not Available');
                    $("#download-button").prop("disabled", true);
                    $("#download-button").css('cursor', 'not-allowed');
                    console.log("no files");
                    alert('No Backup File available to download')
                } else {
                    // ## file exist
                    // ## auto click to backup downlaod button.
                    $('#backupmodalbutton').click();
                    console.log("yes files");
                    console.log(window.admintype + "check1")
                    // ## response data is in json fromat so parse the json data.
                    var response = $.parseJSON(data);
                    console.log("response of checkbackup");
                    console.log('response of checkbackup' + response);
                    var listHTML = '';
                    // ## for loop on all files in json json data
                     for (const item of response) {
                        console.log('in the loop' + item.jfile);
                        // ## checkdownload variable will hold the name of file which is currently donwloading and
                        // ## also name of file which is doownloaded in this session.
                        // ## adding file name to downloading list
                        var checkdownload = downloading.includes(item.jfile);
                        const val = await download(item.jfile);
                    }
                }

            } else {
                $('#backupmodalbutton').click();
                console.log('downloading manual')
                $.post("backup/backup_backend.php", { 'download_drive_manual': 'download_drive_manual', admin: admin }, function(result, status, xhr) {
                    console.log('download drive manual' + result);
                });
            }
        },
        error: function() {
            // alert('Error occured');
        }
    });
}


// ## download function take filename as parameter to download file.
function download(filename) {

    var admin = admin1;
    var loginuser = loginuser1;

    if (filename){
        // ## add filename to window variable
        window.filename = filename;
        console.log(filename + "downloadfile");
        var url = "/var/www/html/backup/" + admin + "/" + filename;

        console.log(url + "urlcheck");
        downloading.push(filename);
        // ## open script downloadfile.php in new tab and also pass data in url (by get method) to donwload file; 
        window.open("backup/downloadfile.php?admin=" + admin + "&loginuser=" + loginuser + "&filename=" + filename + '&backuptype=' + window.admintype, "_blank");
    
    }
}

// ## sleep function in javascript
function sleep(milliseconds) {
    const date = Date.now();
    let currentDate = null;
    do {
        currentDate = Date.now();
    } while (currentDate - date < milliseconds);
}
