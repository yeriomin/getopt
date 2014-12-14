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
     * Get all long options
     *
     * @return array
     */
    public function getOptionsLong()
    {
        if (null === $this->optionsShort
            && null === $this->optionsLong
            && null === $this->arguments
        ) {
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
        if (null === $this->optionsShort
            && null === $this->optionsLong
            && null === $this->arguments
        ) {
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
        if (null === $this->optionsShort
            && null === $this->optionsLong
            && null === $this->arguments
        ) {
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
        $this->optionsShort = null;
        $this->optionsLong = null;
        $this->arguments = null;
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
        $this->arguments = $this->parser->getArguments();
        $optionsShort = $this->parser->getOptionsShort();
        $optionsLong = $this->parser->getOptionsLong();
        $missongRequired = array();
        foreach ($this->optionDefinitions as $definition) {
            /* @var $definition \Yeriomin\Getopt\OptionDefinition */
            $short = $definition->getShort();
            $long = $definition->getLong();
            if (null !== $short && isset($optionsShort[$short])
                && null !== $long && isset($optionsLong[$long])
                && $optionsShort[$short] !== $optionsLong[$long]
            ) {
                throw new GetoptException(
                    'Both -' . $short . ' and --' . $long
                    . ' given, with non-matching values. Make up your mind.'
                );
            } elseif (null !== $short && isset($optionsShort[$short])) {
                $optionsLong[$long] = $optionsShort[$short];
            } elseif (null !== $long && isset($optionsLong[$long])) {
                $optionsShort[$short] = $optionsLong[$long];
            }
            if ($definition->getIsRequired()
                && !isset($optionsShort[$short])
                && !isset($optionsLong[$long])
            ) {
                $parts = array();
                if (null !== $short) {
                    $parts[] = '-' . $short;
                }
                if (null !== $long) {
                    $parts[] = '--' . $long;
                }
                $missongRequired[] = implode('/', $parts);
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
        $type = strlen($option) === 1 ? 'optionsShort' : 'optionsLong';
        if (null === $this->$type) {
            $this->parse();
        }
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
}
