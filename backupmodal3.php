
<?php 


//sub:-This File is used for Give Value to Sesson for Run Userlogin While Backup is Running
//re:- We are Going to use this File  for Backup
//by:- Raj

// ## php session is started to access session values
session_start();

// ## getting session value in variable. 
$usernamenew = $_SESSION["username"];
$adminsession = $_SESSION['adminsession'];
$passwordverified = $_SESSION["verifiedPasswd"];

?>
<!DOCTYPE html>

<?php 
// ## database connection
include("../config.php");
?>


<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {font-family: Arial, Helvetica, sans-serif;}

    .loader {
      border: 5px solid #f3f3f3;
      border-radius: 50%;
      border-top: 5px solid #3498db;
      border-bottom: 5px solid #3498db;
      width: 20px;
      height: 20px;
      -webkit-animation: spin 2s linear infinite;
      animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
      0% { -webkit-transform: rotate(0deg); }
      100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    /*kill user end*/

    /*whole page ajax start*/
    .loading {
      position: fixed;
      z-index: 999;
      height: 2em;
      width: 2em;
      overflow: show;
      margin: auto;
      top: 0;
      left: 0;
      bottom: 0;
      right: 0;
    }

    /* Transparent Overlay */
    .loading:before {
      content: '';
      display: block;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0,0,0,0.3);
    }

    /* :not(:required) hides these rules from IE9 and below */
    .loading:not(:required) {
      /* hide "loading..." text */
      font: 0/0 a;
      color: transparent;
      text-shadow: none;
      background-color: transparent;
      border: 0;
    }

    .loading:not(:required):after {
      content: '';
      display: block;
      font-size: 10px;
      width: 1em;
      height: 1em;
      margin-top: -0.5em;
      -webkit-animation: spinner 1500ms infinite linear;
      -moz-animation: spinner 1500ms infinite linear;
      -ms-animation: spinner 1500ms infinite linear;
      -o-animation: spinner 1500ms infinite linear;
      animation: spinner 1500ms infinite linear;
      border-radius: 0.5em;
      -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
      box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
    }

    .blur {
		filter: blur(10px);
	  }


    /* Animation */

    @-webkit-keyframes spinner {
      0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }
    @-moz-keyframes spinner {
      0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }
    @-o-keyframes spinner {
      0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
      }
    }
    @keyframes spinner {
      0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
      }


        /*whole page ajax end*/
    }

  .smalldesc {
    height: 0px;
    overflow:hidden;
    border:1px;
  }
  .bigdesc {
    height: auto;
    /* height:224px; */
    overflow:auto;
    /* width: auto; */
  }

  .logdivadded {
    height: auto;
    max-height:250px;
    overflow:auto;
    /* width: auto; */
  }

  .arrow {
    border: solid black;
    border-width: 0 3px 3px 0;
    display: inline-block;
    padding: 3px;
  }
  .up {
    transform: rotate(-135deg);
    -webkit-transform: rotate(-135deg);
  } 

  .down {
    transform: rotate(45deg);
    -webkit-transform: rotate(45deg);
  }

  .hr-line{
    margin-top: 15px;
    margin-bottom: 15px;
  }

  ul {
      padding-left: 0;
  }


  .lds-dual-ring.hidden {
        display: none;
    }

    /*Add an overlay to the entire page blocking any further presses to buttons or other elements.*/
    /* .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: rgba(0,0,0,.8);
        z-index: 999;
        opacity: 1;
        transition: all 0.5s;
    } */
    
    /*Spinner Styles*/
    /* .lds-dual-ring {
        display: inline-block;
        width: 80px;
        height: 80px;
    } */
    .lds-dual-ring:after {
        content: " ";
        display: block;
        width: 64px;
        height: 64px;
        margin: 5% auto;
        border-radius: 50%;
        border: 6px solid #fff;
        border-color: #fff transparent #fff transparent;
        animation: lds-dual-ring 1.2s linear infinite;
    }
    @keyframes lds-dual-ring {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

</style>
</head>

<!-- ## This is an empty div used for showning loading gif while processing -->
<div id="loader" class="lds-dual-ring hidden overlay"></div>

<!-- ## this div is by default hidden and only visibale if password protection is set on backup module
## from here user have to  verify password for backup module by entering password. -->
<div id="verifyPasswd" style="display: none;">
    <div class="row service-1" >
          <div class="col-sm-12 ">
                <div class="mb--2"  >
                    <p style="font-size: 1.5rem;">Backup Management is password protected, Verify password to open Backup Management</p>
                </div>
                    <div class="input-group col-sm-9 mb-3" style="display: inline-flex;" >
                        <input type="password" name="verifypassword" id="passWord" class="form-control" required >
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" onclick="verifyPass();" >Verify</button>
                        </div>
                    </div>
                    <!-- ## incorrect password msg will be visible if password if password is wrong -->
                    <div class="col-sm-9 mb-0 mt-0 ml-0" >
                        <span id="failmsg" class="ml-0" style="color: red; display: none;">Incorrect Password<span>
                    </div>
                    <div class="col-sm-9 mb-3" >
                        <!-- ## link click to reset password if forgot -->
                        <a  class="nav nav-link pl-0" href="resetpassword/lockpasswd/forget-password.php">Forget Password?</a>
                    </div>
            </div>
          <div>
    </div>
    <hr class="hr-line" />
</div>

      </div>


<!-- Sub:- Backup Configuration UI Part is Start from here
     Rem:- UI Part all The ui component id  thare propoerty 
     By:- Raj
  ---> 

<!-- ## this is th main div for backup configuration It will display display directly if password 
## is not set on backup module and else after password verification this div will be visible -->
<div id="managementBackup"  >

        <div id="loadinggif_logout_user" class="loading" style="display: none;"></div>
        <!-- ## configure google drive  -->
        <!-- ## google drive can be configured for backup directly on googledrive. after entering gmail for google drive 
            you will be redirected to another page for gmail very and copy code here to complelet configuration -->
        <div class="row service-1 click3">
            <div class="col-sm-8">
              <span id="driveconfigure">Configure Google Drive &nbsp<i class="arrow down"></i></span>
            </div>
            <div id="driveconfiguredetail" class="col-sm-12 columns smalldesc" style="padding-top: 5px;">
              <div id="loadinggif_driveconfigure" class="loading" style="display: none;"></div>

                <div class="row">
                  <div class="col-sm-3 nopadding" style="width: 40.33333333%;">
                    Email ID
                  </div> 
                  <div class="col-sm-3 nopadding" style="width: 55.33333333%;">
                      <!-- ## on blur checkmailid function to verify email is correct or not  -->
                      <input id="emailid" name="emailid" type="text" class="required form-control" value="" onblur="return checkmailid();">

                  </div>
                </div>
                
                <div class="row" style="padding-top: 10px;">
                  <div class="col-sm-3 nopadding" style="width: 40.33333333%;">
                    <span>Google Code</span>
                  </div> 
                  <div class="col-sm-3 nopadding" style="width: 55.33333333%;">
                      <!-- ## on blur check code function will be execute and configure gmail -->
                      <input id="googlecode" name="googlecode" type="text" class="required form-control" onblur="return codecheck();" aria-required="true" />
                  </div>
                </div>
            </div>

            <!-- ## if google drive is already configured then it will div will be visible with remove google drive option -->
            <div id="alreadyconfigured" class="col-sm-12 columns smalldesc" style="padding-top: 5px; display: none">
                  <div class="row">
                    <div class="col-sm-3" style="width: 40.33333333%;">
                      <span>Configured Email</span>
                    </div> 
                      <div class="col-sm-5" style="width: 59.33333333%;" >:
                          <span id="configured_emailid"></span>&nbsp &nbsp<i id="delete_mail" class="fa fa-trash"></i>
                      </div>
                  </div>
            </div>
        </div>
        <hr class="hr-line">
        <!-- ## configure google drive  -->

        <!-- ## backup configuration -->
        <!-- ## configure backup type and desstination from the dropdown in this div -->
        <div class="row service-1 click1">
            <div class="col-sm-8">
              <span id="backupconfigure">Configre Backup &nbsp<i class="arrow down"></i></span>
            </div>
            <!-- select backup destination from the dropdown between google drive and local PC -->
            <div id="backupconfiguredetail" class="col-sm-12 columns smalldesc" style="padding-top: 5px;">
                <div class="row" >
                  <div class="col-sm-3 nopadding" style="width: 40.33333333%;">
                    Backup Destination
                  </div> 
                  <div class="col-sm-3 nopadding" style="width: 40.33333333%;">
                      <select type="select" id="destination" name="destination" class=" " data-toggle="tooltip" title="select backup destination" style="height:30px;width:150px;font-size : 10pt" onclick="destinationSelected()" > 
                          <option value="null">select Destination</option>
                          <option value="drive">Send to Google Drive</option>
                          <option value="local">Download to PC</option>
                      </select>
                  </div>
                </div>
                <!-- ## select for backup type between Auto and manual from here  -->
                <div class="row" style="padding-top: 10px;">
                    <div class="col-sm-3 nopadding" style="width: 40.33333333%;">
                      <span>Backup Type</span>
                    </div> 
                    <div class="col-sm-3">
                        <!-- ## on selecting type from dropown  backuptypeSelected() function of javascript will be executed. -->
                        <select type="select" id="backup_type" name="backup_type" class=" " data-toggle="tooltip" title="select backup type" style="height:30px;width:150px;font-size : 10pt" onclick="backuptypeSelected();"> 
                            <option value="null">select Type</option>
                            <option value="auto">Auto</option>
                            <option value="manual">Manual</option>
                        </select>
                    </div>
                </div>
                
                <!-- ## this autobackup section wil be visible if autobackup addon applied on thep profile  -->
                <!-- ## select schedule banckup time of autobackup  -->
                <div class="row autobackup" id="config_autobackup" style="padding-top: 10px;">
                    <div class="col-sm-3 nopadding" style="width: 40.33333333%;">
                      <span>Auto Backup</span>
                    </div> 
                    <div class="col-sm-6">
                          <div class="row">
                              <div class="col-sm-6">
                                  <div class="form-group">
                                      <select name="hour_0" id="hour_0" class=" form-control hour_minute" style="cursor: pointer;">
                                          <option value="---">Hour</option>
                                          <option value="0">00</option>
                                          <option value="1">01</option>
                                          <option value="2">02</option>
                                          <option value="3">03</option>
                                          <option value="4">04</option>
                                          <option value="5">05</option>
                                          <option value="6">06</option>
                                          <option value="7">07</option>
                                          <option value="8">08</option>
                                          <option value="9">09</option>
                                          <option value="10">10</option>
                                          <option value="11">11</option>
                                          <option value="12">12</option>
                                          <option value="13">13</option>
                                          <option value="14">14</option>
                                          <option value="15">15</option>
                                          <option value="16">16</option>
                                          <option value="17">17</option>
                                          <option value="18">18</option>
                                          <option value="19">19</option>
                                          <option value="20">20</option>
                                          <option value="21">21</option>
                                          <option value="22">22</option>
                                          <option value="23">23</option>
                                      </select>
                                  </div>
                              </div>
                              <div class="col-sm-6">
                                      <select name="minute_0" id="minute_0" class=" form-control hour_minute" style="cursor: pointer;">
                                          <option value="---">Min</option>
                                          <option value="0">00</option>
                                          <option value="1">01</option>
                                          <option value="2">02</option>
                                          <option value="3">03</option>
                                          <option value="4">04</option>
                                          <option value="5">05</option>
                                          <option value="6">06</option>
                                          <option value="7">07</option>
                                          <option value="8">08</option>
                                          <option value="9">09</option>
                                          <option value="10">10</option>
                                          <option value="11">11</option>
                                          <option value="12">12</option>
                                          <option value="13">13</option>
                                          <option value="14">14</option>
                                          <option value="15">15</option>
                                          <option value="16">16</option>
                                          <option value="17">17</option>
                                          <option value="18">18</option>
                                          <option value="19">19</option>
                                          <option value="20">20</option>
                                          <option value="21">21</option>
                                          <option value="22">22</option>
                                          <option value="23">23</option>
                                          <option value="24">24</option>
                                          <option value="25">25</option>
                                          <option value="26">26</option>
                                          <option value="27">27</option>
                                          <option value="28">28</option>
                                          <option value="29">29</option>
                                          <option value="30">30</option>
                                          <option value="31">31</option>
                                          <option value="32">32</option>
                                          <option value="33">33</option>
                                          <option value="34">34</option>
                                          <option value="35">35</option>
                                          <option value="36">36</option>
                                          <option value="37">37</option>
                                          <option value="38">38</option>
                                          <option value="39">39</option>
                                          <option value="40">40</option>
                                          <option value="41">41</option>
                                          <option value="42">42</option>
                                          <option value="43">43</option>
                                          <option value="44">44</option>
                                          <option value="45">45</option>
                                          <option value="46">46</option>
                                          <option value="47">47</option>
                                          <option value="48">48</option>
                                          <option value="49">49</option>
                                          <option value="50">50</option>
                                          <option value="51">51</option>
                                          <option value="52">52</option>
                                          <option value="53">53</option>
                                          <option value="54">54</option>
                                          <option value="55">55</option>
                                          <option value="56">56</option>
                                          <option value="57">57</option>
                                          <option value="58">58</option>
                                          <option value="59">59</option>
                                      </select>
                              </div>
                          </div>
                          <!-- ## save_autobackup_tdl() function will sabve autobackup schedule time -->
                          <button type="button" onclick="save_autobackup_tdl();" class="btn btn-xs btn-primary" >Save</button>
                    </div>
                </div>
                <div class="row autobackup" id="scheduled_autobackup" style="padding-top: 10px;">
                    <div class="col-sm-3 nopadding" style="width: 40.33333333%;">
                      <span>Scheduled</span>
                    </div> 
                    <div class="col-sm-5">
                          <span id="scheduled_time" style="text-align:left;">
                          </span>
                    </div>
                    <div class="col-sm-3 nopadding" style="width: 40.33333333%;">
                      <span>Data Path</span>
                    </div> 
                    <div class="col-sm-7">
                          <div class="row service-1 click2">
                              <div id="datapath" class="col-sm-12 columns smalldesc logdiv" style="padding-top: 5px;">
                                    <!-- ## this empty div will be updated uing javascript function and it contains datapath added in autobackp. -->
                                    <div id="datapath_list">
                                    </div>
                              </div>
                              <div class="col-sm-8">
                                <span id="datapath_button">&nbsp<i class="arrow down"></i></span>
                              </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
        <hr class="hr-line">
        <!-- backup configuration -->

        <!-- last backup detail -->
          <!-- ## this is empty div when its clicked it take backup log detaila from  -->
        <div class="row service-1 click2">
            <div class="col-sm-8">
              <span id="lastbackup">Backup Logs &nbsp<i class="arrow down"></i></span>
            </div>
            <div id="lastbackupDetail" class="col-sm-12 columns smalldesc logdiv" style="padding-top: 5px;">
                  <div id="allbackuplogs">

                  </div>
            </div>
        </div>
        <hr class="hr-line">
        <!-- last backup detail -->

        <!-- restore data backup -->
        <!-- ## This is the div for resoring data,
            data can be restored from local pc by upload TBK file of data 
          and also can be restored from configured google drive . -->
        <div class="row service-1 click4" >
            <div class="col-sm-8">
              <span id="restoredata">Restore Backup &nbsp<i class="arrow down"></i></span>
            </div>
            <div id="restore_data" class="col-sm-12 columns smalldesc" style="padding-top: 5px;">
                <div class="row" >
                  <div class="col-sm-3 nopadding" style="width: 40.33333333%;">
                      Restore data from
                  </div> 
                  <div class="col-sm-3 nopadding" style="width: 55.33333333%;">
                        <!-- ## submitbackup() function will be execute when restore type is selected 
                            ## is google drive is selected tree of backup file in google drive will be displayed
                            ## and if local PC is selected then popup will open to upload files -->
                      <select type="select" id="retoredatabackup" name="retoredatabackup" onclick="testselect()" class=" " data-toggle="tooltip" title="select backup destination" style="height:30px;width:180px;font-size : 10pt" onchange="submitbackup();"> 
                          <option value="null">select </option>
                          <option value="restore_from_drive">Restore from Google Drive</option>
                          <option value="restore_from_local">Upload Backup from PC</option>
                      </select>
                  </div>
                </div>
                <script>
                      // ## this function willl clear option selected in the dropdown and make the dropdown seletion empty.
                      function testselect(){
                          console.log('slection changes')
                          $( "#retoredatabackup option:selected" ).prop("selected", false);
                      }
                </script>
                <!-- ## hidden input for uploading data for restoring -->
                <input hidden type="file" id="files" name="files[]" multiple="" webkitdirectory="" style="display: none;">
                <div id="output" style="display: none;"></div>​
                <div class="row" id="restoreDataTree" style="display: none;">
                    <div class="col-sm-12"><span><b>Backup on Google Drive</b></span></div><br>
                    <div class="container-filetree">
                        <ul class="file-tree" id="file-tree" >
                          <li><a class="context-menu-Data" href="#">backup</a>
                            <!-- ## empty div for restoring data tree, tree genrated will  -->
                            <ul id="google_basicTree">
                            </ul>   
                          </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <hr class="hr-line">

</div>






<script>

//sub:- Started With Java Script Part
//re:- here We are Going to intrect with ajax and PHP for Data the same
//by:- Raj


// ## getting php variable data to javascript variable. 
var admin ='<?php echo $adminsession ?>';
var loginuser='<?php echo $usernamenew ?>';


console.log(admin);
$(document).ready(function(){
  
    // ## ajax call is made to php file for getting current configuration in mysql table database.
    $.post("backup/backup_backend.php",{ 'get_backup_detail': 'get_backup_detail' , admin: admin }, function(result,status,xhr){

      var admindetail = '';

        console.log("result--->"+result);
        // ## ajax will get resulted data in json data, so we have to parse result to use json data.
        var detail = JSON.parse(result);
        var admindetail = detail[0];

        console.log(detail);
        console.log(admindetail.destination);
        // ##using resulted data updating current configuration in UI.
        // ## updating previously selected backup configuration in UI drop down 
        $("#destination option[value="+admindetail.destination+"]").attr('selected', 'selected');        
        $("#backup_type option[value="+admindetail.type+"]").attr('selected', 'selected');

        // ## updating last backup detail in UI table using ip for refrence 
        $("#lastbackup_user").html(admindetail.lastbackup_user);
        $("#lastbackup_type").html(admindetail.lastbackup_type);
        $("#lastbackup_destination").html(admindetail.lastbackup_destination);
        $("#date_time").html(admindetail.lastbackup_date+' '+admindetail.lastbackup_time);

        if (admindetail.mail == '--'){
            $("#configured_emailid").html('--');
        }
        else{
            $("#driveconfiguredetail").css('display', 'none');
            $("#alreadyconfigured").css('display', 'block');
            $("#configured_emailid").html(admindetail.mail);
        }

        // ## from retrived data check if autobackup is applied 
        // ## if autobackup is applied then show autobackup configuration. 
        if(admindetail.autobackup == '1' ){
            if (admindetail.auto_time == '--'){
                // ## scheduleTime function will update the previously scheduled time in UI
                scheduledTime();
            }
            else{
                scheduledTime();
                // ## this ajax call is made to check path added to for autobackup
                // ## this ajax call will return datapath with html which will directly append in UI using ID of div.
                $.ajax({
                    type: "POST",
                    url: 'backup/backup_backend.php',
                    data: ({ 'list_datapath': 'list_datapath', 'admin': admin, 'username' : loginuser }),
                        success: async function(data) {
                        console.log('sucesses list data path');
                        console.log(data);
                        // ## appending data to UI.
                        $('#datapath_list').append(data);
                    },
                    error: function() {
                    }
                });
            }
        }
        else{
            // ## here It will hide autobackup section in UI because autobackup addon is not applied .
            $(".autobackup").css('display', 'none');
        }
    });
    backuplog();

});
console.log(admindetail);

// ## creating empty variable
var admindetail ='';

// Get the modal
var modal = document.getElementById("logout_users");
var rewrite_modal = document.getElementById("rewrite_modal");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];
var delete_mail = document.getElementById("delete_mail")

