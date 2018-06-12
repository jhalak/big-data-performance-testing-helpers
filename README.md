## Mysql performance testing helper PHP scripts

The scripts are used to:

+ Create mysql container using a `Dockerfile`.
+ `create-table.php` -> creates mysql schema/table in the database.
+ `write-data-in-file.php` -> writes mock data as CSV form locally in a file containing huge number of row (e.g. 10M).
+ `insert-into-database-from-file.pho` -> imports mock data from CSV file in the created table.
+ `insert-into-database.php` -> directly insert mock data in database.
