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

    const PADDING = 25;

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
        if (empty($name)) {
            throw new GetoptException('Script name can not be empty');
        }
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
        $helpText = 'Usage: ' . $this->scriptName . ' [arguments]' . "\n";
        $helpText .= 'Arguments:' ."\n";
        foreach ($this->optionDefinitions as $option) {
            $mode = '';
            switch ($option->mode()) {
                case self::NO_ARGUMENT:
                    $mode = '';
                    break;
                case OptionDefinition::REQUIRED:
                    $mode = "<arg>";
                    break;
                case self::OPTIONAL_ARGUMENT:
                    $mode = "[<arg>]";
                    break;
            }
            $short = ($option->short()) ? '-'.$option->short() : '';
            $long = ($option->long()) ? '--'.$option->long() : '';
            if ($short && $long) {
                $options = $short.', '.$long;
            } else {
                $options = $short ? : $long;
            }
            $padded = str_pad(sprintf("  %s %s", $options, $mode), $padding);
            $helpText .= sprintf("%s %s\n", $padded, $option->getDescription());
        }
        return $helpText;
    }

}
