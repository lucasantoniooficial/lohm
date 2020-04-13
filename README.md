# Laravel overhauled migrations

> This package currently does NOT WORK!

This package aims to give another possible approach to migrations. Trying to give a more organized and controlled version of it. Right now it's not on a working state, I'm preparing everything for it. I just wanted to show how it will work and know what do you think of it.

## Installation
This is a typical installation Laravel package installation, you can run as follows:
``` bash
composer require aposoftworks/lohm
```
We require all the providers and facades automatically, so you don't need to worry.

## Usage

### New table
Just an advice, don't use Laravel vanilla migrations altogether with ours, this may cause some problems. But the first thing you should do is define a table migration:

``` bash
php artisan make:table Core\User
```

### Running migrations
If you want to run the migrations, simply run `lohm:migrate`, this will get the latest version of your migrations and run them. Since we keep in cache what is currently in the database, we compare the missing fields and make the changes accordingly. But beware that some operations can break the database, such as removing fields that are required for foreign keys, or adding values to foreign keys that don't match.

### Resetting cache
In case of any changes made directly into the database that will make our current cache "corrupt", you can run the command `lohm:recache`, the package will compare the current version to the database and fix the cache it has to match it.

## Configuration file
If you would like to change any configuration regarded to the package, you can publish it using:
``` bash
php artisan vendor:publish --tag=lohm-config
```
You can see that everything is pretty much configurable, file/directory names, cache options, so you can keep it to your taste.

## TODO
[] Add support for multiple indexes
[] Add support for removing fields that are not necessary anymore
[x] Add sync functionality
[x] Add support for indexes
[x] Add support for foreign keys