// ## this is an onclick fuction to expaned and shring backupconfigure section div in UI.
$('#backupconfigure').on('click', function (e) {
    e.preventDefault();
    this.expand = !this.expand;
    $(this).html(this.expand?"Backup Configure &nbsp<i class='arrow up'>":"Backup Configure &nbsp<i class='arrow down'>");
    console.log('clicking');
    $('#backupconfiguredetail').toggleClass('smalldesc bigdesc', 1000,'swing');
});

// ## this is an onclick fuction to expaned and shring driveconfigure section div in UI.
$('#driveconfigure').on('click', function (e) {
    e.preventDefault();
    this.expand = !this.expand;
    $(this).html(this.expand?"Configure Google Drive &nbsp<i class='arrow up'>":"Configure Google Drive &nbsp<i class='arrow down'>");
    console.log('clicking');
    $('#driveconfiguredetail').toggleClass('smalldesc bigdesc', 1000,'swing');
    $("#alreadyconfigured").toggleClass('smalldesc bigdesc', 1000,'swing');
});

// ## this is an onclick fuction to expaned and shring Backup log's section div in UI.
$('#lastbackup').on('click', function (e) {
    e.preventDefault();
    this.expand = !this.expand;
    $(this).html(this.expand?"Backup Logs &nbsp<i class='arrow up'>":"Backup Logs &nbsp<i class='arrow down'>");
    console.log('clicking');
    $('#lastbackupDetail').toggleClass('smalldesc logdivadded', 1000,'swing');
});

