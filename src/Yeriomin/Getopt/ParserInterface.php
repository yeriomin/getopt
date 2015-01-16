<?php
/**
 * Console arguments parser interface
 *
 * @author yeriomin
 */
namespace Yeriomin\Getopt;

interface ParserInterface
{

    /**
     * Get all long options
     * Unless parse() is called beforehand, returns an empty array
     *
     * @return array
     */
    public function getOptionsLong();

    /**
     * Get a long option value
     *
     * @param string $name
     * @return array
     */
    public function getOptionLong($name);

    /**
     * Get all short options
     * Unless parse() is called beforehand, returns an empty array
     *
     * @return array
     */
    public function getOptionsShort();

    /**
     * Get a short option value
     *
     * @param string $name
     * @return array
     */
    public function getOptionShort($name);

    /**
     * Get arguments which are not options
     *
     * @return array
     */
    public function getArguments();

    /**
     * Get console arguments list
     *
     * @param array $argv An array of raw console arguments
     */
    public function parse(array $argv);
}
