<?php
/**
 * User: Juan
 * Date: 29/11/2017
 * Time: 5:16 PM
 */
include "Stanza.php";

function applyRegexToConfigFile($config_file): array
{
    // LIST OF CHARACTERS TO TRIM
    $CHARACTERS_TO_TRIM = ["'","(",")"];

    // TO FIND TITLE's
    $titleRegex = '(?<title>(?<!\h|#|\d|\w)(Title|T)\h(.*\w))';
    $TITLE_MATCH_INDEX = 2;
    $TITLE_DIRECTIVES = ['T', 'Title', 'title', 'TITLE'];

    // TO FIND URL's
    $urlRegex = '(?<url>(?<!\h|#|\d|\w)(U|URL)\h(.*\w))';
    $URL_MATCH_INDEX = 5;
    $URL_DIRECTIVES = ['U', 'URL', 'url', 'Url'];

    //TO FIND HOST's AND DOMAIN's
    $djhjhostRegex = '(?<djhjhost>(?<!\h|#|\d|\w)(Domain|DJ|HJ|Host)\h(.*\w))';
    $PATTERNS_MATCH_INDEX = 8;
    $PATTERNS_DIRECTIVES = ['Domain', 'DJ', 'HJ', 'Host'];

    // TO FIND SERVER NAME
    $nameRegex = '(?<name>(?<!\h|#|\d|\w)(Name)\h(.*\w))';
    $NAME_MATCH_INDEX = 11;
    $NAME_DIRECTIVES = ['Name'];

    // FULL REGEX STRING
    $fullRegex = '/' . $titleRegex . '|' . $urlRegex . '|' . $djhjhostRegex . '|' . $nameRegex .'/';

    // STANZAS ARRAY
    $stanzas_array = array();

    // APPLY REGEX TO CONFIG.TXT
    preg_match_all($fullRegex, $config_file, $matches, PREG_SET_ORDER, 0);

    foreach ($matches as $match) {

        // THIS FLAG ALLOWS TO CONTROL WHEN TO PUSH OR NOT A NEW STANZA
        $have_add = false;

        // IF THE MATCH IS THE NAME DIRECTIVE, WE CREATE THE LOCAL STANZA
        if(isset($match[$NAME_MATCH_INDEX])){
            if (in_array($match[$NAME_MATCH_INDEX], $NAME_DIRECTIVES)) {
                array_push($stanzas_array, add_local_stanza($match[$NAME_MATCH_INDEX+ 1]));
            }
        }

        // CHECK IF THERE IS A TITLE, IF IT IS, THAT MEANS THERE IS A NEW ONE
        if (isset($match[$TITLE_MATCH_INDEX])) {
            if (in_array($match[$TITLE_MATCH_INDEX], $TITLE_DIRECTIVES)) {

                // TURN UP FLAG TO INSERT THIS NEW STANZA AT THE END OF LOOP
                $have_add = true;

                //WE CREATE A NEW STANZA AND SET TITLE VALUE
                $stanza = new Stanza();
                $trim_title = str_replace($CHARACTERS_TO_TRIM, "", $match[$TITLE_MATCH_INDEX + 1]);
                $stanza->setTitle(strtolower($trim_title));
                //echo $match[$TITLE_MATCH_INDEX].'-->' .$stanza->getTitle().'<br>';
            }
        }

        // CHECK IF THERE IS A URL
        if (isset($match[$URL_MATCH_INDEX])) {
            if (in_array($match[$URL_MATCH_INDEX], $URL_DIRECTIVES)) {
                $stanza->setUrl($match[$URL_MATCH_INDEX + 1]);
                //echo $match[$URL_MATCH_INDEX].'-->' .$stanza->getUrl().'<br>';
            }
        }

        // CHECK IR THERE IS A PATTERN's
        if (isset($match[$PATTERNS_MATCH_INDEX])) {
            if (in_array($match[$PATTERNS_MATCH_INDEX], $PATTERNS_DIRECTIVES)) {
                // CLEAN THE HTTP:// OR HTTPS:// BEFORE ADD A PATTERN
                $stanza->addItemToArray(clean_http_or_https_from_pattern($match[$PATTERNS_MATCH_INDEX+1]));

                //PRINT PATTERNS FOR DEBUG
                for ($i = 0; $i < count($stanza->getPatterns()); $i++){
                    //echo $match[$PATTERNS_MATCH_INDEX].'['.$i.']'. ' --> '.$stanza->getPatterns()[$i].'<br>';
                }
            }
        }

        // WE CHECK IF FLAG IS UP TO PUSH THE STANZA INSIDE THE ARRAY AND AVOID TO LOSE INFORMATION
        if ($have_add) {
            array_push($stanzas_array, $stanza);
            $have_add = false;
        }

        // TO SEPARATE PATTERNS BETWEEN DIFFERENT STANZAS
        //echo '<br>';
    }

    return $stanzas_array;
}

function clean_http_or_https_from_pattern($pattern){
    $TO_CLEAN = array(
        0 => 'http://',
        1 => 'https://',
    );
    $REPLACES = array(
        0 => '',
        1 => '',
    );
    return (str_replace($TO_CLEAN, $REPLACES, $pattern));
}

function add_local_stanza($name){
    $local_stanza = new Stanza();
    $local_stanza->setOrder(100);
    $local_stanza->setTitle("Ezproxy");
    $local_stanza->setUrl("http://".$name);
    $ip = gethostbyname($name);
    $patterns_one_line = $name.'|'.$ip;
    $local_stanza->setPatternsOneLine($patterns_one_line);
}


