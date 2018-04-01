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
    var $order;
    static public $order_counter = 0;
    /**
     * Stanza constructor.
     */
    public function __construct()
    {
        $this->order = static::$order_counter;
        static::$order_counter ++;
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

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @param int $order
     */
    public function setOrder(int $order): void
    {
        $this->order = $order;
    }



}

