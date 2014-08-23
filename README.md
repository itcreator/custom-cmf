custom-cmf
==========

PHP CMF for developers

Installation guide
-------------

#### Create **composer.json**:

```json
{
    "name": "YourName",
    "require": {
        "php": ">=5.4.3",
        "custom-cmf/cmf": "dev-master"
    },
    "include-path": ["vendor/doctrine/orm/lib", "."],
    "minimum-stability": "dev",
    "scripts": {
        "post-install-cmd": [
            "Cmf\\Application\\Composer\\Script::installApp",
            "Cmf\\Application\\Composer\\Script::initEnvironment",
            "Cmf\\Application\\Composer\\Script::updateDb",
            "Cmf\\PublicResources\\Composer\\Script::installAssets"
        ],
        "post-update-cmd": [
            "Cmf\\Application\\Composer\\Script::installApp",
            "Cmf\\Application\\Composer\\Script::initEnvironment",
            "Cmf\\Application\\Composer\\Script::updateDb",
            "Cmf\\PublicResources\\Composer\\Script::installAssets"
        ]
    },
    "config": {
        "bin-dir": "bin"
    }
}
```

#### Use Composer

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Then, use the install command to generate a new Custom CMF application:

    COMPOSER_PROCESS_TIMEOUT=4000 ./composer.phar install

#### Web server

For running of the Custom CMF you can use built-in web server:

    bin/console server:start

You can also use other servers

nginx: https://github.com/itcreator/custom-cmf/blob/master/misc/nginx.config

apache2: https://github.com/itcreator/custom-cmf/blob/master/misc/apache2.conf

####Other

See Custom CMF example application https://github.com/itcreator/custom-cmf-example
