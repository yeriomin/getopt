<?php
/**
 * Console arguments parser
 * Parses and stores everything, regardless of the options you defined
 *
 * @author yeriomin
 */
namespace Yeriomin\Getopt;

class Parser implements ParserInterface
{

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
     * Stores arguments, i.e. Not option names and not option values.
     *
     * @var array
     */
    protected $arguments = array();

    /**
     * Returns true if provided string is an option name
     *
     * @param string $string
     * @return boolean
     */
    private static function isOption($string)
    {
        return !self::isArgumentSeparator($string) && $string[0] === '-';
    }

    /**
     * Returns true if provided string is a name of a long option
     *
     * @param string $string
     * @return boolean
     */
    private static function isLong($string)
    {
        return $string[1] == '-';
    }

    /**
     * Returns true if provided string is the argument separator
     *
     * @param string $string
     * @return boolean
     */
    private static function isArgumentSeparator($string)
    {
        return $string === '--';
    }

    /**
     * Returns true if provided string is the argument separator
     *
     * @param string $string
     * @return boolean
     */
    private static function isClustered($string)
    {
        return $string[0] === '-' && $string[1] != '-' && strlen($string) > 2;
    }

    /**
     * Get all long options
     * Unless parse() is called beforehand, returns an empty array
     *
     * @return array
     */
    public function getOptionsLong()
    {
        return $this->optionsLong;
    }

    /**
     * Get all short options
     * Unless parse() is called beforehand, returns an empty array
     *
     * @return array
     */
    public function getOptionsShort()
    {
        return $this->optionsShort;
    }

    /**
     * Get arguments which are not options
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Parse console arguments list
     *
     * @param array $argv An array of raw console arguments
     */
    public function parse(array $argv)
    {
        if (empty($argv)) {
            return;
        }
        $argNum = 0;
        while ($argNum < count($argv)) {
            $arg = $argv[$argNum];
            if (self::isOption($arg)) {
                // This is an option
                $isLong = self::isLong($arg);
                $name = substr($arg, $isLong ? 2 : 1);
                // Getting option's value
                if (!isset($argv[$argNum + 1])
                    || self::isOption($argv[$argNum + 1])
                    || self::isArgumentSeparator($argv[$argNum + 1])
                    || self::isClustered($arg)
                ) {
                    $value = true;
                } else {
                    $value = $argv[$argNum + 1];
                    $argNum++;
                }
                // Storing the option and its value
                if ($isLong) {
                    $this->optionsLong[$name] = $value;
                } else {
                    // Short options can be clustered
                    $length = strlen($name);
                    for ($i = 0; $i < $length; $i++) {
                        $this->optionsShort[$name[$i]] = $value;
                    }
                }
            } elseif (self::isArgumentSeparator($arg)) {
                // Its the argument separator - every following argument
                // is an actual argument, not an option, regardless of dashes
                $this->arguments = array_merge(
                    $this->arguments,
                    array_slice($argv, $argNum + 1)
                );
                break;
            } else {
                // Its just an argument because it is not following an option
                $this->arguments[] = $arg;
            }
            $argNum++;
        }
        if (!empty($this->arguments)
            && $this->arguments[0] == $_SERVER['PHP_SELF']
        ) {
            array_shift($this->arguments);
        }
    }
}
