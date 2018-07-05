<p><code><strong>Machine type :</strong></code>AWS mysql db.t2.medium</p>
<p><code><strong>Engine type  &nbsp;:</strong></code> MySQL 5.6.39</p>
<p><code><strong>Storage Type :</strong></code> General Purpose (SSD)</p>
<p><code><strong>Row count    &nbsp; &nbsp;:</strong></code> 20M</p>

#### SELECT with un-indexed column
<pre>
mysql> select * from students where id = 19999999;
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (0.01 sec)

mysql> select count(*) from students where joinDate = "1986-10-18";
+----------+
| count(*) |
+----------+
|     1183 |
+----------+
1 row in set (4.07 sec)

mysql> select * from students where regNo = 21365285423197;
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (7.17 sec)

mysql> select * from students where name like "Arne Bode DDS";
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (7.73 sec)

mysql> select * from students where address like "1793 O'Keefe FieldsDeborahfurt, SC 85178-6867";
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (7.74 sec)
</pre>

#### Creating index
<pre>
mysql> create index idx_regNo on students (regNo);
Query OK, 0 rows affected (1 min 24.82 sec)
Records: 0  Duplicates: 0  Warnings: 0

mysql> create index idx_joinDate on students (joinDate);
Query OK, 0 rows affected (1 min 12.24 sec)
Records: 0  Duplicates: 0  Warnings: 0
</pre>

#### SELECT with indexed data
<pre>
mysql> select * from students where regNo = 21365285423197;
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (0.03 sec)

mysql> select * from students where name like "Arne Bode DDS";
+----------+------------+---------------+----------------+-----------------------------------------------+
| id       | joinDate   | name          | regNo          | address                                       |
+----------+------------+---------------+----------------+-----------------------------------------------+
| 19999999 | 1986-10-18 | Arne Bode DDS | 21365285423197 | 1793 O'Keefe FieldsDeborahfurt, SC 85178-6867 |
+----------+------------+---------------+----------------+-----------------------------------------------+
1 row in set (7.68 sec)
</pre>

#### ORDER BY with non indexed column
<pre>
mysql> select * from students order by 2 desc limit 5;
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
mysql> select * from students order by 2 desc limit 5;
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
mysql> update students set address = "1993 O'Keefe FieldsDeborahfurt, SC 85178-6867" where name like "Arne Bode DDS";
Query OK, 1 row affected (11.50 sec)
Rows matched: 1  Changed: 1  Warnings: 0
</pre>
#### UPDATE with indexed column
<pre>
mysql> update students set address = "2093 O'Keefe FieldsDeborahfurt, SC 85178-6867" where regNo = 21365285423197;
Query OK, 1 row affected (0.00 sec)
Rows matched: 1  Changed: 1  Warnings: 0
</pre>

#### DELETE with un-indexed column
<pre>
mysql> delete from students where name like "Arne Bode DDS";
Query OK, 1 row affected (11.43 sec)
</pre>
#### DELETE with indexed column
<pre>
mysql> delete from students where regNo = 86436269977492;
Query OK, 1 row affected (0.01 sec)
</pre>

#### ALTER table with 20M raw
<pre>
mysql> alter table students add column phone char(50);
Query OK, 0 rows affected (9 min 34.69 sec)
Records: 0  Duplicates: 0  Warnings: 0

mysql> ALTER TABLE students
    -> ADD CONSTRAINT idx_unique_regNo_joinDate UNIQUE (joinDate, regNo);
Query OK, 0 rows affected (1 min 54.91 sec)
Records: 0  Duplicates: 0  Warnings: 0

mysql> create index idx_regNo on students (regNo);

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
mysql> select * from students where name like "Arne Bode DDS";
Empty set (19.52 sec)

mysql> select * from students where name like "Edyth Stokes";
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
mysql> select * from students where name like "Edyth Stokes" and joinDate = "1976-01-14";
+---------+------------+--------------+----------------+-------------------------------------------+-------+
| id      | joinDate   | name         | regNo          | address                                   | phone |
+---------+------------+--------------+----------------+-------------------------------------------+-------+
| 3510739 | 1976-01-14 | Edyth Stokes | 98793902582147 | 87044 Nova VillageArlofort, AL 17339-9796 | NULL  |
+---------+------------+--------------+----------------+-------------------------------------------+-------+
1 row in set (0.10 sec)
</pre>

#### Dropping index from joining date
<pre>
mysql> alter table students drop index idx_joinDate;
Query OK, 0 rows affected (0.41 sec)
Records: 0  Duplicates: 0  Warnings: 0
</pre>

#### SELECT with un-indexed column but added with partitioned column that has no index
<pre>
mysql> select * from students where name like "Edyth Stokes" and joinDate = "1976-01-14";
+---------+------------+--------------+----------------+-------------------------------------------+-------+
| id      | joinDate   | name         | regNo          | address                                   | phone |
+---------+------------+--------------+----------------+-------------------------------------------+-------+
| 3510739 | 1976-01-14 | Edyth Stokes | 98793902582147 | 87044 Nova VillageArlofort, AL 17339-9796 | NULL  |
+---------+------------+--------------+----------------+-------------------------------------------+-------+
1 row in set (4.10 sec)
</pre>

#### SELECT from partition
<pre>
mysql> select * from students PARTITION (p2020) order by joinDate asc limit 2;
+----------+------------+---------------------+----------------+--------------------------------------------------+-------+
| id       | joinDate   | name                | regNo          | address                                          | phone |
+----------+------------+---------------------+----------------+--------------------------------------------------+-------+
| 19533825 | 2010-01-01 | Prof. Germaine Wiza | 93437916509231 | 29644 Parker PineNorth Mathias, DE 03648         | NULL  |
|   673281 | 2010-01-01 | Prof. Maxie Lesch   | 27766045318517 | 854 Norberto Camp Apt. 666New Charlene, ME 18455 | NULL  |
+----------+------------+---------------------+----------------+--------------------------------------------------+-------+
2 rows in set (0.63 sec)
</pre>