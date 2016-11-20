<?php

namespace App\Classes\DataStructures;

use App\Classes\DataStructures\GraphDirections;

/**
 * Description of Node
 *
 * @author g4b0
 */
class Node {

    public $id;
    public $connected_nodes = [];
    
    /**
     * Constructor
     * 
     * @param int $id unique identifier
     * @return Node $this
     */
    public function __construct($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * Connect two nodes. A is this node, B is the node to connect
     * 
     * @param \App\Classes\DataStructures\Node $n node to connect (B)
     * @param int $dir direction: 0 = no direction, 1 = A->B, -1 = B->A
     * @param int $weight arc weight
     * @return  Node $this
     */
    public function connectNode(Node $n, $dir = GraphDirections::BOTH, $weight = 0) {
        $this->connected_nodes[$n->id] = [
            'dir' => $dir,
            'weight' => $weight
        ];
        $n->connected_nodes[$this->id] = [
            'dir' => $dir * -1, 
            'weight' => $weight
        ];
        return $this;
    }
    
    public function getConnectedNodes() {
        return $this->connected_nodes;
    }
    
    public function getDirTo(Node $node) {
        return isset($this->connected_nodes[$node->id]) ? $this->connected_nodes[$node->id]['dir'] : 0;
    }

}
