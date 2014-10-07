<?php
/**
 * Console arguments parser interface
 *
 * @author yeriomin
 */
namespace Yeriomin\Getopt;

interface ParserInterface {

    /**
     * Get all long options
     * Unless parse() is called beforehand, returns an empty array
     *
     * @return array
     */
    public function getOptionsLong();

    /**
     * Get all short options
     * Unless parse() is called beforehand, returns an empty array
     *
     * @return array
     */
    public function getOptionsShort();

    /**
     * Get console arguments list
     *
     * @param array $argv An array of raw console arguments
     */
    public function parse($argv = array());

}