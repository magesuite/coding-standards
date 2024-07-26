
# MageSuite Coding Standards

This module is meant to contain all coding standards using in MageSuite and its customization. It also contains scripts that should be used to check your module for specified coding standards.




## Installation

Install module with composer

```bash
composer require creativestyle/magesuite-coding-standards
```

## Usage/Examples

Run `pipeline` script which is installed in `vendor/bin` using your module as parameter
```bash
vendor/bin/pipeline <path-to-your-module>
```
Example
```bash
vendor/bin/pipeline vendor/creativestyle/magesuite-product-tile
```


## Features

Pipeline script is split into two separate ways of processing, depends of module type.

If module is marked as MageSuite (contains `magesuite` keyword in path) then it runs its jobs only for `git-diff` (changed lines only) of your changes. If the module is out of MageSuite package, then it runs for all files that contanins anychanges `git-diff`.

#### List of jobs:
- phpcs
- phpmd
- phpcpd 

