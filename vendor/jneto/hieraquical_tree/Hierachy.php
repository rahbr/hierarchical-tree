<?php

namespace jneto\hierachical_tree;

/**
 * Description of hierachy
 *
 * @author José Proença
 */
use jneto\hierachical_tree\conn;

class Hierachy
{

    /**
     *
     * @fetch the full tree
     * @param string $parent
     * @return array
     */
    public function fullTree($parent)
    {
        $stmt = conn::getInstance()->prepare(
            "SELECT node.name
                FROM categories AS node, categories AS parent
                WHERE
                    node.left_node BETWEEN parent.left_node AND parent.right_node
                    AND parent.name = :parent ORDER BY node.left_node");
        $stmt->bindParam('parent', $parent);
        $stmt->execute();
        $res  = $stmt->fetchALL(PDO::FETCH_ASSOC);
        return $res;
    }

    /**
     * Find all leaf nodes
     *
     * @access public
     * @return array
     */
    public function leafNodes()
    {
        $stmt = conn::getInstance()->prepare("SELECT name FROM categories WHERE right_node = left_node + 1");
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a single path
     *
     * @access public
     * @param $node_name
     * @return array
     */
    public function singlePath($node_name)
    {
        $stmt = conn::getInstance()->prepare("SELECT parent.name FROM categories AS node, categories AS parent WHERE node.left_node BETWEEN parent.left_node AND parent.right_node AND node.name = '{$node_name}' ORDER BY parent.left_node");
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a depth of nodes
     *
     * @access public
     * @param $node_name
     * @return array
     */
    public function getNodeDepth()
    {
        $stmt = conn::getInstance()->prepare("SELECT node.name, (COUNT(parent.name) - 1) AS depth FROM categories AS node, categories AS parent WHERE node.left_node BETWEEN parent.left_node AND parent.right_node GROUP BY node.name ORDER BY node.left_node");
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a subTree depth
     *
     * @access public
     * @param $node_name
     * @return array
     */
    public function subTreeDepth($node_name)
    {
        $stmt = conn::getInstance()->prepare("SELECT node.name, (COUNT(parent.name) - 1) AS depth FROM categories AS node, categories AS parent WHERE node.left_node BETWEEN parent.left_node AND parent.right_node AND node.name = '{$node_name}' GROUP BY node.name ORDER BY node.left_node");
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    /**
     * @fetch local sub nodes only
     *
     * @access public
     * @param $node_name
     * @return array
     */
    public function getLocalSubNodes($node_name)
    {
        $stmt = conn::getInstance()->prepare(" SELECT node.name, (COUNT(parent.name) - (sub_tree.depth + 1)) AS depth FROM categories AS node, categories AS parent, categories AS sub_parent,
        (
        SELECT node.name, (COUNT(parent.name) - 1) AS depth
        FROM categories AS node,
        categories AS parent
        WHERE node.left_node BETWEEN parent.left_node AND parent.right_node
        AND node.name = :node_name
        GROUP BY node.name
        ORDER BY node.left_node
        )AS sub_tree
WHERE node.left_node BETWEEN parent.left_node AND parent.right_node
AND node.left_node BETWEEN sub_parent.left_node AND sub_parent.right_node
AND sub_parent.name = sub_tree.name
GROUP BY node.name
HAVING depth <= 1
ORDER BY node.left_node");
        $stmt->bindParam(':node_name', $node_name, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    /**
     * @list categories and product count
     *
     * @access public
     * @return array
     */
    public function productCount()
    {
        $stmt = conn::getInstance()->prepare("SELECT parent.name, COUNT(products.name) AS product_count FROM categories AS node ,categories AS parent, products  WHERE node.left_node BETWEEN parent.left_node AND parent.right_node AND node.category_id = products.category_id GROUP BY parent.name ORDER BY node.left_node");
        $stmt->execute();
        return $stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    /**
     * @add a node
     *
     * @access public
     * @param string $left_node
     * @param string $new_node
     *
     */
    public function addNode($left_node, $new_node)
    {
        try {
            conn::getInstance()->beginTransaction();
            $stmt = conn::getInstance()->prepare("SELECT @myRight := right_node FROM categories WHERE name = :left_node");
            $stmt->bindParam(':left_node', $left_node);
            $stmt->execute();
            /*             * * increment the nodes by two ** */
            conn::getInstance()->exec("UPDATE categories SET right_node = right_node + 2 WHERE right_node > @myRight");
            conn::getInstance()->exec("UPDATE categories SET left_node = left_node + 2 WHERE left_node > @myRight");
            /*             * * insert the new node ** */
            $stmt = conn::getInstance()->prepare("INSERT INTO categories(name, left_node, right_node) VALUES(:new_node, @myRight + 1, @myRight + 2)");
            $stmt->bindParam(':new_node', $new_node);
            $stmt->execute();
            /*             * * commit the transaction ** */
            conn::getInstance()->commit();
        }
        catch (Exception $e) {
            conn::getInstance()->rollBack();
            throw new Exception($e);
        }
    }

    /**
     * @Add child node
     * @ adds a child to a node that has no children
     * @access public
     * @param string $node_name The node to add to
     * @param string $new_node The name of the new child node
     * @return array
     */
    public function addChildNode($node_name, $new_node)
    {
        try {
            conn::getInstance()->beginTransaction();
            $stmt = conn::getInstance()->prepare("SELECT @myLeft := left_node FROM categories WHERE name=:node_name");
            $stmt->bindParam(':node_name', $node_name);
            $stmt->execute();
            conn::getInstance()->exec("UPDATE categories SET right_node = right_node + 2 WHERE right_node > @myLeft");
            conn::getInstance()->exec("UPDATE categories SET left_node = left_node + 2 WHERE left_node > @myLeft");
            $stmt = conn::getInstance()->prepare("INSERT INTO categories(name, left_node, right_node) VALUES(:new_node, @myLeft + 1, @myLeft + 2)");
            $stmt->bindParam(':new_node', $new_node);
            $stmt->execute();
            conn::getInstance()->commit();
        }
        catch (Exception $e) {
            conn::getInstance()->rollBack();
            throw new Exception($e);
        }
    }

    /**
     *
     * @Delete a leaf node
     *
     * @param string $node_name
     *
     * @access public
     *
     */
    public function deleteLeafNode($node_name)
    {
        try {
            conn::getInstance()->beginTransaction();
            $stmt = conn::getInstance()->prepare("SELECT @myLeft := left_node, @myRight := right_node, @myWidth := right_node - left_node + 1 FROM categories WHERE name = :node_name");
            $stmt->bindParam(':node_name', $node_name);
            $stmt->execute();
            conn::getInstance()->exec("DELETE FROM categories WHERE left_node BETWEEN @myLeft AND @myRight;");
            conn::getInstance()->exec("UPDATE categories SET right_node = right_node - @myWidth WHERE right_node > @myRight");
            conn::getInstance()->exec("UPDATE categories SET left_node = left_node - @myWidth WHERE left_node > @myRight");
            conn::getInstance()->commit();
        }
        catch (Exception $e) {
            conn::getInstance()->rollBack();
            throw new Exception($e);
        }
    }

    /**
     *
     * @Delete a node and all its children
     *
     * @access public
     *
     * @param string $node_name
     *
     */
    public function deleteNodeRecursive($node_name)
    {
        try {
            conn::getInstance()->beginTransaction();
            $stmt = conn::getInstance()->prepare("SELECT @myLeft := left_node, @myRight := right_node, @myWidth := right_node - left_node + 1 FROM categories WHERE name = :node_name");
            $stmt->bindParam(':node_name', $node_name);
            $stmt->execute();
            conn::getInstance()->exec("DELETE FROM categories WHERE left_node BETWEEN @myLeft AND @myRight");
            conn::getInstance()->exec("UPDATE categories SET right_node = right_node - @myWidth WHERE right_node > @myRight");
            conn::getInstance()->exec("UPDATE categories SET left_node = left_node - @myWidth WHERE left_node > @myRight");
            conn::getInstance()->commit();
        }
        catch (Exception $e) {
            conn::getInstance()->rollBack();
            throw new Exception($e);
        }
    }
}