<?php

namespace Yeriomin\Getopt;

/**
 * GetoptMock adds getters and setters for protected members
 *
 * @author yeriomin
 */
class GetoptMock extends Getopt
{

    /**
     * Get the usage message provider
     *
     * @return UsageProviderInterface
     */
    public function getUsageProvider()
    {
        return $this->usageProvider;
    }

    /**
     * Get the parser
     *
     * @return ParserInterface
     */
    public function getParser()
    {
        return $this->parser;
    }

    public function getOptionDefinitions()
    {
        return $this->optionDefinitions;
    }

    public function setRawArguments($rawArguments)
    {
        $this->rawArguments = $rawArguments;
        return $this;
    }

    public function setOptionDefinitions($optionDefinitions)
    {
        $this->optionDefinitions = $optionDefinitions;
        return $this;
    }

    public function setOptionsLong($optionsLong)
    {
        $this->optionsLong = $optionsLong;
        return $this;
    }

    public function setOptionsShort($optionsShort)
    {
        $this->optionsShort = $optionsShort;
        return $this;
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }
}
