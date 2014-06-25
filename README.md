custom-cmf
==========

PHP CMF for developers

Installation guide
-------------

#### Create database for your project:

```sql
 CREATE DATABASE db_name DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;
```

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

#### Add your database configuration into the **resources/config/ConfigInjection.Cmf-Db.cnf.xml** file

#### Create database:

    bin/console orm:schema-tool:update --force

#### Run sql script misc/custom_cmf.sql


Other
-------------

See Custom CMF example application https://github.com/itcreator/custom-cmf-example
