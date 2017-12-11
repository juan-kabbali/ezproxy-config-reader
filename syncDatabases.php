<?php
/**
 * Created by PhpStorm.
 * User: Kabbali
 * Date: 11/12/2017
 * Time: 3:53 PM
 */

include "Assets/CommandOptions.php";
include "Assets/ConfigReader.php";
include "Assets/SqlManager.php";

$account_value;
$mysqluser_value;
$mysqlpass_value;
$config_file;
$config_file_path = "/usr/local/ezproxy/config.txt";

if(!in_array($ACCOUNT, $argv)){
    echo "ERROR: You must specify the account id --> use ".$ACCOUNT."\n";
    exit(1);
}else{
    $account_value = $argv[array_search($ACCOUNT, $argv)+1];
}

if(!in_array($MYSQLUSER, $argv)){
    echo "ERROR: You must specify the mysql user on Intelproxy database --> use ".$MYSQLUSER."\n";
    exit(1);
}else{
    $mysqluser_value = $argv[array_search($MYSQLUSER, $argv)+1];
}

if(!in_array($MYSQLPASS, $argv)){
    echo "ERROR: You must specify the mysql password on Intelproxy database --> use ".$MYSQLPASS."\n";
    exit(1);
}else{
    $mysqlpass_value = $argv[array_search($MYSQLPASS, $argv)+1];
}

if(file_exists($config_file_path)){
    $config_file = file_get_contents('config.txt');
}else{
    echo "ERROR: There ir not config.txt file --> check it in ".$config_file_path."\n";
    exit(1);
}

$stanzas_array = applyRegexToConfigFile($config_file);
GenerateSQL($stanzas_array, $account_value, $mysqluser_value, $mysqlpass_value);








