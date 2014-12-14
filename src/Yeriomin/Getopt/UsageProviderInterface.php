<?php
/**
 * Usage message provider interface
 *
 * @author yeriomin
 */
namespace Yeriomin\Getopt;

interface UsageProviderInterface
{

    /**
     * Set script name
     *
     * @param string $name
     *
     * @return UsageProviderInterface
     */
    public function setScriptName($name);

    /**
     * Add an option definition
     *
     * @param OptionDefinition $option
     *
     * @return UsageProviderInterface
     */
    public function addOptionDefinition(OptionDefinition $option);

    /**
     * Get usage message
     *
     * @return string
     */
    public function getUsageMessage();
}
