<?php
/**
 * Console argument definition
 *
 * @author yeriomin
 */
namespace Yeriomin\Getopt;

class OptionDefinition {

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
    protected $isRequired = false;

    /**
     * Set short argument definition
     *
     * @param string $short A symbol to identify the option
     *
     * @return OptionDefinition
     */
    public function setShort($short) {
        if (!is_string($short)) {
            throw new GetoptException('Short option name must be a string');
        }
        $this->short = substr($short, 0, 1);
        return $this;
    }

    /**
     * Set long argument definition
     *
     * @param string $long A string to identify the option
     *
     * @return OptionDefinition
     */
    public function setLong($long) {
        if (!is_string($long)) {
            throw new GetoptException('Long option name must be a string');
        }
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
    public function setDescription($description = '') {
        if (!is_string($description)) {
            throw new GetoptException('Option description must be a string');
        }
        $this->description = $description;
        return $this;
    }

    /**
     * Set required flag for the option.
     * If a required option is not provided, an exception is thrown
     *
     * @param boolean $isRequired Should the option be required or not
     *
     * @return OptionDefinition
     */
    public function setIsRequired($isRequired = false) {
        $this->isRequired = (boolean) $isRequired;
        return $this;
    }

    /**
     * Create an option definition
     *
     * @param string $short Short option name. One symbol expected
     * @param string $long Long option name. A string expected
     * @param string $description Option description for the usage message
     * @param boolean $isRequired Should the option be required or not
     */
    public function __construct(
        $short,
        $long = null,
        $description = '',
        $isRequired = false
    ) {
        if (!empty($short)) {
            $this->setShort($short);
        }
        $this->setLong($long);
        $this->setDescription($description);
        $this->setRequired($isRequired);
    }

}
