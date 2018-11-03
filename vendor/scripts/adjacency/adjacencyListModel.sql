http://mikehillyer.com/articles/managing-hierarchical-data-in-mysql/

CREATE TABLE category(
        category_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(20) NOT NULL,
        parent INT DEFAULT NULL
);

INSERT INTO category VALUES
    (1,'ELECTRONICS',NULL),
    (2,'TELEVISIONS',1),
    (3,'TUBE',2),
    (4,'LCD',2),
    (5,'PLASMA',2),
    (6,'PORTABLE ELECTRONICS',1),
    (7,'MP3 PLAYERS',6),
    (8,'FLASH',7),
    (9,'CD PLAYERS',6),
    (10,'2 WAY RADIOS',6);

SELECT * FROM category ORDER BY category_id;

/*
+-------------+----------------------+--------+
| category_id | name                 | parent |
+-------------+----------------------+--------+
|           1 | ELECTRONICS          |   NULL |
|           2 | TELEVISIONS          |      1 |
|           3 | TUBE                 |      2 |
|           4 | LCD                  |      2 |
|           5 | PLASMA               |      2 |
|           6 | PORTABLE ELECTRONICS |      1 |
|           7 | MP3 PLAYERS          |      6 |
|           8 | FLASH                |      7 |
|           9 | CD PLAYERS           |      6 |
|          10 | 2 WAY RADIOS         |      6 |
+-------------+----------------------+--------+
*/