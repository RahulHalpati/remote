#!/bin/bash

#sub:- This Script use to do for Backup - Google drive Auto Backup 
#re:- Entier file is running based on parameter and command $1 $2 $2 is pass all the parameter Backup_Backend.php
#By:- Raj

admin="$2"
echo "backup running in backuoconfigure">>/home/$admin/backupconfig.txt
echo '' > /home/"$admin"/$admin-restore
echo '' > /home/"$admin"/$admin-trash

## download_backup execute ecery time when backup is taken from tally
## xpra execute this script when backup is one from tally
if [ "$1" == "download_backup" ]; then
    destination="$3"
    type="$4"
    username="$5"

    cd /home/"$admin"/.$admin"_prefix"/drive_c/backup/
    dirname=`date +%d_%m_%Y_%H_%M`
    ## crete folder with cutrrent datate and time
    mkdir /home/"$admin"/.$admin"_prefix"/drive_c/backup/"$dirname"
    cd /home/"$admin"/.$admin"_prefix"/drive_c/backup/ && mv * "$dirname" 

    ## if backup destination is set to local then create zip of backup file
    if [ "$destination" == "local" ]; then
        cd /home/"$admin"/.$admin"_prefix"/drive_c/backup/
        zip -r "$dirname".zip "$dirname"
    fi


    #sub:- Check the Para and Based on the Action is Happaning  
    #re:- This is Related with Backup Type where we have to do the backup
    #By:- Raj

    ## if backup destination is set to drive then and type is auto then pull all file of backup data to google drive. 
    if [ "$destination" == "drive" ]; then
        if [ $type == 'auto' ]; then
            ## loop on all backup file and push one by one
            IFS=$'\n' eval 'for i in `find  "/home/"$admin"/.$admin"_prefix"/drive_c/backup/" -type f -name "*"`;do sudo /opt/tigdrive/bin/drive push --no-prompt --fix-clashes --ignore-conflict -upload-chunk-size 1024  $i;done'
            if [ -z "$admin" ]
            then
                echo "\$my_var is NULL"
            else
                ## remove all file from backup folder once data is pushed.
                sudo rm -rf /home/"$admin"/.$admin"_prefix"/drive_c/backup/*
            fi
            ## add log in database.
            mysql --user=xpra --password=xpra xpra << EOF
INSERT INTO backuplogs (\`date\`, \`time\`, \`admin\`,\`user\`, \`action\`, \`type\`, \`destination\`) VALUES ( CURDATE() , CURTIME(), "$admin","$username","sent","auto","drive");
EOF
        fi

    fi

## download data on button click manually
elif [ "$1" == "download_now" ]; then
    destination="$3"
    username="$4"
    # type="$4"
    if [ "$destination" == "drive" ]; then
        echo "rune time">>/home/$admin/runcheckscc.txt
        ## loop on all backup file and push one by one
        IFS=$'\n' eval 'for i in `find  "/home/"$admin"/.$admin"_prefix"/drive_c/backup/" -type f -name "*"`;do sudo /opt/tigdrive/bin/drive push --no-prompt --fix-clashes --ignore-conflict -upload-chunk-size 1024  $i;done'
        if [ -z "$admin" ]
        then
                echo "\$my_var is NULL"
        else
                sudo rm -rf /home/"$admin"/.$admin"_prefix"/drive_c/backup/*
        fi
        ## add log in database.
        mysql --user=xpra --password=xpra xpra << EOF
INSERT INTO backuplogs (\`date\`, \`time\`, \`admin\`,\`user\`, \`action\`, \`type\`, \`destination\`) VALUES ( CURDATE() , CURTIME(), "$admin","$username","sent","manual","drive");
EOF
    fi


    #sub:- Google drive Setup  
    #re:- Backupmodel3.php with Related with This
    #By:- Raj

## configure google drive on server.
elif [ "$1" == "configdrive" ]; then
    log=/var/log/backuplog
    mailis="$3"
    code="$4"
    echo "saving configuration"
    if [ ! -d /home/"$admin"/"$admin" ]; then
        mkdir /home/"$admin"/"$admin"
        chmod 0777 /home/"$admin"/"$admin"
    fi
    ## after configuration status will be update to this file.
    if [ -f /home/"$admin"/"$admin"/.save_status ]; then
        echo '' >/home/"$admin"/"$admin"/.save_status
    fi

    if [ -n "$mailis" ]; then
        echo "`date` Registering Google drive for data Transfer for $admin"
        ## configure google drive /home/$admin/.$admin"_prefix"/drive_c/ to this path.
        cd /home/$admin/.$admin"_prefix"/drive_c/;su "$admin" -c "(echo $code) | /opt/tigdrive/bin/drive init"
        if [ "$?" -eq 0 ];
        then
            ## google drive is configured successfull.
            echo "`date`:Drive Registration Successfull-$admin" >>$log
            echo "success" >/home/"$admin"/$admin-drive
            sudo cp -r /home/$admin/.$admin"_prefix"/drive_c/.gd /home/$adminname/.$adminname"_material"
        else
            ## google drive is configured failed.
            echo "`date`:Drive Configuration Failed-$admin" >>$log
            echo "fail" >/home/"$admin"/$admin-drive
        fi
    else
        echo "mail not passed"
    fi
## restore data from google drive to restore folder on server.
elif [ $1 == "restoredata" ]; then
    data="$3"
    echo '' > /home/"$admin"/$admin-restore

    ## create restore folder if not exist.
    if [ ! -e /home/"$admin"/$admin/drive_c/restore ]; then
        sudo mkdir -p /home/"$admin"/.$admin/drive_c/restore
        sudo chmod 0777 /home/"$admin"/.$admin/drive_c/restore
        sudo chown -R "$admin". /home/"$admin"/.$admin"_prefix"/drive_c/restore
    fi
    if [ -z "$admin" ];
    then
        echo "\$my_var is NULL"
    else
        ## empty restore folder 
        sudo rm -rf /home/"$admin"/.$admin"_prefix"/drive_c/restore/*
    fi

    ## push empty restore folder to google drive  
    cd /home/"$admin"/.$admin"_prefix"/drive_c/ && sudo /opt/tigdrive/bin/drive push -no-prompt restore

    cd /home/"$admin"/.$admin"_prefix"/drive_c/
    ## empty restore folder of google drive  
    sudo /opt/tigdrive/bin/drive trash -quiet restore/*
    ## copy restore data from backup folder to restore folder of google drive.
    sudo /opt/tigdrive/bin/drive copy -recursive "$data" restore/
    ## pull restore folder from google drive to server.
    cd /home/"$admin"/.$admin"_prefix"/drive_c/;sudo /opt/tigdrive/bin/drive pull -no-prompt restore/;

    if [ "$?" -eq 0 ]; then
        ## pull of restore folder is successfull. update status to file
        echo "success" > /home/"$admin"/$admin-restore
    else
        ## pull of restore folder is successfull. update status to file
        echo "fail" > /home/"$admin"/$admin-restore
    fi

    sudo chmod -R 0777 /home/"$admin"/.$admin/drive_c/restore/*
    sudo chown -R $admin. /home/"$admin"/.$admin/drive_c/restore/*
## trash_data data of google drive 
elif [ $1 == "trash_data" ]; then
    data="$3"
    echo '' > /home/"$admin"/$admin-trash

    if [ -n $data ]; then
        cd /home/"$admin"/.$admin"_prefix"/drive_c/
        ## trashing data to google drive.
        sudo /opt/tigdrive/bin/drive trash -quiet "$data";
        if [ "$?" -eq 0 ]; then
            echo "secess" > /home/"$admin"/$admin-trash
        else
            echo "fail" > /home/"$admin"/$admin-trash
        fi
    fi
// ## removing configured google drive from server 
elif [ $1 == "delete_mail" ]; then
    ## remove .gd file
    cd /home/"$admin"/.$admin"_prefix"/drive_c/ && sudo rm -rf .gd
    if [ "$?" -eq 0 ]; then
        echo "seccess" > /home/"$admin"/$admin-trash
    else
        echo "fail" > /home/"$admin"/$admin-trash
    fi

elif [ $1 == "autobackup" ]; then
    export admin=$2
    export log=/var/log/autobackup.log

    if [ ! -f /var/log/autobackup.log ]; then
        touch /var/log/autobackup.log
        chmod 0777 /var/log/autobackup.log
    fi
    sudo cat /var/spool/cron/crontabs/$admin > /home/$admin/crontab
    ## add autobackup schedule to crontab  
    if [ "$3" == "set_schedule" ]; then
        hr="$4"
        min="$5"
        ## copy content of admin's crontab to a file
        sudo cat /var/spool/cron/crontabs/$admin > /home/$admin/crontab
        echo "`date` $admin Configuring autobackup for $admin at $hr:$min" >>$log
        sudo sed -i 's:#.*$::g' /home/$admin/crontab
        sudo sed  -i -r '/^\s*$/d' /home/$admin/crontab
        echo "`date` adding $min $hr entry in crontab for $admin" >> $log
        ## add new cron job to file 
        echo "$min  $hr  *  *  *  /opt/script/script/autobackuptdl.sh $admin" >> /home/$admin/crontab
        ## add file to crontab of the user.
        sudo crontab -u $admin /home/$admin/crontab
        sudo service cron restart

    ## remove cron scheduleing from admin's crontab
    elif [ "$3" == "remove_schedule" ]; then
        time="$4"
        hour=`echo $time | xargs | tr ':' ' ' | awk '{print $1}'`
        min=`echo $time | xargs | tr ':' ' ' | awk '{print $2}'`
        ## copy content of admin's crontab to a file
        sudo cat /var/spool/cron/crontabs/$admin > /home/$admin/crontab
        echo "`date` Removing schedule autobackup for $admin " >>$log
        ## remove cron job from file 
        sed -i "/$min $hour\b/d" /home/$admin/crontab
        ## add file to crontab of the user.
        sudo crontab -u $admin /home/$admin/crontab
        sudo service cron restart
    ## apply autobackup tdl to profile for autobackup backup
    elif [ "$3" == "applytdl" ]; then
        echo "apply tdl according to tally version" >>$log
        ## check autobackup TDL is applied or not  
        ## if change tally version then change the tdl according to that version
        if [ -e /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime/tally.exe ]; then

            if [ -e /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup ]; then
                    if [ -z "$admin" ]
                    then
                        echo "\$my_var is NULL"
                    else
                        ## remove previous TallyPrime_autobackup 
                        rm -rf /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup
                    fi

            fi

            ## create new TallyPrime_autobackup
            sudo -u $admin cp -r /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime /home/"$admin"/."$admin"/drive_c/Program\ Files/TallyPrime_autobackup

            ## copy Auto.bat to TallyPrime_autobackup folder
            if [ -f /opt/script/script/autobackup/Auto.bat ]; then
                echo "`date` -- copying Auto.bat file into TallyPrime of $admin" >>$log
                cp /opt/script/script/autobackup/Auto.bat /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/
                chmod 0777 /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/Auto.bat
                chown $admin. /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/Auto.bat
            else
                echo "`date` xxxx Auto.bat file not exists for $tallyver tally version" >>$log
            fi

            echo "`date` AutoBackup TDL is applied in $user profile" >>$log
            ## copy auto.xml to TallyPrime_autobackup folder
            if [ -f /opt/script/script/autobackup/Auto.xml ]; then
                echo "`date` -- copying Auto.xml file into TallyPrime of $admin" >>$log
                cp /opt/script/script/autobackup/Auto.xml /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/Auto.xml
                chmod 0777 /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/Auto.xml
                chown $admin. /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/Auto.xml
            else
                echo "`date` xxxx Auto.xml file not exists for $tallyver tally version" >>$log
            fi

            ## create datapath.xml file and data to datapath.xml for autobackup
            sudo -u $admin /var/www/html/sugam/backup/backupconfig.sh autobackup "$admin" datapath
            ## apply autobackup tdl to tally.ini of TallyPrime_autobackup 
            sudo /var/www/html/sugam/backup/backupconfig.sh autobackup "$admin" addtdl

            if [ -z "$admin" ]
                    then
                        echo "\$my_var is NULL"
                    else
                        ## remove  lic file of from TallyPrime_autobackup 
                        sudo rm -rf /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/*.lic
            fi

            ## create new link of licence file to TallyPrime_autobackup
            IFS=$'\n' eval 'for i in \
            `cd  /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime/ && find  -type f -iname "*.lic" `; \
            do  
                licfile=`echo $i | cut -d '/' -f2-` ; \
                sudo -u $admin ln -s /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime/$licfile  /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/$licfile ; \
            done'
        else
            echo "`date` xxxx tally is not installed in $admin" >>$log
        fi
    elif [ "$3" == "start_backup" ]; then
        echo "apply tdl according to tally version" >>$log
        
        sudo php /var/www/html/admin/link_autobackup_data.php "$admin"
        ## check autobackup TDL is applied or not 
        ## if change tally version then change the tdl according to that version
        if [ -e /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime/tally.exe ]; then

            ## remove previous TallyPrime_autobackup 
            rm -rf /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup
            # /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup
            if [ ! -e /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/tally.exe ]; then
                if [ -z "$admin" ]
                    then
                        echo "\$my_var is NULL"
                    else
                        ## remove previous TallyPrime_autobackup 
                        sudo -u $admin cp -r /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime /home/"$admin"/."$admin"/drive_c/Program\ Files/TallyPrime_autobackup
                fi
                
            fi

            ## create new TallyPrime_autobackup
            sudo -u $admin cp -r /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime /home/"$admin"/."$admin"/drive_c/Program\ Files/TallyPrime_autobackup

            ## copy Auto.bat to TallyPrime_autobackup folder
            if [ ! -f /home/"$admin"/."$admin"/drive_c/'Program Files'/TallyPrime_autobackup/Auto.bat ]; then
                echo "`date` -- copying Auto.bat file into TallyPrime of $admin" >>$log
                cp /opt/script/script/autobackup/Auto.bat /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/
                chmod 0777 /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/Auto.bat
                chown $admin. /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/Auto.bat
            else
                echo "`date` xxxx Auto.bat file not exists for $tallyver tally version" >>$log
            fi

            echo "`date` AutoBackup TDL is applied in $user profile" >>$log
            ## copy auto.xml to TallyPrime_autobackup folder
            if [ ! -e /home/"$admin"/."$admin"/drive_c/'Program Files'/TallyPrime_autobackup/Auto.xml ]; then
                echo "`date` -- copying Auto.xml file into TallyPrime of $admin" >>$log
                cp /opt/script/script/autobackup/Auto.xml /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/Auto.xml
                chmod 0777 /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/Auto.xml
                chown $admin. /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/Auto.xml
            else
                echo "`date` xxxx Auto.xml file not exists for $tallyver tally version" >>$log
            fi

            ## create datapath.xml file and data to datapath.xml for autobackup
            sudo -u $admin /var/www/html/sugam/backup/backupconfig.sh autobackup "$admin" datapath
            sudo /var/www/html/sugam/backup/backupconfig.sh autobackup "$admin" addtdl

            ## remove  lic file of from TallyPrime_autobackup 
            sudo rm -rf /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/*.lic

            ## create new link of licence file to TallyPrime_autobackup
            IFS=$'\n' eval 'for i in \
            `cd  /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup && find  -xtype l `; \
            do  
                linkfile=`echo $i | cut -d '/' -f2-` ; \
                if [ -n "$linkfile" ]; then \
                rm -rf  /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/$linkfile; fi ; \
            done'
            
            ## create new link of licence file to TallyPrime_autobackup
            IFS=$'\n' eval 'for i in \
            `cd  /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime/ && find  -type f -iname "*.lic" `; \
            do  
                licfile=`echo $i | cut -d '/' -f2-` ; \
                sudo -u $admin ln -s /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime/$licfile  /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/$licfile ; \
            done'

        else
            echo "`date` xxxx tally is not installed in $admin" >>$log
        fi
    elif [ $3 == "addtdl" ]; then
        
        sed -i '/TDL=c\b/d' /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/tally.ini
        ## apply autobackup tdl to tally.ini of TallyPrime_autobackup
        IFS=$'\n' eval 'for i in \
         `cd  "/home/$admin/."$admin"_prefix/drive_c/tdl/" && find  -type f -iname "*backup*" `; \
         do  
            tdlname=`echo $i | cut -d '/' -f2-` ; \
            sudo sed -i "/User TDL=Yes/a TDL=c:\\\\tdl\\\\$tdlname" /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/tally.ini ; \
         done'

    elif [ "$3" == "reset" ]; then
            echo "`date` === resetting AutoBackup entries" >>$log
            chk=`grep -i "autobackuptdl.sh" /temp/$admin/$admin | grep -w "$admin" | grep -v "grep"`
            if [ -n "$chk" ]; then
                echo "`date` --- removing $admin autobackup entries from schedule" >>$log
                echo "`date` --- removing $admin autobackup entries from schedule"
                sed -i '/autobackuptdl.sh '$admin'\b/d' /temp/$admin/$admin
                sudo crontab -u $admin /temp/$admin/$admin
            fi
    elif [ "$3" == "datapath" ]; then
            filepath="/home/$admin/datapath.txt"
            # datapath='/home/$admin/.$admin/drive_c/datapath.xml'
            rm -rf /home/"$admin"/."$admin"_prefix/drive_c/'Program Files'/TallyPrime_autobackup/DataPath.xml
            echo "<ENVELOPE>" > $filepath
            # IFS=$'\n' eval "for i in `cd /home/$admin/.$admin/drive_c/ && find Data -type f -name '*Company.900*' | sed -r 's|/[^/]+$||' | rev | cut -d/ -f2- | rev | sort  | uniq `;do echo "$i"; done"
            for i in `cd /home/"$admin"/."$admin"_prefix/drive_c/data_autobackup/ && find -L Data -type f -name '*Company.900*' | sed -r 's|/[^/]+$||' | rev | cut -d/ -f2- | rev | sort  | uniq | tr '/' '\'`;
            do 
                echo " <MULTIDATAPATH>" >> $filepath 
                echo "  <DATAPATH>c:\\data_autobackup\\$i</DATAPATH>" >> $filepath
                echo " </MULTIDATAPATH>" >> $filepath
            done
            echo "</ENVELOPE>" >> $filepath
            iconv -f utf-8 -t utf-16 $filepath > /home/"$admin"/."$admin"_prefix/drive_c/'Program Files'/TallyPrime_autobackup/DataPath.xml
            chmod 0777 /home/"$admin"/."$admin"_prefix/drive_c/'Program Files'/TallyPrime_autobackup/DataPath.xml
    elif [ "$3" == "list_datapath" ]; then
            /var/www/html/sugam/backup/backupconfig.sh autobackup $admin datapath;
            iconv -f utf-16 -t utf-8 /home/"$admin"/."$admin"_prefix/drive_c/Program\ Files/TallyPrime_autobackup/DataPath.xml > /home/"$admin"/DataPath.check;
            grep -oP '(?<=DATAPATH).*?(?=/DATAPATH)' /home/$admin/DataPath.check  | tr -d '<>' | sed '/^$/d' | sed ':a;N;$!ba;s/\n/<br>/g' > /home/"$admin"/"$admin"_datapath;

    elif [ "$3" == "list_ab_company" ]; then
            # admin=$2
            company=$(mysql -N -u xpra -pxpra -D xpra -e "select ab_company from manage_backup where admin='$admin' limit 1 ")

            su "$admin" -c "touch  /home/$admin/.list_ab_cmp"
            su "$admin" -c "echo '' > /home/$admin/.list_ab_cmp"

            echo $company
            data=`echo "$company" | tr "|" " "`

            for datafile in $data
            do
            # echo 'first '
                echo "$datafile" 
                echo "$datafile" >> /home/$admin/.list_ab_cmp
            done
            sed -i '/^$/d' /home/$admin/.list_ab_cmp  
            # sed -i 's/^Data\\\\//' /home/$admin/.list_ab_cmp

    else
        echo "Invalid parameter"
    fi
fi