// ## this is an onclick fuction to expaned and shring autobackup datapath section div in UI.
$('#datapath_button').on('click', function (e) {
    e.preventDefault();
    this.expand = !this.expand;
    $(this).html(this.expand?"&nbsp<i class='arrow up'>":"&nbsp<i class='arrow down'>");
    console.log('clicking');
    $('#datapath').toggleClass('smalldesc logdivadded', 1000,'swing');
    // $('#lastbackupDetail').toggleClass('logdiv logdivadded', 1000,'swing');
});

// ## this is an onclick fuction to expaned and shring resotore backup section div in UI.
$('#restoredata').on('click', function (e) {
    e.preventDefault();
    this.expand = !this.expand;
    $(this).html(this.expand?"Restore Backup &nbsp<i class='arrow up'>":"Restore Backup &nbsp<i class='arrow down'>");
    console.log('clicking');
    $('#restore_data').toggleClass('smalldesc bigdesc', 1000,'swing');
});

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

// ## on destination selecting in UI destinationSelected() is execte
// ## it will make ajax call and send's selected info  to save configuration in database table.
function destinationSelected()
{
    // ## getiing selected option in drropdown with refernce to its ID
    let selected = document.getElementById("destination").value;
    window.admindestination = selected;
    console.log(selected);
    if (selected != "null") {
        // ## jquery ajax post method is send with selected option to save store in database.
        $.post("backup/backup_backend.php",{ destination_selected: 'destination_selected' ,selected: selected , admin: admin }, function(result,status,xhr){
            console.log(result);
        });
    }
};

