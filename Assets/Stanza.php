<?php
/**
 * Created by PhpStorm.
 * User: Kabbali
 * Date: 9/12/2017
 * Time: 10:44 PM
 */

class Stanza
{
    var $db_var;
    var $title;
    var $url;
    var $patterns = array();

    /**
     * Stanza constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getDbVar()
    {
        return $this->db_var;
    }

    /**
     * @param mixed $db_var
     */
    public function setDbVar($db_var)
    {
        $this->db_var = $db_var;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function getPatterns(): array
    {
        return $this->patterns;
    }

    /**
     * @param array $patterns
     */
    public function setPatterns(array $patterns)
    {
        $this->patterns = $patterns;
    }

    /**
     * @param $item
     */
    public  function addItemToArray($item){
        if(!in_array($item, $this->patterns)){
            array_push($this->patterns, $item);
        }
    }



}

