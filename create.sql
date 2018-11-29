/**
 * Author:  José Proença
 * Created: Nov 29, 2018
 */

CREATE TABLE categories (
    category_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL,
    left_node INT NOT NULL,
    right_node INT NOT NULL,
    PRIMARY KEY (category_id)
);

CREATE TABLE products (
    product_id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(40) DEFAULT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (product_id)
);



-- Dump test data --
/* categories */
INSERT INTO categories (category_id, name, left_node, right_node) VALUES 
(1, 'electronics', 1, 20),
(2, 'televisions', 2, 9),
(3, 'tube', 3, 4),
(4, 'lcd', 5, 6),
(5, 'plasma', 7, 8),
(6, 'portable electronics', 10, 19),
(7, 'mp3 players', 11, 14),
(8, 'flash', 12, 13),
(9, 'cd players', 15, 16),
(10, '2 way radios', 17, 18);

/* products */
INSERT INTO products (product_id, name, category_id) VALUES 
(1, '20" TV', 3),
(2, '36" TV', 3),
(3, 'Super-LCD 42"', 4),
(4, 'Ultra-Plasma 62"', 5),
(5, 'Value Plasma 38"', 5),
(6, 'Power-MP3 5gb', 7),
(7, 'Ipod 4gb', 8),
(8, 'Porta CD', 9),
(9, 'Walkman', 9),
(10,'Family Talk 360', 10);