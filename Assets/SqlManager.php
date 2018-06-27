<?php
/**
 * Created by PhpStorm.
 * User: Kabbali
 * Date: 10/12/2017
 * Time: 1:23 AM
 */

function generateSQL(array $stanzas_array, $account_value, $server_ip_value, $mysqluser, $mysqlpass)
{
    // DB CONNECTION

    $dbname = "intelproxy";
    $conn = mysqli_connect($server_ip_value, $mysqluser, $mysqlpass, $dbname);

    // CHECK CONNECTION
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // DELETE BEFORE CREATE REGISTRIES
    $sql_truncate_basedatos_patrones = "DELETE FROM basedatos_patrones WHERE cuenta_id = '$account_value'";
    mysqli_query($conn, $sql_truncate_basedatos_patrones) or die(mysqli_error($conn));

    $sql_truncate_basedatos = "DELETE FROM basedatos WHERE cuenta_id = '$account_value'";
    mysqli_query($conn, $sql_truncate_basedatos) or die(mysqli_error($conn));

    // DELETE -HIDE STANZAS
    $stanzas_array = delete_hide_stanzas($stanzas_array);

    // FOR EACH STANZA FOUNDED IN CONFIG READER FILE
    foreach ($stanzas_array as $stanza) {

        // ASSIGN THE UNIQUE PATTERNS DELETING THEM
        $stanza->setPatterns(delete_duplicated_patterns($stanzas_array, $stanza)->getPatterns());

        // DELETE WILDCARD PATTERNS
        $stanza->setPatterns(delete_wildcard_patterns($stanza));

        // CHECK FOR STANZAS'S VOID PATTERNS
        if(empty($stanza->getPatterns())){
            $stanza = patterns_generator_from_url($stanza);
        }

        // INSERT STANZA INTO BASEDATOS
        $sql_insert_basedatos = "INSERT INTO basedatos (cuenta_id, titulo, url, orden) VALUES ('$account_value','$stanza->title','$stanza->url','$stanza->order')";
        mysqli_query($conn, $sql_insert_basedatos) or die(mysqli_error($conn));
        echo $stanza->title." created successfully \n";
        //echo 'INSERT INTO basedatos (cuenta_id, titulo, url, orden) VALUES ('.$account_value.','.$stanza->title.','.$stanza->url.','.$stanza->order.'); <br>';
        //echo $stanza->getTitle() . " created successfully <br>";

        // ITERATE EACH STANZAS'S PATTERN TO GENERATE ITS SQL
        foreach ($stanza->patterns as $pattern) {
            // CONCATENATE SQL SENTENCES
            $sql_insert_basedatos_patrones =
                "INSERT INTO basedatos_patrones (cuenta_id, basedatos_id, patron)
                 VALUES ('$account_value',(SELECT id FROM basedatos WHERE orden = $stanza->order),'$pattern')";
            mysqli_query($conn, $sql_insert_basedatos_patrones) or die(mysqli_error($conn));
            echo "\t\t".$pattern." added to database ".$stanza->title." successfully \n";
            //echo $pattern . " added successfully <br>";
        }
        // TO SEPARATE DATABASES BETWEEN THEM-SELF
        //echo '<br>';
    }

    mysqli_close($conn);
}

function delete_duplicated_patterns(array $stanzas_array, $stanza)
{

    // CREATE TMP_STANZA TO COMPARE IT WITH OTHERS STANZAS AND DELETE DUPLICATED HJ OR DJ - TH LAST RECORD WILL BE SAVED
    $tmp_stanza = $stanza;

    // WE CHECK IF SOME STANZA HAS DUPLICATED PATTERNS COMPARED WITH THE OTHERS ONES
    foreach ($stanzas_array as $unique_stanza) {

        // CHECK IF IS NOT THE SAME STANZA TO BE COMPARED - WE ASSUME THAT EACH STANZAS HAS TO HAVE AN UNIQUE TITLE
        if ($unique_stanza->getTitle() != $tmp_stanza->getTitle()) {

            // DELETE THE DUPLICATED PATTERNS BETWEEN THE DIFFERENT STANZAS
            $tmp_stanza->setPatterns(array_diff($tmp_stanza->getPatterns(), $unique_stanza->getPatterns()));
        }
    }

    return $tmp_stanza;
}

function delete_hide_stanzas(array $stanzas_array)
{
    foreach ($stanzas_array as $key => $stanza) {
        if (strpos($stanza->getTitle(), '-hide') !== false) {
            echo "Warning: ".$stanza->getTitle()." database will not be added as a Intelproxy stanza \n";
            //echo "Warning: ".$stanza->getTitle()." database will not be added as a Intelproxy stanza <br>";
            unset($stanzas_array[$key]);
        }
    }
    echo "\n";
    //echo "<br>";
    return $stanzas_array;
}

function delete_wildcard_patterns($stanza)
{
    $tmp_patterns = array();
    foreach ($stanza->getPatterns() as $key => $pattern){
         array_push($tmp_patterns, str_replace('*.', '', $pattern));
    }
    return $tmp_patterns;
}

function patterns_generator_from_url($stanza)
{
    $CHARACTERS_TO_SCAPE = array(
        0 => 'http://',
        1 => 'https://',
        2 => '/',
        3 => '.',
        4 => '?',
        5 => '^'
    );
    $REPLACES = array(
        0 => '',
        1 => '',
        2 => '\\/',
        3 => '\\.',
        4 => '\\?',
        5 => '\\^'
    );
    $stanza->addItemToArray('#('.str_replace($CHARACTERS_TO_SCAPE, $REPLACES, $stanza->getUrl()).")#");
    return $stanza;
}

