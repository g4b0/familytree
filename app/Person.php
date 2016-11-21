<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use App\Classes\DataStructures\Graph;
use App\Classes\DataStructures\Node;
use App\Classes\DataStructures\GraphDirections;

class Person extends Model {

    protected $familygraph;

    public function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        $this->familygraph = null;
    }
    
//    marito = husband
//    moglie = wife
//    coniuge = spouse
//    bisnonno o bisavolo = great-grandfather
//    trisnonno o trisavolo = great-great-grandfather
//    cognata= sister in law
//    cognato=brother in law 
//    suocera= mother in law
//    suocero= father in law 
//    gemelli= twins 
//    madrina
//    padrino

    /**
     * Find person's father
     * 
     * @return Person
     */
    public function getFather() {
        return Person::find($this->father_id);
    }

    /**
     * Find person's mother
     * 
     * @return Person
     */
    public function getMother() {
        return Person::find($this->mother_id);
    }

    /**
     * Find person's parents
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getParents() {
        return Person::whereIn('id', [$this->father_id, $this->mother_id])
                        ->get();
    }
    
    /**
     * Find person's children
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getChildren() {
        return Person::where('father_id', '=', $this->id)
                        ->orWhere('mother_id', '=', $this->id)
                        ->get();
    }

    /**
     * Find person's brother and sisters
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getSiblings() {
        if (!($this->father_id > 0 && $this->mother_id > 0)) {
            return null;
        }

        $s = Person::where('id', '<>', $this->id);
        if ($this->father_id > 0) {
            $s->where('father_id', '=', $this->father_id);
        }
        if ($this->mother_id > 0) {
            $s->where('mother_id', '=', $this->mother_id);
        }
        return $s->get();
    }

    /**
     * Find person's grandfather and grandmother
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getGrandParents() {
        $gp = collect();
        foreach ($this->getParents() as $p) {
            $gp = $gp->merge($p->getParents());
        }
        return $gp;
    }

    /**
     * Find person's grandsons and granddaughter
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getGrandsons() {
        $ret = collect();

        $children = $this->getChildren();
        foreach ($children as $c) {
            $ret = $ret->merge($c->getChildren());
        }

        return $ret;
    }

    /**
     * Find person's uncles and aunts
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getUncles() {
        $u = collect();
        foreach ($this->getParents() as $p) {
            $u = $u->merge($p->getSiblings());
        }
        return $u;
    }

    /**
     * Find person's newphews and nieces
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getNephews() {
        $ret = collect();

        $siblings = $this->getSiblings();
        foreach ($siblings as $s) {
            $ret = $ret->merge($s->getChildren());
        }

        return $ret;
    }

    /**
     * Find person's ancestors recursively
     * 
     * @return \Illuminate\Support\Collection
     */
    public function getAncestors() {
        $ret = collect();

        $anc = $this->getParents();
        $ret = $ret->merge($anc);

        foreach ($anc as $p) {
            $ret = $ret->merge($p->getAncestors());
        }

        return $ret;
    }

    /**
     * Find the lowest common ancestor between this person and the one passed as
     * the first parameter
     * 
     * @param \App\Person $p
     * @return \App\Person
     */
    public function getLowestCommonAncestor(Person $p) {

        $a = collect([$this]);
        $a = $a->merge($this->getAncestors());
        $b = collect([$p]);
        $b = $b->merge($p->getAncestors());

        $common = $a->intersect($b);
        if ($common->isEmpty()) {
            return null;
        }

        // Find the lowest one
        $g = $this->getGraph();
        $hopNum = -1;
        $lca = null;
        foreach ($common as $p) {
            $dist = $this->getDistance($p);
            if ($hopNum === -1 || $dist < $hopNum) {
                $lca = $p;
                $hopNum = $dist;
            }
        }

        return $lca;
    }

    /**
     * Calculate the distance between this person and the one passed as the
     * first parameter
     * 
     * @param \App\Person $p
     * @return int
     */
    public function getDistance(Person $p) {
        $g = $this->getGraph();
        $path = $g->breadthFirstSearch($this->id, $p->id);
        return count($path) - 1;
    }

    /**
     * Calculate the cousin degree
     * 
     * @param \App\Person $p
     * @return int
     */
    public function getCousinDegree(Person $p) {
        $ret = 0;
        $lca = $this->getLowestCommonAncestor($p);

        if ($lca !== null) {
            $d1 = $this->getDistance($lca);
            $d2 = $p->getDistance($lca);


            if ($d1 >= 2 && $d2 >= 2) {
                // We are cousin!
                $ret = ($d1 - 2) + ($d2 - 2) + 1;
            }
        }

        return $ret;
    }

    /**
     * Find the persons's cousins, until the specified degree
     * 
     * @param int $maxDegree
     * @return \Illuminate\Support\Collection
     */
    public function getCousins($maxDegree = 6) {
        $ret = [];

        $g = $this->getGraph();
        $nodes = $g->getNodes();

        foreach ($nodes as $id => $n) {
            $p = Person::find($id);
            $d = $this->getCousinDegree($p);
            if ($d > 0 && $d <= $maxDegree) {
                $ret[$d][] = $p;
            }
        }

        return collect($ret);
    }

    /**
     * Build the familytree graph
     * 
     * @param int $liv deepht
     * @param Node $node initial node
     * @param Graph $graph initial graph
     * @return Graph
     */
    public function getGraph($liv = -1, Node $node = null, Graph $graph = null) {

        if ($liv === 0) {
            return;
        }

        if ($graph === null) {
            $graph = new Graph();
        }

        if ($node === null) {
            // Cache
            if ($this->familygraph !== null) {
                return $this->familygraph;
            }
            $node = new Node($this->id);
            Log::info("Creating graph for {$this->firstname}");
        }

        $graph->addNode($node);
        $person = Person::find($node->id);

        // Children
        foreach ($person->getChildren() as $c) {
            $cnode = new Node($c->id);
            $node->connectNode($cnode, GraphDirections::DOWN);
            if (!$graph->hasNode($cnode)) {
                $person->getGraph($liv - 1, $cnode, $graph);
            }
        }

        // Parents
        foreach ($person->getParents() as $p) {
            $pnode = new Node($p->id);
            $node->connectNode($pnode, GraphDirections::UP);
            if (!$graph->hasNode($pnode)) {
                $person->getGraph($liv - 1, $pnode, $graph);
            }
        }

        // Cache
        $this->familygraph = $graph;
        return $graph;
    }

}
