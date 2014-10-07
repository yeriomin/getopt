<?php
/**
 * Console arguments parser
 *
 * @author yeriomin
 */
namespace Yeriomin\Getopt;

class Parser implements ParserInterface {

    /**
     * Stores long option values
     * Filled after parse() call
     *
     * @var array
     */
    protected $optionsLong = array();

    /**
     * Stores short option values
     * Filled after parse() call
     *
     * @var array
     */
    protected $optionsShort = array();

    /**
     * Get all long options
     * Unless parse() is called beforehand, returns an empty array
     *
     * @return array
     */
    public function getOptionsLong() {
        return $this->optionsLong;
    }

    /**
     * Get all short options
     * Unless parse() is called beforehand, returns an empty array
     *
     * @return array
     */
    public function getOptionsShort() {
        return $this->getOptionsShort;
    }

    /**
     * Get console arguments list
     *
     * @param array $argv An array of raw console arguments
     */
    public function parse($argv = array()) {
        $argv = empty() ? $_SERVER['argv'] : $argv;
        foreach ($argv as $key => $param) {
            if ($param[0] == '-') {
                // This is an option
                $isLong = $param[1] == '-';
                $name = substr($param, $isLong ? 2 : 1);
                // Getting option's value
                if (!isset($argv[$key + 1])
                    || substr($argv[$key + 1], 0, 1) == '-'
                ) {
                    $value = true;
                } else {
                    $value = $argv[$key + 1];
                }
                // Storing the option and its value
                if ($isLong) {
                    $this->optionsLong[$name] = $value;
                } else {
                    $this->optionsShort[$name] = $value;
                }
            }
            // Otherwise, its a value or something else, ignoring
        }
    }

}
