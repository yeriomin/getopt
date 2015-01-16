<?php
/**
 * Getopt alternative
 * Provides a less cryptic way of parsing console arguments
 *
 * @author yeriomin
 */
namespace Yeriomin\Getopt;

class Getopt
{

    /**
     * Raw console arguments
     *
     * @var array
     */
    protected $rawArguments = array();

    /**
     * Usage message builder
     *
     * @var UsageProviderInterface
     */
    protected $usageProvider;

    /**
     * Script name to use in the help message
     *
     * @var string
     */
    protected $scriptName;

    /**
     * Console arguments parser
     *
     * @var ParserInterface
     */
    protected $parser;

    /**
     * Option definitions
     *
     * @var array
     */
    protected $optionDefinitions = array();

    /**
     * Stores long option values
     * Filled after parse() call
     *
     * @var array
     */
    protected $optionsLong;

    /**
     * Stores short option values
     * Filled after parse() call
     *
     * @var array
     */
    protected $optionsShort;

    /**
     * Stores arguments, i.e. Not option names and not option values.
     *
     * @var array
     */
    protected $arguments;

    /**
     * Has parsing been done or not
     *
     * @var boolean
     */
    protected $parsed = false;

    /**
     * Get all long options
     *
     * @return array
     */
    public function getOptionsLong()
    {
        if (!$this->parsed) {
            $this->parse();
        }
        return $this->optionsLong;
    }

    /**
     * Get all short options
     *
     * @return array
     */
    public function getOptionsShort()
    {
        if (!$this->parsed) {
            $this->parse();
        }
        return $this->optionsShort;
    }

    /**
     * Get arguments which are not options
     *
     * @return array
     */
    public function getArguments()
    {
        if (!$this->parsed) {
            $this->parse();
        }
        return $this->arguments;
    }

    /**
     * Set usage message builder
     *
     * @param \Yeriomin\Getopt\UsageProviderInterface $usageProvider
     * @return \Yeriomin\Getopt\Getopt
     */
    public function setUsageProvider(UsageProviderInterface $usageProvider)
    {
        $this->usageProvider = $usageProvider;
        return $this;
    }

    /**
     * Set script name
     *
     * @param string $name
     *
     * @return \Yeriomin\Getopt\Getopt
     */
    public function setScriptName($name)
    {
        $this->scriptName = $name;
        return $this;
    }

    /**
     * Set parser
     *
     * @param \Yeriomin\Getopt\ParserInterface $parser
     * @return \Yeriomin\Getopt\Getopt
     */
    public function setParser(ParserInterface $parser)
    {
        $this->parsed = false;
        $this->parser = $parser;
        return $this;
    }

    /**
     * Add an option definition
     *
     * @param \Yeriomin\Getopt\OptionDefinition $optionDefinition
     * @return \Yeriomin\Getopt\Getopt
     */
    public function addOptionDefinition(OptionDefinition $optionDefinition)
    {
        $this->optionDefinitions[] = $optionDefinition;
        return $this;
    }

    /**
     * Build and return the usage message based on defined options
     *
     * @return string
     */
    public function getUsageMessage()
    {
        foreach ($this->optionDefinitions as $def) {
            $this->usageProvider->addOptionDefinition($def);
        }
        if (empty($this->scriptName)) {
            $this->scriptName = $_SERVER['PHP_SELF'];
        }
        $this->usageProvider->setScriptName($this->scriptName);
        return $this->usageProvider->getUsageMessage();
    }

    /**
     * Return an array of raw arguments from the command line
     *
     * @return array
     */
    public function getRawArguments()
    {
        return $this->rawArguments;
    }

    /**
     * Parse the console arguments using the provided parser
     * and check them for validity
     *
     * @throws GetoptException
     */
    public function parse()
    {
        $this->parser->parse($this->rawArguments);
        $this->parsed = true;
        $this->arguments = $this->parser->getArguments();
        $optionsShort = $this->parser->getOptionsShort();
        $optionsLong = $this->parser->getOptionsLong();
        $missongRequired = array();
        foreach ($this->optionDefinitions as $definition) {
            $value = $this->getOptionValue($definition);
            $short = $definition->getShort();
            $long = $definition->getLong();
            $optionsShort[$short] = $value;
            $optionsLong[$long] = $value;
            if ($definition->getRequired() && $value === null) {
                $parts = array();
                $parts[] = $short !== null ? '-' . $short : null;
                $parts[] = $long !== null ? '--' . $long : null;
                $missongRequired[] = implode('|', $parts);
            }
        }
        if (!empty($missongRequired)) {
            throw new GetoptException(
                'Missing required options: '
                . implode(', ', $missongRequired)
            );
        }
        $this->optionsShort = $optionsShort;
        $this->optionsLong = $optionsLong;
    }

    /**
     * Returns an option value
     *
     * @param string $option
     */
    public function __get($option)
    {
        if (!$this->parsed) {
            $this->parse();
        }
        $type = strlen($option) === 1 ? 'optionsShort' : 'optionsLong';
        $container = $this->$type;
        return isset($container[$option]) ? $container[$option] : null;
    }

    /**
     * Construct the console arguments/options handler.
     * Check and fill the raw arguments array,
     * init parser and usage message provider
     *
     * @param \Traversable $rawArguments
     * @throws GetoptException
     */
    public function __construct($rawArguments = null)
    {
        if (null === $rawArguments) {
            $rawArguments = PHP_SAPI == 'cli' ? $_SERVER['argv'] : array();
        } elseif (!is_array($rawArguments)
            && !($rawArguments instanceof \Traversable)
        ) {
            throw new GetoptException('An array of strings is expected');
        }
        $this->rawArguments = $rawArguments;
        $this->parser = new Parser();
        $this->usageProvider = new UsageProvider();
    }

    /**
     * Get option value based on its definition
     *
     * @param \Yeriomin\Getopt\OptionDefinition $definition
     * @return mixed
     * @throws GetoptException
     */
    private function getOptionValue(OptionDefinition $definition)
    {
        $nameShort = $definition->getShort();
        $nameLong = $definition->getLong();
        $valueShort = $this->parser->getOptionShort($nameShort);
        $valueLong = $this->parser->getOptionLong($nameLong);
        if ($nameShort !== null && $nameLong !== null
            && $valueShort !== null && $valueLong !== null
            && $valueShort !== $valueLong
        ) {
            throw new GetoptException(
                'Both -' . $nameShort . ' and --' . $nameLong
                . ' given, with non-matching values. Make up your mind.'
            );
        }
        return $valueShort !== null ? $valueShort : $valueLong;
    }
}