// ## submitbackup() function is executed when restoring backup dropdown is selected.
function submitbackup()
{
  // ## getting selected value of restoring dropdown with refrence yo ID
  let selected = document.getElementById("retoredatabackup").value;
    console.log(selected);
    if (selected != "null") {
      // ## if selected option is restore_from_drive then it will create a tree for of data in google drive .
      if (selected == 'restore_from_drive'){
          $("#restoreDataTree").css('display', 'block');            
          $(".file-tree").css('display', 'none');
          $('#loader').removeClass('hidden')

          $.getScript("backup/file-explore.js");
          $.post("backup/backup_backend.php",{ backup_tree_data: 'backup_tree_data' , admin: admin }, function(result,status,xhr){
              console.log(result);
              // google_basicTree
              $('#file-tree').html(result);
              $(".file-tree").filetree();
              $(".file-tree").css('display', 'block');            
              $('#loader').addClass('hidden')


          });
      }
      else{
          // ## local drive is slected in dropdown so it will now
          // ## open upload modal to upload tbk file

          // ## clicking to open upload modal from javascript with refrence to ID
          document.getElementById("files").click();
      }
    }
    else
    {
        // ## selection in dropdown is null.
        console.log('null is seletcted');
    }
}


// ## backuptypeSelected() function is executed every time backuptype dropdown
// ## changes from UI between auto and manual.
function backuptypeSelected(){  
    // ## getting selected value from UI into variable.
    let selected = document.getElementById("backup_type").value;
    window.admintype = selected;
    console.log(selected);
    // ## checking if slection is not null.
    if (selected != "null") {
        // ## jquery ajax call to update new slection to mysql database.
        $.post("backup/backup_backend.php",{ backup_type_selected: 'backup_type_selected' ,selected: selected , admin: admin }, function(result,status,xhr){
            console.log(result);
        });

        // ## changing current proccessing which running according 
        // ## to current select eg, if auto is slected and then it will start a functon backupdownload()
        // ## to run on interval else if manual is selected then 
        if ( selected == 'auto' ){
          // ## clearinterval will stop function running in loop
          clearInterval(downloadinterval);
          clearInterval(checkbackupinterval);
          // ## setting up new interval function.
          downloadinterval = setInterval(function() { backupdownload() }, 5000);
          console.log('changing to '+window.admindestination+' and '+window.admintype);
        }else{
          // ## clear already interval function running in loop
          clearInterval(checkbackupinterval);
          clearInterval(downloadinterval);
          // ## setting up new interval function.
          checkbackupinterval = setInterval(function() { checkbackupfile() }, 5000);
          console.log('changing to '+window.admindestination+' and '+window.admintype);
        }
    }
};


