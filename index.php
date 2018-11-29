<?php
require_once './vendor/autoload.php';

use jneto\hierachical_tree\Hierachy;

// https://www.phpro.org/tutorials/Managing-Hierarchical-Data-with-PHP-and-MySQL.html

/* * * a new hierachy instance ** */
$hierachy = new Hierachy;

$iterator = new RecursiveIteratorIterator(new recursiveArrayIterator($hierachy->fullTree('electronics')));

try {
    foreach ($iterator as $key => $value) {
        echo $value.'<br />';
    }
}
catch (Exception $e) {
    echo $e->getMessage();
}

// Finding all the Leaf Nodes
$iterator = new RecursiveIteratorIterator(new recursiveArrayIterator($hierachy->leafNodes()));
try {
    foreach ($iterator as $key => $value) {
        echo $value.'<br />';
    }
}
catch (Exception $e) {
    echo $e->getMessage();
}

// Finding the Depth of the Nodes

$iterator = new RecursiveIteratorIterator(new recursiveArrayIterator($hierachy->getNodeDepth()));
try {
    foreach ($iterator as $key => $value) {
        echo $key.' -- '.$value.'<br />';
    }
}
catch (Exception $e) {
    echo $e->getMessage();
}

// Depth of a Sub-Tree
$iterator = new RecursiveIteratorIterator(new recursiveArrayIterator($hierachy->subTreeDepth('portable electronics')));
try {
    foreach ($iterator as $key => $value) {
        echo $key.' -- '.$value.'<br />';
    }
}
catch (Exception $e) {
    echo $e->getMessage();
}

// Get Local Sub-Tree Depth
$iterator = new RecursiveIteratorIterator(new recursiveArrayIterator($hierachy->getLocalSubNodes('portable electronics')));
try {
    foreach ($iterator as $key => $value) {
        echo $key.' -- '.$value.'<br />';
    }
}
catch (Exception $e) {
    echo $e->getMessage();
}

// Product Count
$iterator = new RecursiveIteratorIterator(new recursiveArrayIterator($hierachy->productCount()));
try {
    foreach ($iterator as $key => $value) {
        echo $key.' -- '.$value.'<br />';
    }
}
catch (Exception $e) {
    echo $e->getMessage();
}

// Adding New Nodes
$hierachy->addNode('televisions', 'game consoles');
echo 'new node added';

// Adding a Child Node
$hierachy->addChildNode('2 way radios', 'uhf');
echo 'New Child Node Added';

// Deleting a node
$hierachy->deleteLeafNode('game consoles');

// Delete Node Recursive
$hierachy->deleteNodeRecursive('mp3 players');
