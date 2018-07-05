<p><code><strong>Machine type :</strong></code>AWS mysql db.t2.medium</p>
<p><code><strong>Engine type  &nbsp;:</strong></code> MySQL 5.6.39</p>
<p><code><strong>Storage Type :</strong></code> General Purpose (SSD)</p>
<p><code><strong>Row count    &nbsp; &nbsp;:</strong></code> 20M</p>

#### SELECT with un-indexed column
<pre>
mysql> SELECT * FROM students WHERE id = 19999999;
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (0.01 sec)

mysql> SELECT COUNT(*) FROM students WHERE joinDate = "1986-10-18";
+----------+
| count(*) |
+----------+
|     1183 |
+----------+
1 row in set (4.07 sec)
</pre>

#### SELECT before indexing on `regNo`
<pre>
mysql> SELECT * FROM students WHERE regNo = 21365285423197;
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (7.17 sec)
</pre>

#### Creating INDEX on `regNo`
<pre>
mysql> CREATE INDEX idx_regNo ON students (regNo);
Query OK, 0 rows affected (1 min 24.82 sec)
Records: 0  Duplicates: 0  Warnings: 0
</pre>

#### SELECT after indexing
<pre>
mysql> SELECT * FROM students WHERE regNo = 21365285423197;
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (0.03 sec)
</pre>

#### SELECT before indexing on <code>joinDate</code> with <code>name</code>
<pre>
mysql> SELECT * FROM students WHERE name LIKE "Arne Bode DDS";
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (7.73 sec)

mysql> SELECT * FROM students WHERE address LIKE "1793 O'Keefe FieldsDeborahfurt, SC 85178-6867";
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (7.74 sec)
</pre> 

#### Creating index on <code>joinDate</code>
<pre>
mysql> CREATE INDEX idx_joinDate ON students (joinDate);
Query OK, 0 rows affected (1 min 12.24 sec)
Records: 0  Duplicates: 0  Warnings: 0
</pre>

#### SELECT after indexing on <code>joinDate</code> with <code>name</code>
<pre>
mysql> SELECT * FROM students WHERE name LIKE "Arne Bode DDS";
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (7.68 sec)
</pre>

##### SELECT with <code>joinDate</code> and <code>name</code> from un-indexed data
<pre>
mysql> SELECT * FROM students WHERE joinDate = "1993-01-01" AND `name` LIKE "Celia Bailey" LIMIT 10;
+-------+------------+--------------+----------------+------------------------------------------------+-------+
| id    | joinDate   | name         | regNo          | address                                        | phone |
+-------+------------+--------------+----------------+------------------------------------------------+-------+
| 51519 | 1993-01-01 | Celia Bailey | 68062737285180 | 31910 Rowe Pines Suite 307New Dahlia, NH 03904 | NULL  |
+-------+------------+--------------+----------------+------------------------------------------------+-------+
1 row in set (7 min 33.37 sec)
</pre>

##### Creating index on <code>joinDate</code> and <code>name</code>
<pre>
mysql> CREATE INDEX idx_joinDate_name ON students (`joinDate`, `name`);
Query OK, 0 rows affected (2 min 41.82 sec)
Records: 0  Duplicates: 0  Warnings: 0
</pre>

##### SELECT with <code>joinDate</code> and <code>name</code> from indexed data
<pre>
mysql> SELECT * FROM students WHERE joinDate = "1993-01-01" AND name LIKE "Celia Bailey";
+-------+------------+--------------+----------------+------------------------------------------------+-------+
| id    | joinDate   | name         | regNo          | address                                        | phone |
+-------+------------+--------------+----------------+------------------------------------------------+-------+
| 51519 | 1993-01-01 | Celia Bailey | 68062737285180 | 31910 Rowe Pines Suite 307New Dahlia, NH 03904 | NULL  |
+-------+------------+--------------+----------------+------------------------------------------------+-------+
1 row in set (0.00 sec)
</pre>

##### EXPLAIN SELECT with indexed columns (<code>joinDate + name</code>) in different way
<pre>
mysql> EXPLAIN SELECT * FROM students WHERE joinDate = "1993-01-01" limit 10 \G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: students
         type: ref
possible_keys: idx_joinDate_name
          key: idx_joinDate_name
      key_len: 3
          ref: const
         rows: 1229
        Extra: NULL
1 row in set (0.00 sec)

mysql> EXPLAIN SELECT * FROM students WHERE joinDate = "1993-01-01" AND name LIKE "Celia Bailey"\G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: students
         type: range
possible_keys: idx_joinDate_name
          key: idx_joinDate_name
      key_len: 770
          ref: NULL
         rows: 1
        Extra: Using index condition
1 row in set (0.00 sec)