// ## forcefully logout all user's , this function is used in rewrite processes.
function forcefullylogoutallusers(){
    alert('Forcefull closing Users tally is not recommended Please avoid using this option');
    console.log('proceed tally rewrite now')
    $.post("backup/rewriteprocess.php",{ logoutUser: 'logoutUser' , admin:'admin', data:'66666' }, function(result){
        console.log(result);
        console.log("result from server-->"+result);
        modal.style.display = "none";
        rewrite_modal.style.display = "block";
        setuprewrite();
    });
}

// ## setuprewrite() is a used in  rewrite processes. 
function setuprewrite(){
    $.post("backup/rewriteprocess.php",{ setuprewrite: 'startrewrite' , admin:'admin', data:'66666' }, function(result){
	      console.log(result);
        $(".statusMsg").append('<b>Rewrite setup completed<b></br><b>Click Rewritenow button to starttally </b>');
    });
}

// ## url's link for google drive configuration .
// ## this is a O oath linking for autohorization and to get google drive link 
var google_data_url = "https://accounts.google.com/o/oauth2/auth?access_type=offline&client_id=354790962074-7rrlnuanmamgg1i4feed12dpuq871bvd.apps.googleusercontent.com&redirect_uri=urn%3Aietf%3Awg%3Aoauth%3A2.0%3Aoob&response_type=code&scope=https%3A%2F%2Fwww.googleapis.com%2Fauth%2Fdrive&state=2018-09-20+13%3A01%3A26.463641442+%2B0530+IST+m%3D%2B0.0202702722596996162";


// ## check and verify, is correct or not .
function checkmailid(){
      // ## getting mail id from input field.            
      var mails = document.getElementById('emailid').value;
      console.log("checkemailid");
      // ## checking if mail id exist or not.
      if(mails==''){
          return false;
      }
      else
      {
          // ## checking mailid entered is correct
          var filter = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
          if (!filter.test(mails)) {
              alert("Please enter valid email address");
              $("#emailid").val('');
              return false;
          }

          // ## rediredict to O oath link to authenticate Gmail ID.
          console.log("Google Data Sync activated on " +mails+ " mailid ");
          var win = window.open(google_data_url, '_blank');
          if(win){
              console.log('OK');
          }else{
              // ## url link for O oath link to authenticate is blocked.
              alert('Please allow popups for this site');
          }
      }
}

