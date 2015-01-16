<?php
/**
 * Usage message provider
 * Builds a help/usage message for the console app
 * based on option definitions provided
 *
 * @author yeriomin
 */
namespace Yeriomin\Getopt;

class UsageProvider implements UsageProviderInterface
{

    /**
     * Script name to use in the help message
     *
     * @var string
     */
    protected $scriptName = '';

    /**
     * Arguments description. For example:
     * cp [OPTIONS] SOURCE DESTINATION
     *
     * @var string
     */
    protected $argumentsDescription = '[OPTIONS] [ARGUMENTS]';

    /**
     * An array of OptionDefinition objects
     *
     * @var array
     */
    protected $optionDefinitions = array();

    /**
     * Set script name
     *
     * @param string $name
     *
     * @return UsageProviderInterface
     */
    public function setScriptName($name)
    {
        $this->scriptName = $name;
        return $this;
    }

    /**
     * Set arguments description. For example:
     * cp [OPTIONS] SOURCE DESTINATION
     *
     * @param string $argumentsDescription
     * @return \Yeriomin\Getopt\UsageProvider
     */
    public function setArgumentsDescription($argumentsDescription)
    {
        $this->argumentsDescription = $argumentsDescription;
        return $this;
    }

    /**
     * Add an option definition
     *
     * @param OptionDefinition $option
     *
     * @return UsageProviderInterface
     */
    public function addOptionDefinition(OptionDefinition $option)
    {
        $this->optionDefinitions[] = $option;
        return $this;
    }

    /**
     * Get usage message
     *
     * @return string
     */
    public function getUsageMessage()
    {
        if (empty($this->scriptName)) {
            throw new GetoptException('Script name can not be empty');
        }
        $helpText = 'Usage: ' . $this->scriptName . ' '
            . $this->argumentsDescription . "\n\n" . 'Options:' ."\n"
        ;
        $args = array();
        $charCount = 0;
        foreach ($this->optionDefinitions as $option) {
            $arg = $this->getOptionString($option);
            $charCount = $charCount < strlen($arg) ? strlen($arg) : $charCount;
            $args[$arg] = $option->getDescription();
        }
        foreach ($args as $arg => $description) {
            $helpText .= str_pad($arg, $charCount + 1) . $description . "\n";
        }
        return $helpText;
    }

    /**
     * Get a human-readable string definition of an option
     *
     * @param \Yeriomin\Getopt\OptionDefinition $option
     * @return string
     */
    private function getOptionString(OptionDefinition $option)
    {
        $short = $option->getShort();
        $long = $option->getLong();
        $result = ' ';
        if ($short !== null && $long !== null) {
            $result .= '-' . $short . ', --' . $long;
        } else {
            $result .= $short !== null ? '-' . $short : '--' . $long;
        }
        return $result;
    }
}
