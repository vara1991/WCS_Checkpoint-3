WCS PHP Checkpoint
=====================
![](https://static.tvtropes.org/pmwiki/pub/images/potc_monocle2.jpg)

Launch your server and read the instructions.

Requirements
------------

  * Php ^7.2    http://php.net/manual/fr/install.php;
  * Composer    https://getcomposer.org/download/;

Installation
------------

1. Clone the current repository.

2. Create a branch correctly named as "CITY_LASTNAME_FIRSTNAME".

2. Move into the directory and create an `.env.local` file. 
**This one is not committed to the shared repository.**
Set `db_name` to **checkpoint3**.
 
3. Execute the following commands in your working folder to install the project:

```bash
$ composer install
$ bin/console d:d:c (create 'checkpoint3' DB)
$ bin/console d:m:m (execute migrations and create tables)
```
> Reminder: Don't use composer update to avoid problem

> Assets are directly into *public/* directory, **we will not use** Webpack with this checkpoint


Usage
-----

Launch the server with the command below and follow the instructions on the homepage `/`;

```bash
$ symfony server:start
```
