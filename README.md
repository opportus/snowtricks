# Snowtricks [![SymfonyInsight](https://insight.symfony.com/projects/648e763e-751a-413e-9327-89bb416f83a3/big.svg)](https://insight.symfony.com/projects/648e763e-751a-413e-9327-89bb416f83a3)

Shool project #6 - A Symfony 4 application...

## Attached Resources

- [Demo](http://vps320850.ovh.net/snowtricks.com/public/index.php)
- [Diagrams](https://github.com/opportus/snowtricks/blob/master/conception/diagrams)
- [Specifications](https://github.com/opportus/snowtricks/blob/master/conception/specifications)
- [Wireframes](https://github.com/opportus/snowtricks/blob/master/conception/wireframes)
- [Fixtures](https://github.com/opportus/snowtricks/blob/master/src/DataFixtures)
- [Tickets](https://github.com/opportus/snowtricks/issues)
- [Task Board](https://github.com/opportus/snowtricks/projects/1)
- [Quality Test](https://insight.sensiolabs.com/projects/bb2ed0f1-32af-43ab-b550-efefdeb3cec6)
- [Setup](https://github.com/opportus/snowtricks/blob/master/README.md#installation)

## Installation

### Requirements

- PHP >= 7.1
- Git
- Composer

### Step 1 - Clone And Compose The Application

Clone the repository:
```shell
git clone https://github.com/opportus/snowtricks.git path/to/my/project
```

Change the working directory:
```shell
cd path/to/my/project
```

Install dependencies:
```shell
composer install
```

### Step 2 - Configure The Application

Configure Doctrine:

https://symfony.com/doc/current/doctrine.html#configuring-the-database

...

### Step 3 - Load Intitial Data To The Application Database

Create the database:
```shell
php bin/console doctrine:database:create
```

Load fixtures:
```shell
php bin/console doctrine:fixtures:load
```
