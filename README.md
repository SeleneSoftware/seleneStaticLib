# Selene Static Builder

A rather small static site builder built from Twig and Markdown that isn't blog centric.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

Composer
npm/Yarn
PHP 7.2+

### Installing

A step by step series of examples that tell you how to get a development env running

Say what the step will be

```
composer create-project selenesoftware/seleneSite
```

## Running the tests

Explain how to run the automated tests for this system
I don't have tests for this.  It is so small, tests would seem larger than the actual code.  Don't want that.


### Code Styles

The code for the actuall builder and the skeleton are sniffed by the PHP CS Fixer (friendsofphp/php-cs-fixer).  There is a .php_cs.dist file that contains the rules usually run by Selene Software.  Feel free to adjust it to your own style guidelines.

```
php-cs-fixer fix
```

## Deployment

Once you run bin/app.php there will be files created in the 'web' directory.  Then run npm run build and you have production ready files.  Drop everything in the 'web' directory into your production environment.

## Built With

* [Twig](https://twig.symfony.com) - The rendering engine used
* [Composer](https://getcomposer.org) - Dependency Management
* [Symfony Encore](https://symfony.com/doc/current/frontend/encore/installation-no-flex.html) - Asset Bundling

## Contributing

Please read [CONTRIBUTING.md](https://gist.github.com/PurpleBooth/b24679402957c63ec426) for details on our code of conduct, and the process for submitting pull requests to us.

When submitting a pull request, please make sure you are submitting to the feature branch with the next version number.  If your pull request contains only documentation, please feel free to pull against master.

When submitting, please update the Authors section below.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags).  We also try to keep the skeleton the same version as the lib.

## Upgrading

We try to modify the skeleton as little as possible between versions, as plugins are built in the Application file.  But to upgrade the library, just update composer:
```
composer update
```

## Authors

* **Jason Marshall** - *Initial work* - [psion](https://github.com/psion)

## License

This project is licensed under the Apache License - see the [LICENSE-2.0.md](LICENSE-2.0.md) file for details

