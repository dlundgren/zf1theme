# Zend Framework 1 Theme manager extension

[![Travis CI](https://secure.travis-ci.org/dlundgren/zf1theme.png)](https://travis-ci.org/dlundgren/zf1theme) [![Code Climate](https://codeclimate.com/github/dlundgren/zf1theme/badges/gpa.svg)](https://codeclimate.com/github/dlundgren/zf1theme)

A way to manage themes for a Zend Framework 1 project.

The goal is to store all of the themes in one location to make it easier to locate views, layouts, helpers, and assets for a theme instead of them being located in multiple different directories

## Basic Usage

```php
$defProvider = new \ZfTheme\DefinitionProvider\Filesystem\ThemeJson(APPLICATION_PATH . '/themes');
$manager = new \ZfTheme\Manager($defProvider);

$theme = $manager->find('my-awesome-theme');

// The theme can now register itself with the view setting headLink, headScript, inlineScript or other variables on the view
$theme->registerWithView($view);
```

## Manager constructor

The manager constructor takes two arguments

- DefinitionProvider (either a single provider or an array of DefinitionProviders)

