<?php
/**
 * Usage message provider
 * Builds a help/usage message for the console app
 * based on option definitions provided
 *
 * @author yeriomin
 */
namespace Yeriomin\Getopt;

class UsageProvider implements UsageProviderInterface {

    /**
     * Script name to use in the help message
     * Defaults to $argv[0]
     *
     * @var string
     */
    protected $scriptName = '';

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
     * @throws GetoptException
     *
     * @return UsageProviderInterface
     */
    public function setScriptName($name) {
        $this->scriptName = $name;
        return $this;
    }

    /**
     * Add an option definition
     *
     * @param OptionDefinition $option
     *
     * @return UsageProviderInterface
     */
    public function addOptionDefinition(OptionDefinition $option) {
        $this->optionDefinitions[] = $option;
        return $this;
    }

    /**
     * Get usage message
     *
     * @return string
     */
    public function getUsageMessage() {
        if (empty($this->scriptName)) {
            throw new GetoptException('Script name can not be empty');
        }
        $helpText = 'Usage: ' . $this->scriptName . ' [arguments]' . "\n\n";
        $helpText .= 'Arguments:' ."\n";
        $args = array();
        $charCount = 0;
        foreach ($this->optionDefinitions as $option) {
            $short = $option->getShort();
            $long = $option->getLong();
            $arg = ' ';
            if ($short !== null && $long !== null) {
                $arg .= '-' . $short . ', --' . $long;
            } else {
                $arg .= $short !== null ? '-' . $short : '--' . $long;
            }
            $charCount = $charCount < strlen($arg) ? strlen($arg) : $charCount;
            $args[$arg] = $option->getDescription();
        }
        foreach ($args as $arg => $description) {
            $helpText .= str_pad($arg, $charCount + 1) . $description . "\n";
        }
        return $helpText;
    }

}
