Food Orders Application
=======================

Welcome to Food Order Application, your best choice to make your organization and employees happy!

Getting started
===============
Prerequisites
-------------
* PHP >= 7.0
* Composer Installed
* Git Installed

Installation Guide
------------------
* Clone the repository

``` bash
  $ git clone https://github.com/ahmadelmalah/Learning-Project-Symfony-Restaurants-Orders.git
```
* Generate the SSH keys :

``` bash
$ mkdir var/jwt
$ openssl genrsa -out var/jwt/private.pem -aes256 4096
$ openssl rsa -pubout -in var/jwt/private.pem -out var/jwt/public.pem
```
* Install Dependencies

``` bash
  $ composer install
```
* Enjoy and make food orders :)

Project Software Architecture
=============================
Layered Architecture Pattern
-----------------------------
Layered 3-tier architecture is used on the project, MVC nature of symfony framework is leveraged to accomplish this approach

![alt tag](https://raw.githubusercontent.com/ahmadelmalah/Learning-Project-Symfony-Restaurants-Orders/dev/orderat/web/documentation/architecture-theo.png)

Architecture Implementation
---------------------------
* Presentation Layer: Twig
* Business Logic: Services
* Data Access Layer: Doctrine
* Database: MYSQL

![alt tag](https://raw.githubusercontent.com/ahmadelmalah/Learning-Project-Symfony-Restaurants-Orders/dev/orderat/web/documentation/architecture-practial.png)

Application Services
--------------------
**Restaurant Service** provides services regarding restaurants
* *create()* creates a new resturant object and saves it to database

**Order Service** provides services regarding orders
* *create()* creates a new order object and saves it to database

**Item Service** provides services regarding order items
* *create()* creates a new item object saves it to database

**Admin Service** provides services related to admin area

Releases Change log
===================
What’s new in version beta 0.2 (Let's make something shippable)
-------------------------------------
* Remember me feature
* Remember me admin protection (please re-login to access admin area)
* Unit Test
* Functional Test
* Project report
* UX optimizations: redirections, navigations and validations
* UI optimizations: admin area
* Exception Page

What’s new in version beta 0.3 (More attention to quality and performance)
--------------------------------------------------------------------------
**New Features**
* Applying AJAX on pagination
* Adding Javascript loader on Pagination

**PHP7 New Features Used**
* Constant arrays
* Group use declarations
* Object type hinting

**Bug Fixes**
* Fixing "$this" filtration bug

**Work Flow Optimizations**
* Git repository is now branched to distinguish development flow from deployment flow
* Git repository README.md is updated to include documentations regarding installation and change log

**Performance Optimizations**
* Admin counts are now cachable according to caching strategy
* Doctrine cache is used to handle counts values

**Code Base Optimizations**

*Readability Optimizations*

* Plain numbers are now replaced with descriptive-named constants(entity-related and service-related)
* All naming conventions were revisited
* All functions were revisited, now maximum service function is 10-line long

*Code Base Convention Optimizations*

* Services functions are now throwing exceptions instead of returning error codes
* Functions with the same names represent the same logic (e.g each entity-service has create() and save() functions)

*Utility Optimizations*

* New utility "Query Filter" is added to ease filtration
* APIUtility is updated to return views


Symfony Standard Edition
========================

Welcome to the Symfony Standard Edition - a fully-functional Symfony
application that you can use as the skeleton for your new applications.

For details on how to download and get started with Symfony, see the
[Installation][1] chapter of the Symfony Documentation.

What's inside?
--------------

The Symfony Standard Edition is configured with the following defaults:

  * An AppBundle you can use to start coding;

  * Twig as the only configured template engine;

  * Doctrine ORM/DBAL;

  * Swiftmailer;

  * Annotations enabled for everything.

It comes pre-configured with the following bundles:

  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][6] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][7] - Adds support for the Doctrine ORM

  * [**TwigBundle**][8] - Adds support for the Twig templating engine

  * [**SecurityBundle**][9] - Adds security by integrating Symfony's security
    component

  * [**SwiftmailerBundle**][10] - Adds support for Swiftmailer, a library for
    sending emails

  * [**MonologBundle**][11] - Adds support for Monolog, a logging library

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][13] (in dev/test env) - Adds code generation
    capabilities

  * **DebugBundle** (in dev/test env) - Adds Debug and VarDumper component
    integration

All libraries and bundles included in the Symfony Standard Edition are
released under the MIT or BSD license.

Enjoy!

[1]:  https://symfony.com/doc/3.2/setup.html
[6]:  https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
[7]:  https://symfony.com/doc/3.2/doctrine.html
[8]:  https://symfony.com/doc/3.2/templating.html
[9]:  https://symfony.com/doc/3.2/security.html
[10]: https://symfony.com/doc/3.2/email.html
[11]: https://symfony.com/doc/3.2/logging.html
[12]: https://symfony.com/doc/3.2/assetic/asset_management.html
[13]: https://symfony.com/doc/current/bundles/SensioGeneratorBundle/index.html
