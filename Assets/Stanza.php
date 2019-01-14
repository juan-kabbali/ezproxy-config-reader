<?php
/**
 * Created by PhpStorm.
 * User: Kabbali
 * Date: 9/12/2017
 * Time: 10:44 PM
 */

class Stanza
{
    var $title;
    var $url;
    var $patterns = array();
    var $patterns_one_line;
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
    public function getPatternsOneLine()
    {
        return $this->patterns_one_line;
    }

    /**
     * @param mixed $patterns_one_line
     */
    public function setPatternsOneLine($patterns_one_line): void
    {
        $this->patterns_one_line = $patterns_one_line;
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

