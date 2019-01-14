<?php
/**
 * Created by PhpStorm.
 * User: Kabbali
 * Date: 11/12/2017
 * Time: 3:53 PM
 */

include "Assets/CommandInputs.php";
include "Assets/ConfigReader.php";
include "Assets/SqlManager.php";

// TODO
// add ezproxy domain and ip as a database
// search for databases files if there is an IncludeFile Directive

// VARIABLE TO STORAGE THE ACCCOUNT VALUE TO INSERT DE DB's
$account_value;
//$account_value = 1;

// VARIABLE TO STORAGE THE SERVER_IP
$server_ip_value;
//$server_ip_value = "172.18.0.2";

// VARIABLE TO STORAGE THE MY_SQL USER VALUE
$mysqluser_value;
//$mysqluser_value = "root";

// VARIABLE TO STORAGE THE MY_SQL PASSWOED VALUE
$mysqlpass_value;
//$mysqlpass_value = "a1b2c3d4";

// VARIABLE TO STORAGE THE CONFIG FILE
$config_file;

// VARIABLE TO STORAGE THE CONFIG FILE PATH
$config_file_path;
//$config_file_path = "D:\Projects\Referencistas\Intelproxy\ConfigReader\Assets\config.txt";

// IF THE COMMAND ASK FOR HELP
if(in_array($HELP, $argv)){
    echo "INTELPROXY IPC STANZAS \n";
    echo $ACCOUNT." To specify the account id where stanzas will be added \n";
    echo $SERVER_IP." To specify the mysql server ip, you can check it with docker inspect \n";
    echo $MYSQLUSER." To specify the mysql user \n";
    echo $MYSQLPASS." To specify the mysql password \n";
    echo $CONFIG_PATH." To specify the config.txt path. Use /home/{{user}}/config.txt \n";
    exit(0);
}

// WE CHECK IF AN ACCOUNT ID IS GIVEN
if(!in_array($ACCOUNT, $argv)){
    echo "ERROR: You must specify the account id --> use ".$ACCOUNT."\n";
    exit(1);
}else{
    $account_value = $argv[array_search($ACCOUNT, $argv)+1];
}

// WE CHECK IF A SERVER IP IS GIVEN
if(!in_array($SERVER_IP, $argv)){
    echo "ERROR: You must specify the server ip --> use ".$SERVER_IP."\n";
    exit(1);
}else{
    $server_ip_value = $argv[array_search($SERVER_IP, $argv)+1];
}

// WE CHECK IF A MY_SQL USER IS GIVEN
if(!in_array($MYSQLUSER, $argv)){
    echo "ERROR: You must specify the mysql user on Intelproxy database --> use ".$MYSQLUSER."\n";
    exit(1);
}else{
    $mysqluser_value = $argv[array_search($MYSQLUSER, $argv)+1];
}

// WE CHECK IF A MY_SQL PASSWORD IS GIVEN
if(!in_array($MYSQLPASS, $argv)){
    echo "ERROR: You must specify the mysql password on Intelproxy database --> use ".$MYSQLPASS."\n";
    exit(1);
}else{
    $mysqlpass_value = $argv[array_search($MYSQLPASS, $argv)+1];
}

// WE CHECK IF A CONFIG PATH IS GIVEN
if(!in_array($CONFIG_PATH, $argv)){
    echo "ERROR: You must specify the config.txt path --> use ". $CONFIG_PATH."\n";
    exit(1);
}else{
    $config_file_path = $argv[array_search($CONFIG_PATH, $argv)+1];
}

// WE CHECK IF THERE IS A CONFIG.TXT FILE IN GIVEN PATH
if(file_exists($config_file_path)){
    $config_file = file_get_contents($config_file_path);
}else{
    echo "ERROR: There ir not config.txt file --> check it in ".$config_file_path."\n";
    exit(1);
}

echo <<< ConfirmMessage
Confirm to run
$account_value
$server_ip_value
$mysqluser_value
$mysqlpass_value
$config_file_path \n
ConfirmMessage;

$stanzas_array = applyRegexToConfigFile($config_file);

generateSQL($stanzas_array, $account_value, $server_ip_value, $mysqluser_value, $mysqlpass_value);

exit(0);

