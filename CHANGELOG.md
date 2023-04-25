# CHANGELOG

## [Unreleased]

## [1.7.4] - 2023-04-25
### Fixed
- Improved loading times

## [1.7.3] - 2022-11-11
### Fixed
- Fixed bug related to page timeouts

## [1.7.2] - 2022-11-04
### Fixed
- Fixed bug in fonts rendering

### Changed
- Replaced Travis CI with Github Actions
- Downgraded Node.js requirement

## [1.7.1] - 2022-09-27
### Fixed
- Fixed bug related to media types

## [1.7] - 2022-05-18
### Added
- Updated to PHP 8.1
- Updated to Node.js 18.0
- Updated to Puppeteer 13.7
- Updated all other dependencies to the latest versions
- Added a Dockerfile for development and testing

### Changed
- Dropped support for PHP < 8.0

## [1.6.1] - 2021-08-31
### Fixed
- Fixed bug related to escaping slashes in URLs

## [1.6.0] - 2020-12-10
### Added
- Adds support for Composer 2

## [1.5.2] - 2020-09-07
### Fixed
- Fixed bug with whitespaces in directory names

## [1.5.1] - 2020-09-01
### Fixed
- Fixed bug in download output handler

## [1.5.0] - 2020-06-05
### Changed
- Updated all dependencies to the latest versions
- Refactored JS code to ES6

## [1.4.1] - 2019-11-11
### Fixed
- NPM warning for invalid version

## [1.4] - 2019-09-24
### Added
- Added pageWaitFor option
- Added cookies option

### Changed
- Added --disable-web-security to get webfonts working

## [1.3.1] - 2019-03-07
### Fixed
- Fixed empty PDF bug

## [1.3] - 2019-02-26
### Changed
- Added support for Puppeteer 1.x

## [1.2.1] - 2018-07-31
### Changed
- The setNodePath method doesn't accept empty parameter anymore

## [1.2] - 2018-07-30
### Added
- Added method to manually set the path to the Node.js executable

## [1.1.1] - 2018-05-14
### Fixed
- Fixed bug in Converter when using root 

## [1.1] - 2018-04-17
### Added
- Added Windows support

## [1.0.1] - 2018-04-09
### Fixed
- Fixed broken install hook of NPM packages in Composer
- Fixed bug in example script in README

## [1.0] - 2018-04-04
### Added
- Initial stable release
- Added support for media type and viewport

### Changed
- Dropped the requirement for Node 7.6+, now requiring 6.4+

## [0.1] - 2018-01-08
### Added
- Initial alpha release
