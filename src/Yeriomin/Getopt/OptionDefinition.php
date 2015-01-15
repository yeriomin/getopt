<?php
/**
 * Console argument definition
 *
 * @author yeriomin
 */
namespace Yeriomin\Getopt;

class OptionDefinition
{

    /**
     * Short argument definition. One character
     *
     * @var string
     */
    protected $short;

    /**
     * Long argument definition
     *
     * @var string
     */
    protected $long;

    /**
     * Option description. To be shown in the usage message
     *
     * @var string
     */
    protected $description = '';

    /**
     * Whether option is required or not
     *
     * @var boolean
     */
    protected $required = false;

    /**
     * Get short argument definition. One character
     *
     * @return string
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * Get long argument definition
     *
     * @return string
     */
    public function getLong()
    {
        return $this->long;
    }

    /**
     * Get argument description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Return true if the argument is required
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set short argument definition
     *
     * @param string $short A letter to identify the option
     *
     * @return OptionDefinition
     */
    public function setShort($short)
    {
        $short = (string) $short;
        $this->short = function_exists('mb_substr')
            ? mb_substr($short, 0, 1)
            : substr($short, 0, 1)
        ;
        return $this;
    }

    /**
     * Set long argument definition
     *
     * @param string $long A string to identify the option
     *
     * @return OptionDefinition
     */
    public function setLong($long)
    {
        $long = (string) $long;
        $this->long = $long;
        return $this;
    }

    /**
     * Set option description for the usage message
     *
     * @param string $description Option description for the usage message
     *
     * @return OptionDefinition
     */
    public function setDescription($description = '')
    {
        $description = (string) $description;
        $this->description = $description;
        return $this;
    }

    /**
     * Set required flag for the option.
     * If a required option is not provided, an exception is thrown
     *
     * @param boolean $required Should the option be required or not
     *
     * @return OptionDefinition
     */
    public function setRequired($required = true)
    {
        $this->required = (boolean) $required;
        return $this;
    }

    /**
     * Create an option definition
     *
     * @param string $short Short option name. One symbol expected
     * @param string $long Long option name. A string expected
     * @param string $description Option description for the usage message
     * @param boolean $required Should the option be required or not
     */
    public function __construct(
        $short = null,
        $long = null,
        $description = '',
        $required = false
    ) {
        if (!empty($short)) {
            $this->setShort($short);
        }
        $this->setLong($long);
        $this->setDescription($description);
        $this->setRequired($required);
    }
}
