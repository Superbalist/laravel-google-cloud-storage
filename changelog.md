# Changelog

## 2.2.3 - 2020-03-16

* Add support for Laravel 7.x (@lucasmichot)

## 2.2.2 - 2019-09-26

* Add support for Laravel 6.x (@Mombuyish)

## 2.2.1 - 2019-04-18

* Fix #57: `ErrorException thrown with message "array_merge(): Argument #2 is not an array"` when defining service account

## 2.2.0 - 2019-04-15

* Allow users to authenticate to Google Cloud Storage directly with env variable without using a json file

## 2.1.2 - 2019-02-13

* Replace null coalescing operator with backwards compatible laravel `array_get`

## 2.1.1 - 2019-02-11

* Fix `Call to undefined method Superbalist\LaravelGoogleCloudStorage\GoogleCloudStorageServiceProvider::createCacheStore()` bug when using caching

## 2.1.0 - 2018-12-18

* This Allows optionally configuring `visibility`, `disable_asserts` and `url` options on the filesystem
* Add optional caching layer around the adapter

## 2.0.0 - 2018-02-07

* Allow superbalist/flysystem-google-storage ^7.0

## 1.0.5 - 2018-02-02

* Allow superbalist/flysystem-google-storage ^6.0

## 1.0.4 - 2017-05-19

* Allow for superbalist/flysystem-google-storage ^5.0

## 1.0.3 - 2017-05-19

* Add support for Laravel 5.1 (@jbaron-mx)

## 1.0.2 - 2017-01-03

* Allow for superbalist/flysystem-google-storage ^4.0

## 1.0.1 - 2016-11-29

* Add support for Laravel 5.2 (@Pierlo / @Pierre Gordon)

## 1.0.0 - 2016-09-27

* Initial release
