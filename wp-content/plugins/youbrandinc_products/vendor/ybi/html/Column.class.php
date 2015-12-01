<?php
/**
 * Created by PhpStorm.
 * User: Scott
 * Date: 2/26/2015
 * Time: 11:49 AM
 */

class Column extends HTMLObject {
    /**
     * PROPDESCRIPTION
     *
     * @access public
     * @var PROPTYPE
     */
    public $content;

    /**
     * PROPDESCRIPTION
     *
     * @access public
     * @var PROPTYPE
     */
    public $col_span;


    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param ARGTYPE $content ARGDESCRIPTION
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @return RETURNTYPE RETURNDESCRIPTION
     */
    public function getColSpan() {
        return $this->col_span;
    }

    /**
     * METHODDESCRIPTION
     *
     * @access public
     * @param ARGTYPE $content ARGDESCRIPTION
     */
    public function setColSpan($colspan) {
        $this->col_span = $colspan;
    }

}