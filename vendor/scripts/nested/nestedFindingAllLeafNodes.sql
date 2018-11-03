SELECT name
FROM nested_category
WHERE rgt = lft + 1;

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