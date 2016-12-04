<?php

namespace App\Http\Controllers;

use App\Person;
use App\Classes\DataStructures\GraphDirections;

/**
 * Description of ApiController
 *
 * @author g4b0
 */
class ApiController extends Controller {
    
    public function tree(Person $person) {
        $graph = $person->getGraph();
        $elements = [];
        foreach ($graph->getNodes() as $n) {
            $tmp = new \stdClass();
            $tmp->data = new \stdClass();
            $tmp->data->id = $n->id;
            
            $p = Person::find($n->id);
            $tmp->data->name = $p->firstname;
            $tmp->data->gender = $p->gender;
            
            $elements[] = $tmp;
            
            foreach ($n->connected_nodes as $id => $cn) {
                if ($cn['dir'] === GraphDirections::DOWN) {
                    $tmpEdge = new \stdClass();
                    $tmpEdge->data = new \stdClass();
                    $tmpEdge->data->id = $n->id . '-' . $id;
                    $tmpEdge->data->source = $n->id;
                    $tmpEdge->data->target = $id;
                    
                    $elements[] = $tmpEdge;
                    
                }
            }
            //dd($n);
            
        }
        //dd($elements);
        return json_encode($elements);
    }
}
