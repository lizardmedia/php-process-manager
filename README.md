[![Build Status](https://scrutinizer-ci.com/g/lizardmedia/php-process-manager/badges/build.png?b=master)](https://scrutinizer-ci.com/g/lizardmedia/php-process-manager/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/lizardmedia/php-process-manager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/lizardmedia/php-process-manager/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/lizardmedia/php-process-manager/v/stable)](https://packagist.org/packages/lizardmedia/php-process-manager)
[![License](https://poser.pugx.org/lizardmedia/php-process-manager/license)](https://packagist.org/packages/lizardmedia/php-process-manager)

# PHP Process Manager #
PHP library for creating and managing PHP-processes.

## Getting started ##
This instructions will tell You how to get a copy of library and use it to manage processes

### Prerequsites ###
* PHP 7.0

### Installing ###
#### Using composer ####
Add to your project using composer:
```
composer require lizardmedia/php-process-manager
```

####Download ZIP ####
Download zip archive and unpack it to your project

## Usage ##

### CLI ###
Commands using Symfony console:
```$xslt
./bin/phppm-background {script to run} {log file}
```

Example:
```$xslt
./bin/phppm-background "example/ProcessExample.php" script.log > pid
```

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Michał Adamiak** - Lizard Media
* **Mateusz Procner** - Lizard Media

See also the list of [contributors](https://github.com/lizardmedia/php-process-manager/graphs/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

## To do

* Add killing and managing processes
