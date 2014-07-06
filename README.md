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
            "Cmf\\PublicResources\\Composer\\Script::installAssets"
        ],
        "post-update-cmd": [
            "Cmf\\Application\\Composer\\Script::installApp",
            "Cmf\\Application\\Composer\\Script::initEnvironment",
            "Cmf\\PublicResources\\Composer\\Script::installAssets"
        ]
    }
}
```

#### Use Composer

If you don't have Composer yet, download it following the instructions on
http://getcomposer.org/ or just run the following command:

    curl -s http://getcomposer.org/installer | php

Then, use the install command to generate a new Custom CMF application:

    COMPOSER_PROCESS_TIMEOUT=4000 ./composer.phar install

#### Add your database configuration

Edit  file **resources/config/ConfigInjection.Cmf-Db.cnf.xml** file

#### Create database for your project:

```sql
 CREATE DATABASE custom_cmf DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
```

#### Create database schema:

    bin/console orm:schema-tool:update --force

#### Import fixtures

mysql custom_cmf < misc/custom_cmf.sql


#### Web server config

nginx: https://github.com/itcreator/custom-cmf/blob/master/misc/nginx.config
apache2: https://github.com/itcreator/custom-cmf/blob/master/misc/apache2.conf

####Other

See Custom CMF example application https://github.com/itcreator/custom-cmf-example
