# Introduction
Jenang2 is Yet Another PHP Framework utilizing Symfony components and others.

# How to use

First of all, make sure you have a worked composer installation.

    composer install

Copy .env.dist and rename it to .env

If your app has database fill DB_NAME in .env file

# How to run

To run your app,
    
    php -S localhost:8080 -t app/public


Or, if you use xampp and run from htdocs directory, you should change BASE_URL to fit yours.

For example, if your htdocs is in 'c:\xampp\htdocs' then BASE_URL should be,
    
    BASE_URL=/jenang2/app/public/


# TODO

Still incomplete, though.

- Logger (plan: use Monolog)
- Database Migration (plan: use Phinx)
- Cache (for future)