mysql> EXPLAIN SELECT * FROM students WHERE name LIKE "Celia Bailey"\G
*************************** 1. row ***************************
           id: 1
  select_type: SIMPLE
        table: students
         type: ALL
possible_keys: NULL
          key: NULL
      key_len: NULL
          ref: NULL
         rows: 19337691
        Extra: Using where
1 row in set (0.00 sec)
</pre>

#### ORDER BY with non indexed column
<pre>
mysql> SELECT * FROM students ORDER BY 2 DESC LIMIT 5;
+----------+------------+----------------------+----------------+---------------------------------------------------------+-------+
| id       | joinDate   | name                 | regNo          | address                                                 | phone |
+----------+------------+----------------------+----------------+---------------------------------------------------------+-------+
| 19324416 | 2018-07-02 | Dr. Sidney Boyle Sr. | 45444459995372 | 98140 Kiehn Isle Suite 182Lake Jacynthe, NE 36792-7860  | NULL  |
| 18473984 | 2018-07-02 | Rudy Wilkinson       | 46520953514273 | 90915 Keira ViewsNorth Alice, ND 76105                  | NULL  |
| 17262337 | 2018-07-02 | Prof. Hayden Feest   | 20023806640166 | 10810 Runolfsdottir LocksPort Ethelyn, IN 67587         | NULL  |
|  7425538 | 2018-07-02 | Nadia Bogisich Sr.   | 96900016503225 | 161 Darrion Trail Apt. 005Karelletown, LA 33043         | NULL  |
| 12567554 | 2018-07-02 | Prof. Lavern Hoppe   | 85999859822758 | 94100 Crist Corner Apt. 698Ferminchester, HI 49292-6577 | NULL  |
+----------+------------+----------------------+----------------+---------------------------------------------------------+-------+
5 rows in set (7 min 39.66 sec)
</pre>

#### ORDER BY with indexed column
<pre>
mysql> SELECT * FROM students ORDER BY 2 DESC LIMIT 5;
+----------+------------+-------------------------+----------------+----------------------------------------------------------+-------+
| id       | joinDate   | name                    | regNo          | address                                                  | phone |
+----------+------------+-------------------------+----------------+----------------------------------------------------------+-------+
| 19997275 | 2018-07-02 | Robyn Dibbert           | 60258513675560 | 800 Lind DrivesMadisonburgh, MN 44350-8950               | NULL  |
| 19921081 | 2018-07-02 | Jaycee Hayes            | 28110928242456 | 90108 O'Hara Highway Suite 133Alishashire, IA 75341-3129 | NULL  |
| 19846576 | 2018-07-02 | Shemar Miller           | 42621613299340 | 81176 Muller SpurBellemouth, ME 68269-1327               | NULL  |
| 19742358 | 2018-07-02 | Prof. Jefferey Reynolds | 57582632761583 | 95140 Ruecker MountainGermanmouth, MS 43839              | NULL  |
| 19705240 | 2018-07-02 | Stewart Heaney DDS      | 95638570091013 | 362 Jayme Shore Apt. 050Port Daphneybury, DE 82522-3288  | NULL  |
+----------+------------+-------------------------+----------------+----------------------------------------------------------+-------+
5 rows in set (0.00 sec)
</pre>

#### UPDATE with un-indexed column
<pre>
mysql> UPDATE students SET address = "1993 O'Keefe FieldsDeborahfurt, SC 85178-6867" WHERE name LIKE "Arne Bode DDS";
Query OK, 1 row affected (11.50 sec)
Rows matched: 1  Changed: 1  Warnings: 0
</pre>
#### UPDATE with indexed column
<pre>
mysql> UPDATE students SET address = "2093 O'Keefe FieldsDeborahfurt, SC 85178-6867" WHERE regNo = 21365285423197;
Query OK, 1 row affected (0.00 sec)
Rows matched: 1  Changed: 1  Warnings: 0
</pre>

#### DELETE with un-indexed column
<pre>
mysql> DELETE FROM students WHERE name LIKE "Arne Bode DDS";
Query OK, 1 row affected (11.43 sec)
</pre>
#### DELETE with indexed column
<pre>
mysql> DELETE FROM students WHERE regNo = 86436269977492;
Query OK, 1 row affected (0.01 sec)
</pre>

### ALTER table with 20M raw
#### Add a new column to the table
<pre>
mysql> ALTER TABLE students add column phone char(50);
Query OK, 0 rows affected (9 min 34.69 sec)
Records: 0  Duplicates: 0  Warnings: 0
</pre>

