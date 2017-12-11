<?php
/**
 * User: Juan
 * Date: 29/11/2017
 * Time: 5:16 PM
 */
include "Stanza.php";

function applyRegexToConfigFile($config_file):array {

    // REGEX PATTERNS
    $dbvarRegex = '(?<dbvar>(?<!\h|#|\d|\w)(dbvar0|DbVar0)\h(.*\w))';
    $titleRegex = '(?<title>(?<!\h|#|\d|\w)(Title|T)\h(.*\w))';
    $urlRegex = '(?<url>(?<!\h|#|\d|\w)(U|URL)\h(.*\w))';
    $djhjhostRegex = '(?<djhjhost>(?<!\h|#|\d|\w)(DJ|HJ|Host)\h(.*\w))';
    $fullRegex = '/' . $dbvarRegex . '|' . $titleRegex . '|' . $urlRegex . '|' . $djhjhostRegex . '/';

    // STANZAS ARRAY
    $stanzas_array = array();

    preg_match_all($fullRegex, $config_file, $matches, PREG_SET_ORDER, 0);

    foreach ($matches as $match) {

        if ($match[2] == 'DbVar0' | $match[2] == 'dbvar0') {
            //echo '<br>DBVAR<br>';
            if (isset($stanza)) {
                array_push($stanzas_array, $stanza);
            }
            $stanza = new Stanza();
            $stanza->setDbVar($match[3]);
        }

        if (isset($match[5])) {
            if ($match[5] == 'T' | $match[5] == 'Title' | $match[5] == 'title') {
                //echo '<br>TITLE<br>';
                $stanza->setTitle($match[6]);
            }
        }

        if (isset($match[8])) {
            if ($match[8] == 'U' | $match[8] == 'Url' | $match[8] == 'URL') {
                //echo '<br>URL<br>';
                $stanza->setUrl($match[9]);
            }
        }

        if (isset($match[11])) {
            if ($match[11] == 'HJ' | $match[11] == 'DJ') {
                //echo '<br>HJ<br>';
                $stanza->addItemToArray($match[12]);
            }
        }
    }
    return $stanzas_array;
}


