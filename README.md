Zend Framework 2 Events Module
============================================
### Requirements
+ database 
+ PHP intl extension

### Installation

```sh
$ cd DIR_PROJECT
$ composer require pchela/events:dev-master
```

### Setup

Fill the specified db parameters

```sh
//DIR_PROJECT/config/autoload/local.php

return array(
    'doctrine' => array(
        'connection' => array(
            // default connection name
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host' => 'DBHOST', 
                    'port' => 'DBPORT',
                    'user' => 'DBUSER',
                    'password' => 'USERPASSWORD',
                    'dbname' => 'DBNAME',
                )
            )
        )
    ),
);

```

Enable modules in application.config.php
```
return array(
  'modules' => array(
      // other modules
        'DoctrineModule',
        'DoctrineORMModule',
        'Events',
  ),
  // other content
);
```

Create db schema 

```sh
$ cd DIR_PROJECT
$ php vendor/bin/doctrine-module orm:schema-tool:create

```

### Usage
http://your-domain.app/events
