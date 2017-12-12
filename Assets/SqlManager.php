<?php
/**
 * Created by PhpStorm.
 * User: Kabbali
 * Date: 10/12/2017
 * Time: 1:23 AM
 */


function GenerateSQL(array $stanzas_array, $account_value, $mysqluser, $mysqlpass){

    $servername = "localhost";
    $dbname = "intelproxy";

    // Create connection
    $conn = mysqli_connect($servername, $mysqluser, $mysqlpass, $dbname);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // DELETE BEFORE CREATE REGISTRIES
    $sql_truncate_basedatos = "DELETE FROM adm_basedatos";
    mysqli_query($conn, $sql_truncate_basedatos) or die(mysqli_error($conn));

    $sql_delete_cuenta = "DELETE FROM cuentas_x_basedatos WHERE cuenta_id = '$account_value'";
    mysqli_query($conn, $sql_delete_cuenta) or die(mysqli_error($conn));

    $sql_truncate_patrones = "DELETE FROM adm_basedatos_patrones";
    mysqli_query($conn, $sql_truncate_patrones) or die(mysqli_error($conn));

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
        $sql_adm_basedatos = "INSERT INTO adm_basedatos (id, titulo, url) VALUES ('$stanza->db_var','$stanza->title','$stanza->url')";
        mysqli_query($conn, $sql_adm_basedatos) or die(mysqli_error($conn));
        echo $stanza->title." created successfully \n";
        //echo 'INSERT INTO adm_basedatos ( id, titulo, url) VALUES (' . $stanza->db_var . ', ' . $stanza->title . ', ' . $stanza->url . '); <br>';


        // ADD IT TO ID ACCOUNT
        $sql_cuentas_x_basedatos = "INSERT INTO cuentas_x_basedatos (cuenta_id, basedatos_id) VALUES ('$account_value','$stanza->db_var')";
        mysqli_query($conn, $sql_cuentas_x_basedatos ) or die(mysqli_error($conn));
        echo $stanza->title." added to account ".$account_value." successfully \n";
        //echo 'INSERT INTO cuentas_x_basedatos (cuenta_id, basedatos_id) VALUES (' .$account_value . ',' . $stanza->db_var . '); <br>';

        foreach ($stanza->patterns as $pattern) {
            // ADD PATTERNS TO DATABASE
            $sql_adm_basedatos_patrones = "INSERT INTO adm_basedatos_patrones (basedatos_id, patron) VALUES ('$stanza->db_var','$pattern')";
            mysqli_query($conn, $sql_adm_basedatos_patrones) or die(mysqli_error($conn));
            echo $pattern." added to database ".$stanza->title." successfully \n";
            //echo 'INSERT INTO adm_basedatos_patrones ( basedatos_id, patron) VALUES (' . $stanza->db_var . ', ' . $pattern . '); <br>';
        }
        //echo '<br>';
    }
    mysqli_close($conn);
}



