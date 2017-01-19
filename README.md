# Recover CPR Initiative
[![CircleCI](https://circleci.com/gh/dustinleblanc/veccs/tree/master.svg?style=svg)](https://circleci.com/gh/dustinleblanc/veccs/tree/master) [![SensioLabsInsight](https://insight.sensiolabs.com/projects/eda4584d-e7ae-4a0c-97f4-8ec284909625/mini.png)](https://insight.sensiolabs.com/projects/eda4584d-e7ae-4a0c-97f4-8ec284909625)

The Recover CPR initiative is a project of the Veterinary Emergency Critical Care Society. This project exists to assist in the process of evaluating academic literature around treatment of veterinary patients for emergency medical needs in order to foster a greater knowledge around effective treatments for these populations.
## Getting set up to develop:

1. Make sure you have [Composer](https://getcomposer.org/doc/00-intro.md) installed
2. Make sure you have Docker and [Docker-Compose](https://docs.docker.com/compose/) available. Docker for Mac runs super slow with Symfony/Drupal 8 apps so if developing on a Mac, consider using something like Dinghy to speed things up.
3. Run `composer setup` to copy the seed files into place.
4. Run `docker-compose up -d`
5. Optionally, if you need to include any local overrides, use a `docker-compose.override.yml` file to override them.
6. Interacting with Drupal is done from within the `php` or `testphp` containers: `docker-compose run php sh` will get you a bash shell inside the container. Drush can be run this way.
7. To run tests: `composer test`

## Tests

Currently a very limited Acceptance test suite exists with a handful of tests. The Test runner is [Codeception](http://codeception.com/) and we are using PhantomJS via WebDriver to allow for some Javascript/Ajax interaction. This should be added to and extended. PHP-VCR is installed but not yet working. This is particularly important for testing functionality of the Zotero Import module. Other tests should be added as time allows.

## Hosting

Hosting is currently on Pantheon for development and is expected for Production.

## CI and CD

CI and CD are setup through CircleCI. The entire test suite is run within the same Docker setup as development. CircleCI will deploy all passing commits to master to the development environment on Pantheon. Individual developers should not be deploying to Pantheon unless under extreme duress (hotfix for critical issue, etc).

Deployment scripts are all run through Robo, if the deployment needs to be modified, check the Robofile for modifications.

## General helpers

Dev scripts are written using the [Robo task runner](http://robo.li/). New scripts should be added to the `Robofile.php` file at the root of the repository as public methods. Some are already there, many of them outdated as the project has evolved.

## Issues / Stories
Issues/Stories are currently tracked through Github issues. The project is open source so there are currently no plans to make development planning private.