// ## check code of O oath authentication of google drive
function codecheck(){
      // ## getting value entered in input box to variable
      var googlecode = document.getElementById('googlecode').value;
      var mails = document.getElementById('emailid').value;

      var g_type = "Data Management";	
      console.log("googlecode--->>"+googlecode);

      // ## check if google code inputbox and is not empty.
      if (googlecode == '') {
          alert("Enter Code");
          return false;
      }
      else if (mails == '') {
          alert("Enter Email Address");
          return false;
      }
      else{
        $('#loadinggif_driveconfigure').show();
          $("#set_server_config_message").html("Please Wait .....");
            // ## jquery ajax call to configure googledrive using code  on server 
            $.post("backup/backup_backend.php",{ 'save_config': 'save_config' ,google_email_id: mails , googlecode: googlecode , admin: admin }, function(result,status,xhr){

                console.log("result--->"+result);
                // ## if response is recived as sucessful then
                // ## if google drive is configured successful then updated gmail address in sugam database 
                if ("success" == "success") {
                  // ## ajax call to store gmail address in database.
                  $.post("backup/backup_backend.php",{ 'save_email_database': 'save_email_database' ,google_email_id: mails , admin: admin }, function(result,status,xhr){
                      console.log(result);
                  });
                  alert("Sucessfull!! drive has configured");
                  // ## changes in 	UI according to gmail added
                  $('#loadinggif_driveconfigure').hide();
                  $("#driveconfiguredetail").css('display', 'none');
                  $("#alreadyconfigured").css('display', 'block');
                  $("#configured_emailid").html(mails);
                  $("#alreadyconfigured").toggleClass('smalldesc bigdesc', 1000,'swing');
                }
                else{
                  // $("#set_server_config_message").html("Configuration Not Saved, Please Try Again");
                      //destination.reload();
                  alert("Failed!! to configure drive");	
                  $('#loadinggif_driveconfigure').hide();

                }

            });
      }
}


// ## In is on click function to delete configured mail address.
delete_mail.onclick = function() {
    console.log('deleteing mail');
    console.log('deleting email compelete 1')
    // ## confirmation to delete gmail address
    var u = confirm("Are you sure want to delete this configured Google Drive!!");
    if (u) {
      console.log('deleting email compelete 2')
        // ## ajax call to backup_backend.php to deleted mail, admin name is passed with ajax call.
        $.post("backup/backup_backend.php",{ 'delete_mail': 'delete_mail' , admin: admin }, function(result,status,xhr){
            console.log('deleting email compelete 3')
            // ## change in UI reguarding delete mail using javacript css method. 
            $("#alreadyconfigured").css('display', 'none');
            $("#driveconfiguredetail").css('display', 'block');
            $("#configured_emailid").html('--');
            $("#alreadyconfigured").toggleClass('smalldesc bigdesc', 1000,'swing');
            alert(result);
        });
    }
}

// ## restoring data from google drive on sugam server 
function upload_from_google_to_toc_data(id)
{
    // ## id is passed with while execute function.
    var path = [];
    console.log('function is running'+id);

    console.log('children path'+$(this).children("a").eq(0).text());
    // $('#'+newid).parentsUntil("ul.jstree-container-ul").each(function(){
    //     console.log("this condition is running")
    //     if($(this).children("a").eq(0).text() != '')
    // 	    path.push($(this).children("a").eq(0).text());
  	// });

    // ## run  foreach function on tree html to get the path of data clicked accoring to tree.
    $(document.getElementById(id)).parentsUntil("ul.jstree-container-ul").each(function(){
        console.log("this condition is running")
        if($(this).children("a").eq(0).text() != '')
    	    path.push($(this).children("a").eq(0).text());
  	});

    console.log(path);
    // ## getting id of clicked data. 
    var selected_path = window.clickedId;
    console.log(selected_path +" selected_path");
	  var newArray = path.filter(function(v){return v!==''});
    // ## actualpath varibale hold the path of data on server from Data folder
  	var actualPath = newArray.reverse().join("/");
  	console.log("upload from google to toc--->"+actualPath);

    $('#loader').removeClass('hidden')

    if (actualPath != '') {
            // ## confirm restoring of data from User in UI
            var u = confirm("Are You Sure To Want to restore "+actualPath);
            if (u) {
                console.log("company path to upload-->"+actualPath);
                $('#upload_folder_management_loadinggif').show();
                // ## admin name, and data path on google drive is sent with ajax call to backend script for restoring data. 
                $.post("backup/backup_backend.php",{ admin: admin, single_upload_only:'single_upload_only' , company_name:actualPath }, function(result,status,xhr){
                    console.log("result from serverip --->"+result);
                    $('#loader').addClass('hidden');
                    // ## show msg of success and faliure  accoring to response of ajax call. 
                    if ( result == 'success'){
                        // ## show confirmation modal in UI with success message.
                        $.confirm({
                          title: 'Sugam Cloud',
                          content: 'Tally Backup Data is now available in c:\\restore.<br><br>Please follow the restore process in Tally.',
                          buttons: {
                              OK: function () {
                              },
                          }
                        });
                    }
                    else if( result == 'fail' ){
                      alert('restoring data failed');
                    }
                    else{
                      alert(result);
                    }
                   
                });
            }
            else{
                // $(".tooltip").tooltip("hide");
                return false;
            }
    }
}

