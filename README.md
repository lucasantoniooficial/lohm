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
php artisan lohm:new table Core\User
```

The `lohm:new` parameter takes two parameters, what to create and the name/path to it. In case we will create a new migration that is localized inside the Core folder and has the name of the user. As you will see (if you did not change the config of the package. Is that the table is actually a folder, called m-User. And inside is a PHP file called `0-1-0.php`. We don't save timestamps by default, but you can change that in the config.
Inside of the PHP file, you will find that it is pretty similar to the Laravel migration syntax, you can work as expected here.

### New version
Differently from Laravel, we keep a current version cache inside of the application, that keeps tracks of the fields saved in the database, so you don't need to write a new version every time you want to make a change. Instead, versions are used to give you complete control and vision of what is in the table and what is not.

``` bash
php artisan lohm:new version 0.1.1
```

When you run this command, a new file with the desired version will be added to every table inside of your migrations, and the contents of the last version will be added as a base to the new version.

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

## General thoughts
### Versionify vs unify
LOHM helps you keep your tables versionized, meaning that he will keep a version of every table inside your migrations. But in case you don't want this resource, you may disable it using `--unify`, it will convert the table into a PHP file instead of a directory containing it's versions.

## TODO
[] Add support for indexes
[] Add support for foreign keys
