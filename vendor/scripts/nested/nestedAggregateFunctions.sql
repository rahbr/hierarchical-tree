CREATE TABLE product
(
        product_id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(40),
        category_id INT NOT NULL
);

INSERT INTO product(name, category_id) VALUES('20" TV',3),('36" TV',3),
('Super-LCD 42"',4),('Ultra-Plasma 62"',5),('Value Plasma 38"',5),
('Power-MP3 5gb',7),('Super-Player 1gb',8),('Porta CD',9),('CD To go!',9),
('Family Talk 360',10);

SELECT * FROM product;

/*
+------------+-------------------+-------------+
| product_id | name              | category_id |
+------------+-------------------+-------------+
|          1 | 20" TV            |           3 |
|          2 | 36" TV            |           3 |
|          3 | Super-LCD 42"     |           4 |
|          4 | Ultra-Plasma 62"  |           5 |
|          5 | Value Plasma 38"  |           5 |
|          6 | Power-MP3 128mb   |           7 |
|          7 | Super-Shuffle 1gb |           8 |
|          8 | Porta CD          |           9 |
|          9 | CD To go!         |           9 |
|         10 | Family Talk 360   |          10 |
+------------+-------------------+-------------+
*/