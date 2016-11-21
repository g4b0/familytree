<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;
use App\Classes\DataStructures\Graph;

class HomeController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $gabo = Person::where('identifier', '=', 'BRSGRL81C03D205D')->first();
        $nando = $gabo->getFather();
        $grazia = $gabo->getMother();
        $michele = $nando->getFather();

        $data = [
            'Gabo' => $gabo,
            'Father' => $nando,
            'Mother' => $grazia,
            'Grandfather' => $michele,
            'Path' => []
        ];

        //dd($gabo->treeDimension());
        //dd($gabo->getParents());
        //dd($gabo->getSiblings());
        //dd($gabo->getChildren());
        //dd($gabo->getGrandParents());
        //dd($gabo->getUncles());
        //dd($gabo->getAncestors());
        //dd($gabo->getCousins());
        //dd($gabo->getNephews());
        //dd($michele->getGrandsons());
        
        $graph = $gabo->getGraph();
        //dd($graph);



        $ricky = Person::where('identifier', '=', 'BRSRCR16R17F351N')->first();
        //dd($ricky->treeDimension());
        //dd($ricky->getUncles());
        //dd($ricky->getCousins());

        $cuggi = Person::where('firstname', '=', 'Andrea')
                ->where('surname', '=', 'Lombardo')
                ->first();
        
        $lorenzo = Person::where('firstname', '=', 'Lorenzo')
                ->where('surname', '=', 'Grosso')
                ->first();
        
        $lily = Person::where('firstname', '=', 'Lidia')
                ->where('surname', '=', 'Brosulo')
                ->first();
        
        $data['Path'][] = $this->getPathTxt($ricky, $cuggi, $graph);
        $data['Path'][] = $this->getPathTxt($gabo, $cuggi, $graph);
        $data['Path'][] = $this->getPathTxt($ricky, $lorenzo, $graph);
        $data['Path'][] = $this->getPathTxt($lily, $cuggi, $graph);
        //dd($data);
        
        //dd($gabo->getCousinDegree($cuggi));
        //dd($lily->getCousinDegree($cuggi));
        //dd($nando->getCousinDegree($cuggi));
        //dd($nando->getCousinDegree($lorenzo));
        //dd($ricky->getCousinDegree($lorenzo));

        return view('home', $data);
    }
    
    private function getPathTxt(Person $from, Person $to, Graph $graph) {
        $ret = '';
        
        $path = $graph->breadthFirstSearch($from->id, $to->id);
        if ($path !== null) {
            $hops = count($path) - 1;
            $ret .= 'Path: ';
            $prevNode = null;
            $tot = 0;
            foreach ($path as $nodeId) {
                
                $node = $graph->getNode($nodeId);
                if ($prevNode !== null) {
                    $dir = $prevNode->getDirTo($node);
                    $ret .= " ->[$dir] ";
                    $tot += $dir;
                }
                
                $person = Person::find($nodeId);
                $ret .= $person->firstname;
                
                $prevNode = $node;
            }
            $ret .= " tot: $tot [$hops]";
            
        } else {
            $ret .= "No path from {$from->firstname} to {$to->firstname}";
        }
        
        return $ret;
    }

}
