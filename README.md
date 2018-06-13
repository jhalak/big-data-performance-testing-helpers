asAA/'
/##`Mysql performance testing helper scripts`

> Our goal was to test how mysql performs with or without applying various performance optimization technique for big data. 
For this we need a mysql table and a large number of rows in that table (i.e. 20 millions).
These helper scripts will do the basic task for us like, creating a mysql container, creating a database and 
a table with some fields, and a desirable number of rows in that table.  

The scripts are used to:

+ Create mysql container using a `Dockerfile`.
+ `create-table.php` -> creates mysql schema/table in the database.
+ `write-data-in-file.php` -> writes mock data as CSV form locally in a file containing huge number of row (e.g. 10M).
+ `insert-into-database-from-file.php` -> imports mock data from CSV file in the created table.
+ `insert-into-database.php` -> directly insert mock data in database.

#### How to:

``` 
docker-compose up -d
composer install
```

Then run various files using php command (e.g.): `php create-table.php` 
