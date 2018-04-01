<?php
/**
 * Created by PhpStorm.
 * User: Kabbali
 * Date: 10/12/2017
 * Time: 1:23 AM
 */


function GenerateSQL(array $stanzas_array, $account_value, $mysqluser, $mysqlpass){

    $servername = "172.18.0.2";
    $dbname = "intelproxy";

    // Create connection
    $conn = mysqli_connect($servername, $mysqluser, $mysqlpass, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // DELETE BEFORE CREATE REGISTRIES
    $sql_truncate_basedatos_patrones = "DELETE FROM basedatos_patrones WHERE cuenta_id = '$account_value'";
    mysqli_query($conn, $sql_truncate_basedatos_patrones) or die(mysqli_error($conn));

    $sql_truncate_basedatos = "DELETE FROM basedatos WHERE cuenta_id = '$account_value'";
    mysqli_query($conn, $sql_truncate_basedatos) or die(mysqli_error($conn));

    foreach ($stanzas_array as $stanza) {
        // get tmp array to compare it with others stanzas and delete duplicate HJ or DJ - the last record will keep them
        $tmp_stanza = $stanza;
        foreach ($stanzas_array as $unique_stanza){
            if($unique_stanza->db_var != $tmp_stanza->db_var){
                $tmp_stanza->patterns = array_diff($tmp_stanza->patterns, $unique_stanza->patterns);
            }
        }

        // Assign the unique array DJ or HJ to the current stanza
        $stanza->patterns = $tmp_stanza->patterns;

        // INSERT DATABASES
        //TODO create in the stanza class an attribute which indicates the order value
        $sql_insert_basedatos = "INSERT INTO basedatos (cuenta_id, titulo, url, orden) VALUES ('$account_value','$stanza->title','$stanza->url','$stanza->order')";
        mysqli_query($conn, $sql_insert_basedatos) or die(mysqli_error($conn));
        echo $stanza->title." created successfully \n";
        //echo 'INSERT INTO adm_basedatos ( id, titulo, url) VALUES (' . $stanza->db_var . ', ' . $stanza->title . ', ' . $stanza->url . '); <br>';

        foreach ($stanza->patterns as $pattern) {
            // ADD PATTERNS TO DATABASE
            $sql_insert_basedatos_patrones =
                "INSERT INTO basedatos_patrones (cuenta_id, basedatos_id, patron)
                 VALUES ('$account_value','(SELECT id FROM basedatos WHERE titulo=$stanza->title)','$pattern')";
            //VALUES ('$account_value','$stanza->db_var','$pattern')";
            mysqli_query($conn, $sql_insert_basedatos_patrones) or die(mysqli_error($conn));
            echo "\t\t".$pattern." added to database ".$stanza->title." successfully \n";
            //echo 'INSERT INTO adm_basedatos_patrones ( basedatos_id, patron) VALUES (' . $stanza->db_var . ', ' . $pattern . '); <br>';
        }
        //echo '<br>';
    }
    mysqli_close($conn);
}