#### Add a new <code>UNIQUE</code> key to the table
<pre>
mysql> ALTER TABLE students ADD CONSTRAINT idx_unique_regNo_joinDate UNIQUE (joinDate, regNo);
Query OK, 0 rows affected (1 min 54.91 sec)
Records: 0  Duplicates: 0  Warnings: 0
</pre>

#### Drop primary key and add another primary key with 2 fields
<pre>
mysql> ALTER TABLE students DROP PRIMARY KEY, ADD PRIMARY KEY (id, joinDate);
Query OK, 0 rows affected (11 min 8.90 sec)
Records: 0  Duplicates: 0  Warnings: 0

mysql> ALTER TABLE students
    -> PARTITION BY RANGE (YEAR(joinDate)) (
    -> PARTITION p1970l VALUES LESS THAN (1970),
    -> PARTITION p1980 VALUES LESS THAN (1980),
    -> PARTITION p1990 VALUES LESS THAN (1990),
    -> PARTITION p2000 VALUES LESS THAN (2000),
    -> PARTITION p2010 VALUES LESS THAN (2010),
    -> PARTITION p2020 VALUES LESS THAN (2020),
    -> PARTITION p2030 VALUES LESS THAN (2030),
    -> PARTITION p2040 VALUES LESS THAN (2040),
    -> PARTITION p2050 VALUES LESS THAN (2050),
    -> PARTITION p2050p VALUES LESS THAN MAXVALUE
    -> );
Query OK, 19999998 rows affected (9 min 39.41 sec)
Records: 19999998  Duplicates: 0  Warnings: 0
</pre>

### SELECT after partitioning based on join date
#### SELECT only with un-indexed column
<pre>
mysql> SELECT * FROM students WHERE name LIKE "Arne Bode DDS";
Empty set (19.52 sec)

mysql> SELECT * FROM students WHERE name LIKE "Edyth Stokes";
+----------+------------+--------------+----------------+--------------------------------------------------------------+-------+
| id       | joinDate   | name         | regNo          | address                                                      | phone |
+----------+------------+--------------+----------------+--------------------------------------------------------------+-------+
|  3510739 | 1976-01-14 | Edyth Stokes | 98793902582147 | 87044 Nova VillageArlofort, AL 17339-9796                    | NULL  |
......................
......................
+----------+------------+--------------+----------------+--------------------------------------------------------------+-------+
15 rows in set (17.02 sec)
</pre>

#### SELECT with un-indexed column but added with partitioned column that has index
<pre>
mysql> SELECT * FROM students WHERE name LIKE "Edyth Stokes" AND joinDate = "1976-01-14";
+---------+------------+--------------+----------------+-------------------------------------------+-------+
| id      | joinDate   | name         | regNo          | address                                   | phone |
+---------+------------+--------------+----------------+-------------------------------------------+-------+
| 3510739 | 1976-01-14 | Edyth Stokes | 98793902582147 | 87044 Nova VillageArlofort, AL 17339-9796 | NULL  |
+---------+------------+--------------+----------------+-------------------------------------------+-------+
1 row in set (0.10 sec)
</pre>

#### Dropping index from joining date
<pre>
mysql> ALTER TABLE students drop index idx_joinDate;
Query OK, 0 rows affected (0.41 sec)
Records: 0  Duplicates: 0  Warnings: 0
</pre>

#### SELECT with un-indexed column but added with partitioned column that has no index
<pre>
mysql> SELECT * FROM students WHERE name LIKE "Edyth Stokes" AND joinDate = "1976-01-14";
+---------+------------+--------------+----------------+-------------------------------------------+-------+
| id      | joinDate   | name         | regNo          | address                                   | phone |
+---------+------------+--------------+----------------+-------------------------------------------+-------+
| 3510739 | 1976-01-14 | Edyth Stokes | 98793902582147 | 87044 Nova VillageArlofort, AL 17339-9796 | NULL  |
+---------+------------+--------------+----------------+-------------------------------------------+-------+
1 row in set (4.10 sec)
</pre>

#### SELECT from partition
<pre>
mysql> SELECT * FROM students PARTITION (p2020) ORDER BY joinDate ASC LIMIT 2;
+----------+------------+---------------------+----------------+--------------------------------------------------+-------+
| id       | joinDate   | name                | regNo          | address                                          | phone |
+----------+------------+---------------------+----------------+--------------------------------------------------+-------+
| 19533825 | 2010-01-01 | Prof. Germaine Wiza | 93437916509231 | 29644 Parker PineNorth Mathias, DE 03648         | NULL  |
|   673281 | 2010-01-01 | Prof. Maxie Lesch   | 27766045318517 | 854 Norberto Camp Apt. 666New Charlene, ME 18455 | NULL  |
+----------+------------+---------------------+----------------+--------------------------------------------------+-------+
2 rows in set (0.63 sec)
</pre>