// ## this function will be executed when we delete data backup file in googledrive from UI 
// ## ID of seleted data is passed with executing function.
function delete_data_from_drive(id){
    // ## empty path variale
    var path = [];
    console.log('function is running'+id);;
    
    // ## foreach function on tree html to get the path of data clicked accoring to tree.
    $(document.getElementById(id)).parentsUntil("ul.jstree-container-ul").each(function(){
        console.log("this condition is running")
        if($(this).children("a").eq(0).text() != '')
    	    path.push($(this).children("a").eq(0).text());
  	});
    console.log(path);
    // ## getting id of clicked data. 
    var selected_path = window.clickedId;
    console.log(selected_path +" selected_path");
	  var newArray = path.filter(function(v){return v!==''});
    // ## actualpath varibale hold the path of data on drive from backup folder
  	var actualPath = newArray.reverse().join("/");
  	console.log("upload from google to toc--->"+actualPath);

    
    if (actualPath != '') {
        var u = confirm("Are You Sure To Want to Delete "+actualPath+" from Google Drive");
        if (u) {
            console.log("company path to upload-->"+actualPath);
            $('#upload_folder_management_loadinggif').show();
            // ## admin name, and data path on google drive is sent with ajax call to backend script for deleting data. 
            $.post("backup/backup_backend.php",{ admin: admin, trash_this:'trash_this' , company_name:actualPath }, function(result,status,xhr){
                  // ## show msg of success and faliure  accoring to response of ajax call. 
                  console.log("result from serverip --->"+result);
                  if ( result == 'sucess'){
                    alert('Trashing Backup sucessfull!!');
                  }
                  else if( result == 'fail' ){
                    alert('Trashing Backup failed');
                  }
                  else{
                    alert(result);
                  }
            });
        }
        else{
            return false;
        }
    }
}

    // ## This will display context menu in the UI,
    // ## context menu will apply to all html element with  .context-menu-one class.
    jQuery(function($) {
        $.contextMenu({
            selector: '.context-menu-one', 
            callback: function(key, options) {
                if (key == "restore"){
                  window.m = key;
                  console.log('window.m'+window.m) ;
                  console.log(window.myvar) ;
                  console.log("clicked id"+window.clickedId)
                  upload_from_google_to_toc_data(window.clickedId);
                }
                else if (key == "trash"){
                  window.m = key;
                  console.log('window.m'+window.m) ;
                  console.log("clicked id"+window.clickedId)
                  delete_data_from_drive(window.clickedId);
                  //console.log('trash this');
                }
            },
            items: {
                "restore": {name: "Restore", icon: "fa-refresh"},
                "trash": {name: "Move to trash", icon: "delete"}
                // "Download": {name: "Download", class:"fa fa-upload"},
                // "Delete": {name: "Delete", icon: ""}
            }
        });

        $('.context-menu-one').on('click', function(e){
            console.log('clicked', this);
            
        })    
    });

    function myFunction()
    {
        if (window.m === "Upload") { 
            alert('uploading ');
        }  
    }

    // ## this function is usefull to find the actual data path in data tree.
    // ## path of data is obtained using the ID in html as refrence.
    function toc_data(id){
        window.clickedId = id
        var path = [];
        // ## foreach function to on html of tree with refrence to class ul.jstree-container-ul
        $('#'+id).parentsUntil("ul.jstree-container-ul").each(function(){
            if($(this).children("a").eq(0).text() != '');
            path.push($(this).children("a").eq(0).text());
        });

        var newArray = path.filter(function(v){return v!==''});
        window.myvar = newArray.reverse().join("/");
        console.log("data path -->"+window.clickedId);
    }

    // ## backuplog function will update log's from database in UI.
    // ## ajax call is made to backup_backend.php script to get backup logs 
    // ## html data of backup logs will get in response of ajax call.
    function backuplog(){
        $.ajax({
            type: "POST",
            url: 'backup/backup_backend.php',
            data: ({ 'backuplog': 'backuplog', 'admin': admin, 'username' : loginuser }),
            success: async function(data) {
                console.log('sucesses');
                console.log(data);
                // ## appending log's html reponse to UI with refrence to div ID
                $('#lastbackupDetail').append(data);
            },
            error: function() {
            }
        });
    }

  // ## this function is executed when we save a schedule time for autobackup
  // ## hour and minute sleted in UI is fetched with refrence to their fixed ID's 
  function save_autobackup_tdl()
  {
      var autohours = $("#hour_0 option:selected").text();
      var autominutes = $("#minute_0 option:selected").text();
      // ## got hout and minute selected in UI

      // ## checking if anyone of then is not equal to default value 
      if (autohours == 'Hour' || autominutes == 'Minute') {
          alert("Please Select Hour & Minute To Proceed");
          return false;
      }


      schedule=autohours+':'+autominutes;

      // ## confirmation form UI .
      var conf = confirm("Are You Sure To Add This Autobackup Schedule !! ");
      if (conf) {
          // ## ajax call with time selected to schedule in cron and store in data base.
          $.post("backup/backup_backend.php",{ save_autobackup_time: "save_autobackup_time" , admin: admin, autohours : autohours ,autominutes : autominutes }, function(result,status,xhr){
            console.log("result-->"+result);
                if (result == "Success") {
                    scheduledTime();
                    alert("Schedule Backup Set Successfully.");
                }
                else{
                    scheduledTime()
                    alert("Please Try Again.");
                }
          });
      }
      else{
          return false;
      }
  }

  // ## delete previously schedule time for autobackup, time which is removed from UI is passed in function.
  function delete_autotime(clicked_id)
  {
      var conf = confirm("Are You Sure To Remove Autobackup Schedule !! ");
      var remove_time=clicked_id.trim().replaceAll('|', '');

      if (conf) {
            // ## ajax call with time want's to remove from database is  sent to backend script, delete_autobackup_time parameter is also passed to remove. 
            $.post("backup/backup_backend.php",{ delete_autobackup_time: "delete_autobackup_time" , admin: admin, remove_time: remove_time }, function(result,status,xhr){
                console.log("result-->"+result);
                if (result == "Success") {
                    scheduledTime();
                    alert("Schedule Removed Successfully.");
                }
                else{
                    scheduledTime();
                    alert("Please Try Again.");
                }
            });
      }
      else{
          return false;
      }
  }

  // ## funcction will get the deatil of prefivously schedule autobackup detail from database.
  // ## and update in UI modal IT also manage to the option of only 3 scheduling for one profile
  // ## this will hide option to schedule autobackup when this schedule and auto make the  option visible if any one of the 
  // ## schedule time is deleted.
  function scheduledTime()
  {
      // ## ajax call to get deteial from Database of previously scheduled of autobackup.
      $.ajax({
          type: "POST",
          url: 'backup/backup_backend.php',
          data: ({ 'scheduledTime': 'scheduledTime', 'admin': admin }),
          success: async function(data) {
              // ## json data will be json data
              $("#scheduled_time").html(data);
              console.log(data);

              var detail = JSON.parse(data);
              var abDetail = detail[0];

              console.log(detail);
              console.log(abDetail.schedulings);
              // ## appending schedule in UI
              if (abDetail.schedulings){
                  $("#scheduled_autobackup").css('display', 'block');
                  $("#scheduled_time").html(abDetail.schedulings);
              }else{
                  $("#scheduled_autobackup").css('display', 'none');
                  $("#scheduled_time").html('<br>');
              }

              console.log(abDetail.count);
              // ## checking if scheule count is more than three.
              if ( abDetail.count <= 2 ){
                  // console.log('excedd');
                  $("#config_autobackup").css('display', 'block');
              }else{
                  // ## hiding option to add schedule. 
                  console.log('sads sfsd   excedd');
                  $("#config_autobackup").css('display', 'none');
              }
          },
          error: function() {
              console.log('error');
          }
      });
  }

