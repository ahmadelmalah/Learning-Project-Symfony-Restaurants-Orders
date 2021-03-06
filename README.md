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
* Presentation Layer: Twig (+ ES6 Template Literals)
* Business Logic: Services
* Data Access Layer: Doctrine
* Database: MYSQL

![alt tag](https://raw.githubusercontent.com/ahmadelmalah/Learning-Project-Symfony-Restaurants-Orders/dev/orderat/web/documentation/architecture-practial.png)

Application Services Description
--------------------------------
**Restaurant Service** provides services regarding restaurants
* *create()* creates a new resturant object and saves it to database

**Order Service** provides services regarding orders
* *create()* creates a new order object and saves it to database

* *makeReady()* makes order state ready
* *makeWaiting()* makes order state waiting
* *makeDelivered()* makes order state delivered
* *makeComplete()* makes order state complete

* *getOrders()* get orders according to specific criteria
* *getOrdersPaginated()* similar to *getOrders()* but returns paginated results


**Item Service** provides services regarding order items
* *create()* creates a new item object saves it to database

**Admin Service** provides services related to admin area
* *getTotal()* gets the total count of a certain entity
* *isCached()* checks if data is cached


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
* Applying AJAX on filtration
* Adding Javascript loader

**PHP7 New Features Used**
* Constant arrays
* Group use declarations
* Scalar type hinting

**Javascript ES6 New Features Used**
* Template literals

**Bugs, Vulnerabilities and validations Optimizations**
* Fixing "$this" filtration bug
* Fixing Symfony log warnings(Strings Translation)
* Redirecting logged in users if they try to open /login or /register
* Better handling for framework exceptions so users couldn't see the real exception
* Better handling for forms validations

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
* Javascript functions are grouped in separate .js files with suitable names

*Code Base Convention Optimizations*

* Services functions are now throwing exceptions instead of returning error codes
* Functions with the same names represent the same logic (e.g each entity-service has create() and save() functions)
* Object type hinting is used in Controllers and Services functions

*Utility Optimizations*

* New utility "Query Filter" is added to ease filtration
* APIUtility is updated to return views

What’s new in version beta 0.4
------------------------------
**Major Changes**
* Moving from knpPaginator to PaginatorUtility

**Performance Optimizations**
* The new paginator doesn't require fetching all data

**Code Base Optimizations**

*Code Base Optimizations*

* Creating ServiceDataPersistanceTrait to hold all similar functions shared between services
* All functions arguments are using type hinting including scalar types
* All functions are PHPDoc-style commented
