#getopt

[![Latest Version](https://img.shields.io/packagist/v/yeriomin/getopt.svg)](https://packagist.org/packages/yeriomin/getopt)
[![Build Status](https://travis-ci.org/yeriomin/getopt.svg?branch=master)](https://travis-ci.org/yeriomin/getopt)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/yeriomin/getopt/badges/quality-score.png)](https://scrutinizer-ci.com/g/yeriomin/getopt)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/fadf2986-e7b3-4e35-ac74-8ed3073fe2f6/mini.png)](https://insight.sensiolabs.com/projects/fadf2986-e7b3-4e35-ac74-8ed3073fe2f6)

A small PHP getopt helper/library. Provides a customizable input parameter interpretation and usage message generation.

## Usage

`$ composer require yeriomin/getopt`

```
<?php
include 'vendor/autoload.php';
$getopt = new \Yeriomin\Getopt\Getopt();
$arguments = $getopt->getArguments();
```

## Features

1. Obviously it parses command line options. Uses $_SERVER['argv'].
2. Short and long options, arguments.
3. Short options clustering.
4. Required options.
5. `--` argument separator.
6. Usage message generation.

## Detailed usage

### Arguments and options

Lets see how `getopt` deals with the following input:

`$ php somescript.php arg1 --option1 value1 -o value2 -abc -- --option3 value3`

#### Getting option value

```
echo $getopt->option1; // "value1"
echo $getopt->optionWhichIsNotProvided; // null
```

#### getArguments()

Returns an array of arguments. For the above example returns:

```
array(3) {
  [0] =>
  string(4) "arg1"
  [1] =>
  string(9) "--option3"
  [2] =>
  string(6) "value3"
}
```

#### getOptionsLong()

Returns an array of long options. For the above example returns:

```
array(1) {
  'option1' =>
  string(6) "value1"
}
```

#### getOptionsShort()
Returns an array of short options. For the above example returns:

```
array(4) {
  'o' =>
  string(6) "value2"
  'a' =>
  bool(true)
  'b' =>
  bool(true)
  'c' =>
  bool(true)
}
```

### Defining options

If you just want to get console arguments, you just need the three methods covered in the previous section. However giving `getopt` definitions of options you expect lets you define required options and get a usage message.

#### addOptionDefinition()

```
$optionDefinition = new \Yeriomin\Getopt\OptionDefinition(
    'c',
    'config',
    'Path to a configuration file'
);
$getopt->addOptionDefinition($optionDefinition);
```

Doing this defines -c|--config option. Providing -c populates --config and vice versa.

#### Required options

```
$optionDefinition = new \Yeriomin\Getopt\OptionDefinition(
    'c',
    'config',
    'Path to a configuration file',
    true
);
$getopt->addOptionDefinition($optionDefinition);
```

Forth argument in the `OptionDefinition` constructor makes option required. You can do the same with `$optionDefinition->setRequired()`. If any required option is not provided, getopt will throw an exception when `getArguments`, `getOptionsShort` or `getOptionsLong` are called.

#### getUsageMessage()

This method lets you get a usage message based on the options you have defined. Is looks somewhat like a man page.

```
<?php
include 'vendor/autoload.php';
$getopt = new \Yeriomin\Getopt\Getopt();
$optionConfig = new \Yeriomin\Getopt\OptionDefinition(
    'c',
    'config',
    'Path to a configuration file',
    true
);
$getopt->addOptionDefinition($optionConfig);
$optionHelp = new \Yeriomin\Getopt\OptionDefinition(
    'h',
    'help',
    'Show script help message',
);
$getopt->addOptionDefinition($optionHelp);
try {
    $configPath = $getopt->c;
} catch (\Yeriomin\Getopt\GetoptException $e) {
    echo $e->getMessage() . "\n";
    echo $getopt->getUsageMessage();
    exit(1);
}
```

Trying to run this script with no arguments would give us the following message:

```
Missing required options: -c/--config
Usage: somscript.php [OPTIONS] [ARGUMENTS]

Options:
 -c, --config Path to a configuration file
 -h, --help   Show script help message
```
