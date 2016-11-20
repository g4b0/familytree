<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Classes\DataStructures\Graph;
use App\Classes\DataStructures\Node;
use App\Classes\DataStructures\GraphDirections;

class Person extends Model {

    public function getFather() {
        return Person::find($this->father_id);
    }

    public function getMother() {
        return Person::find($this->mother_id);
    }

    public function getParents() {
        return Person::whereIn('id', [$this->father_id, $this->mother_id])
                        ->get();
    }

    public function getGrandParents() {
        $gp = collect();
        foreach ($this->getParents() as $p) {
            $gp = $gp->merge($p->getParents());
        }
        return $gp;
    }

    public function getChildren() {
        return Person::where('father_id', '=', $this->id)
                        ->orWhere('mother_id', '=', $this->id)
                        ->get();
    }

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

    public function getUncles() {
        $u = collect();
        foreach ($this->getParents() as $p) {
            $u = $u->merge($p->getSiblings());
        }
        return $u;
    }

    public function getCousins($liv = 1) {
        $c = collect();
        foreach ($this->getUncles() as $u) {
            $c = $c->merge($u->getChildren());
        }

        $c2 = collect();
        foreach ($this->getParents() as $p) {
            $c2 = $c2->merge($p->getCousins());
        }

        return $c->merge($c2);
    }

    /**
     * Build the familytree graph
     * @param int $liv deepht
     * @param Node $node initial node
     * @param Graph $graph initial graph
     * @return Graph
     */
    public function getGraph($liv = -1, $node = null, $graph = null) {
        
        if ($liv === 0) {
            return;
        } 
        
        if ($graph === null) {
            $graph = new Graph();
        }

        if ($node === null) {
            $node = new Node($this->id);
        }

        $graph->addNode($node);        
        $person = Person::find($node->id);

        // Children
        foreach ($person->getChildren() as $c) {
            $cnode = new Node($c->id);
            $node->connectNode($cnode, GraphDirections::DOWN);
            if (!$graph->hasNode($cnode)) {
                $person->getGraph($liv-1, $cnode, $graph);
            }
        }

        // Parents
        foreach ($person->getParents() as $p) {
            $pnode = new Node($p->id);
            $node->connectNode($pnode, GraphDirections::UP);
            if (!$graph->hasNode($pnode)) {
                $person->getGraph($liv-1, $pnode, $graph);
            }
        }

        return $graph;
    }

//    madre = mother
//    padre = father
//    figlio= son
//    figlia= daughter
//    sorella= sister
//    fratello= brother
//    cugino = cousin
//    nipote maschio (x gli zii)= nephew
//    nipote maschio (x i nonni) = grandson
//    nipote femmina (x gli zii) = niece
//    nipote femmina (x i nonni) granddaughter
//    nonna=grandmother
//    nonno= grandfather 

    /**
     * Get the binary tree dimension (number of nodes)
     * starting from this person
     */
    public function treeDimension() {
        $dim = 1;
        $f = $this->getFather();
        if ($f !== null) {
            $dim += $f->treeDimension();
        }
        $m = $this->getMother();
        if ($m !== null) {
            $dim += $m->treeDimension();
        }
        return $dim;
    }

}
