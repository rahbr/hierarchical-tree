SELECT t1.name FROM
    category AS t1 LEFT JOIN category as t2
    ON t1.category_id = t2.parent
        WHERE t2.category_id IS NULL;

/*
+--------------+
| name         |
+--------------+
| TUBE         |
| LCD          |
| PLASMA       |
| FLASH        |
| CD PLAYERS   |
| 2 WAY RADIOS |
+--------------+
*/