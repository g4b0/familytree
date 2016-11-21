<?php

namespace App\Classes\DataStructures;

use App\Classes\DataStructures\Node;
use SplQueue;

/**
 * Graph
 * 
 * A graph is a mathematical construct used to model the relationships between 
 * key/value pairs. A graph comprises a set of vertices (nodes) and an arbitrary
 * number of edges (lines) which connect them. These edges can be directed or 
 * undirected. A directed edge is simply an edge between two vertices, and edge
 * A->B is not considered the same as B->A. An undirected edge has no 
 * orientation or direction; edge A-B is equivalent to B-A. 
 * 
 * A tree structure which we learned about last time can be considered a type of
 * undirected graph, where each vertex is connected to at least one other vertex
 * by a simple path.
 * 
 * Graphs can also be weighted or unweighted. A weighted graph, or a network, is
 * one in which a weight or cost value is assigned to each of its edges. 
 * Weighted graphs are commonly used in determining the most optimal path, most
 * expedient, or the lowest "cost" path between two points. GoogleMap’s driving 
 * directions is an example that uses weighted graphs.
 * 
 * [https://www.sitepoint.com/data-structures-4/]
 *
 * @author g4b0
 */
class Graph {

    protected $nodes;

    public function __construct() {
        $this->nodes = [];
    }

    /**
     * Add a node to the graph
     * 
     * @param int $id unique identifier
     */
    public function addNode(Node $n) {
        $this->nodes[$n->id] = $n;
    }

    /**
     * True if the graph contain the node, false elsewhere
     * 
     * @param Node $n
     * @return boolean
     */
    public function hasNode(Node $n) {
        return array_key_exists($n->id, $this->nodes);
    }
    
    /**
     * Get the node
     * 
     * @param int $id
     * @return Node
     */
    public function getNode($id) {
        return isset($this->nodes[$id]) ? $this->nodes[$id] : null;
    }
    
    public function getNodes() {
        return $this->nodes;
    }
    
    /**
     * Count the number of nodes
     * 
     * @return int
     */
    public function count() {
        return count($this->nodes);
    }

    /**
     * Calculate the best path between two nodes
     * 
     * @param int $originId origin node id
     * @param int $destinationId destination node id
     * @return SplQueue hops between two nodes
     */
    public function breadthFirstSearch($originId, $destinationId) {
        $ret = null;
        $visited = [];
        
        // mark all nodes as unvisited
        foreach ($this->nodes as $vertex => $node) {
            $visited[$vertex] = false;
        }

        // create an empty queue, enqueue the origin vertex and mark as visited
        $q = new SplQueue();
        $q->enqueue($originId);
        $visited[$originId] = true;

        // Queue used to track the path back from each node
        $path = array();
        $path[$originId] = new SplQueue();
        $path[$originId]->push($originId);

        $found = false;
        while (!$q->isEmpty() && !$found) {
            $node = $q->dequeue();
            if ($node === $destinationId) {
                $found = true;
                break;
            } 
            
            foreach ($this->nodes[$node]->getConnectedNodes() as $vertex => $prop) {
                if ($visited[$vertex] === false) {
                    // if not yet visited, enqueue vertex and mark as visited
                    $q->enqueue($vertex);
                    $visited[$vertex] = true;
                    // add vertex to current path
                    $path[$vertex] = clone $path[$node];
                    $path[$vertex]->push($vertex);
                }
            }
            
        }
        
        if (isset($path[$destinationId])) {
            $ret = $path[$destinationId];
        } 
        
        return $ret;
    }

}
