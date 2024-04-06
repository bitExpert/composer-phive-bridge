# Composer-PHIVE Bridge

[![Build Status](https://github.com/bitExpert/composer-phive-bridge/workflows/ci/badge.svg?branch=master)](https://github.com/bitExpert/composer-phive-bridge/actions)
[![Mastodon Follow](https://img.shields.io/mastodon/follow/109408681246972700?domain=https://rheinneckar.social)](https://rheinneckar.social/@bitexpert)

Update the phive toolset on `composer update`

[PHIVE](https://phar.io) is a tool to distribute PHAR files which is ideal to distribute tools for your
build-process without having to worry about them influencing your dependencies.

Wait, what?

Well, usually whenever you require a development tool via `composer require --dev` that tool will influence your
dependency tree as all the dependencies of your tool now become dependencies of your project. So whenever you
require a lot of dev-dependencies that makes dependencies-resolving much more complicated as the dependencies of
those dev-dependencies suddenly need to match your projects dependencies as well. To circumvent that you can use
the phar-versions of the tools you are using. Those are like binary files that do not influence your dependencies
at all.

The tool to use for managing those PHAR files is [PHIVE](https://phar.io). PHIVE not only allows you to install
your CI tools without influencing your dependencies, it also comes with signature check on board so that you can be
absolutely sure that only tools with correct digital signatures (if available) are installed.

But now you have to handle 2 different tools to get your dependencies and your tools up-to-date.

This plugin tries to fix that by updating your PHIVE-installed tools everytime you do a `composer update`. So you now
only need to update one tool and the other one is updated automatically.

## Installation

Obviously this is installed using Composer.

```bash
composer require --dev bitexpert/composer-phive-bridge
```

## Usage

After the package is installed, there is nothing more you need to do. On the next `composer update` the plugin
will check for `phive` and if it is not installed, it will install `phive` in the current folder and then run
`phive install` to install all tools.

If you want to add a new tool, run `phive install <tool>` according to the [PHIVE documentation](https://phar.io)
