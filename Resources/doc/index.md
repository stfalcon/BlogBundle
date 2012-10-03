Getting Started With BlogBundle
==================================

Simple small bundle for simple blogs

## Prerequisites

This version of the bundle requires:

1. Symfony >= 2.0
2. LiipFunctionalTestBundle for testing
3. DoctrineFixturesBundle for fixtures
4. SonataAdminBundle for administering
5. StofDoctrineExtensionsBundle for timestamps
6. KnpPaginatorBundle for automate pagination

## Installation

Installation is a quick 4 step process:

1. Add BlogBundle in your composer.json
2. Enable the Bundle
3. Import BlogBundle routing and update your config file
4. Update your database schema

### Step 1: Add BlogBundle in your composer.json

```js
{
    "require": {
        "stfalcon/blog-bundle": "*"
    }
}
```

### Step 2: Enable the bundle

Finally, enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Stfalcon\Bundle\BlogBundle\StfalconBlogBundle(),
    );
}
```

### Step 3: Import BlogBundle routing and update your config file

Now that you have installed and activated the bundle, all that is left to do is
import the StfalconBlogBundle and SonataAdminBundle routings:

In YAML:

``` yaml
# app/config/routing.yml
_stfalcon_blog:
    resource: "@StfalconBlogBundle/Resources/config/routing.yml"
    
admin:
    resource: '@SonataAdminBundle/Resources/config/routing/sonata_admin.xml'
    prefix: /admin

_sonata_admin:
    resource: .
    type: sonata_admin
    prefix: /admin
```

Add following lines to your config file:

In YAML:

``` yaml
# app/config/config.yml
# StfalconBlogBundle Configuration
stfalcon_blog:
    rss:
        title: "your-blog-title-goes-here"
        description: "your-blog-description-goes-here"

# Sonata Configuration
sonata_block:
    default_contexts: [cms]
    blocks:
        sonata.admin.block.admin_list:
            contexts:   [admin]

# DoctrineExtensionsBundle
stof_doctrine_extensions:
    default_locale: en_US
    orm:
        default:
            timestampable: true
```

### Step 4: Update your database schema

Now that the bundle is configured, the last thing you need to do is update your
database schema because you have added a two new entities, the `Post` and the `Tag`.

Run the following command.

``` bash
$ php app/console doctrine:schema:update --force
```
Now that you have completed the installation and configuration of the BlogBundle!