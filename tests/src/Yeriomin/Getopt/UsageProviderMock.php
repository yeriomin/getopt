<?php

namespace Yeriomin\Getopt;

/**
 * UsageProviderMock exists to test UsageProvider
 * It has getters which are not needed in UsageProvider
 *
 * @author yeriomin
 */
class UsageProviderMock extends UsageProvider
{

    /**
     * Get script name
     *
     * @return string
     */
    public function getScriptName()
    {
        return $this->scriptName;
    }

    /**
     * Get the OptionDefinition array
     *
     * @return array
     */
    public function getOptionDefinitions()
    {
        return $this->optionDefinitions;
    }
}