</script>
        <!-- file tree js -->
        <link href="https://www.jqueryscript.net/css/jquerysctipttop.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="backup/file-explore.css" rel="stylesheet" type="text/css">

        <!-- file tree javascript -->
        <script src="backup/file-explore.js"></script>
        <!-- file tree javascript -->



<script>

    // ## Detect when the value of the files input changes.
    document.getElementById('files').onchange = function(e) {
    // ## Retrieve the file list from the input element
    uploadRestore(e.target.files);
    // ## Outputs file names to div id "output"
    output.innerText = "";
    for (var i in e.target.files)
        output.innerText = output.innerText + e.target.files[i].webkitRelativePath + "\n";
    console.log(output.innerText);
}


function uploadRestore(files) {
    $('#loader').removeClass('hidden');
    // ## Create a new HTTP requests, Form data item (data we will send to the server) and an empty string for the file paths.
    xhr = new XMLHttpRequest();
    data = new FormData();
    paths = "";

    // ## Set how to handle the response text from the server
    xhr.onreadystatechange = function(ev) {
        if (this.readyState == 4 && this.status == 200) {
            // cFunction(this);
            console.log(xhr.responseText);
              $.confirm({
                title: 'Sugam Cloud',
                content: 'Tally Backup Data is now available in c:\\restore.<br><br>Please follow the restore process in Tally.',
                buttons: {
                    OK: function () {
                      console.log('okay')
                    },
                }
              });
            $('#loader').addClass('hidden');
        }
    };
    
    // Loop through the file list
    for (var i in files) {
        paths += files[i].webkitRelativePath + "###";
        // Append current file to our FormData with the index of i
        data.append(i, files[i]);

    };
    data.append('paths', paths);
    data.append('admin', admin);
    data.append('upload_backup', 'upload_backup');
    // data.append('folderpath', window.myvar);  
    // Open and send HHTP requests to upload.php
    xhr.open('POST', "/sugam/backup/backup_upload.php", true);
    xhr.send(this.data);

}

// ## this verifyPass function will be executed if set password potection to lock backup modal 
// ## and while entring password and clicking on verify button.
// ## entered password is sent with ajax call to verify if its correct
function verifyPass(){
    // ## get password enter in UI with refrence to ID
    var checkpss = $('#passWord').val();
       $.ajax({
          type: "POST",
          url: 'backup/backup_backend.php',
          data: ({ 'verifyPass': 'verifyPass', 'admin': admin, 'checkpss' : checkpss }),
          success: async function(data) {
              console.log('sucesses');
              console.log(data);
              var data1 = data.trim();
              // ## if response for verification is success then
              // ## it will hide verification div and diplay backup management div
              if ( data1 == 'Success' ){
                  $('#verifyPasswd').css('display','none')
                  $('#managementBackup').css('display','block')
              }else{
                console.log(data);
                $('#failmsg').css('display', 'block');
              }
          },
          error: function() {
          }
      });
}
</script>
<script>
    // ## show video on in iframe 
    $(document).ready(function() {
        var url = $("#framecall1").attr('src');
        $("#sugamvideosframe1").on('hide.bs.modal', function() {
          $('.close').click();
        });
        $("#sugamvideosframe1").on('show.bs.modal', function() {
            $('.close').click();
        });
    });
</script>
<script>
    function loadnewpage(newsrc){
            $('#sugamvideosframe1').modal('show');
            let youtubestart = "https://www.youtube.com/embed/";
            let idvideo = newsrc;
            let videofuture = "?feature=player_detailpage&autoplay=1&enablejsapi=1&controls=1&showinfo=1&rel=0&allow=autoplay";
            let result1 = youtubestart.concat(idvideo);
            let plylink = result1.concat(videofuture);
            console.log(plylink );
            document.getElementById('framecall1').setAttribute('src', plylink);
    }
</script>
</html>

<?php
    // ## getting admin information from mysql database table
    $sql5 = "SELECT * FROM admininformation where admin='$adminsession'";
    $result5 = $conn->query($sql5);
    $row5 = $result5->fetch_assoc();
    $setPswd= $row5["setPswd"];
    $pawdAdmin= $row5["pawdAdmin"];
    // checking if password is set and if password verified recently for session.
    if( $setPswd == '1' && $pswdBackup == '1' && $passwordverified != 'True' ){
        echo "<script> $('#verifyPasswd').css('display','block')</script>";
        echo "<script> $('#managementBackup').css('display','none')</script>";
    }
?>