SELECT parent.name, COUNT(product.name)
FROM nested_category AS node ,
        nested_category AS parent,
        product
WHERE node.lft BETWEEN parent.lft AND parent.rgt
        AND node.category_id = product.category_id
GROUP BY parent.name
ORDER BY node.lft;

/*
+----------------------+---------------------+
| name                 | COUNT(product.name) |
+----------------------+---------------------+
| ELECTRONICS          |                  10 |
| TELEVISIONS          |                   5 |
| TUBE                 |                   2 |
| LCD                  |                   1 |
| PLASMA               |                   2 |
| PORTABLE ELECTRONICS |                   5 |
| MP3 PLAYERS          |                   2 |
| FLASH                |                   1 |
| CD PLAYERS           |                   2 |
| 2 WAY RADIOS         |                   1 |
+----------------------+---------------------+
*/