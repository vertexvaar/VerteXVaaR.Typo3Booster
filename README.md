VerteXVaaR.Typo3Booster
=======================

## Introduction
Increases TYPO3 bootstrap performance by concatenating a lot of required classes into a single file.

## Basic Usage

THE INSTALLATION VIA COMPOSER IS MANDATORY!

CLI walkthrough:

```
composer require vertexvaar/typo3booster
./typo3/cli_dispatch.phpsh extbase extension:install typo3booster
./typo3/cli_dispatch.phpsh extbase booster:frontend
./typo3/cli_dispatch.phpsh extbase booster:backend
```

Other way:

Install via `composer require vertexvaar/typo3booster`
Activate `typo3booster` in the ExtensionManager
Create a scheduler tasks. One for `booster:frontend` and one for `booster:backend`

After a preload file is created it will increase your TYPO3 performace automagically ;)

## Command options

`--force` Overwrites an existing preload file, if it already exists

## In depth

Each class resides in a single file, so there is a lot of I/O and iteration necessary for autoloading all thes required classes.
The classpreloader remembers all the files included by a script via autoloading and concatenates them into a single file.
This file is then required at the beginning of each request, so the classes inside the file are already loaded and do not need to be searched for anymore.

There is a tradeoff between preloading and autoloading, see the [ClassPreloader](https://github.com/ClassPreloader/ClassPreloader/blob/2.0/README.md#notice) package for more information.
That's the reason why not all classes are included in the preload file.

THE PRELOAD FILE IS NOT GENERATED AUTOMATICALLY.
Read the topic [Automation](#automation) for more information. You have to run the specific command to generate this file.

## Automation

You can add a scheduler task to run the code generation of the preload file frequently.
A booster CommandController task without force will not generate a new file, so it's good for automatic generation after cache clearing

## Clear the cache

The preload files are deleted by clicking on the "Flush system caches" button in the upper toolbar of TYPO3.
They wont be generated again. You have to run (or wait for) the command to generate it.

## Found a bug? Feedback?

Some day i want to contribute this to the core, but not without some beta-testing of this concept.
Therefore i need a lot of feedback and suggestions.
Please report any bug or negative side effects, as well as your feedback, here https://github.com/vertexvaar/VerteXVaaR.Typo3Booster/issues

## Other stuff

Copyright & Author: Oliver Eglseder <php@vxvr.de>
License: GPL-2.0+
Supported by: in2code GmbH https://www.in2code.de
