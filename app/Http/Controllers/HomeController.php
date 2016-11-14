<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Person;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gabo = Person::where('identifier', '=', 'BRSGRL81C03D205D')->first();
        $father = $gabo->getFather();
        $mother = $gabo->getMother();
        $grandfather = $father->getFather();
        
        $data = [
            'Gabo' => $gabo,
            'Father' => $father,
            'Mother' => $mother,
            'Grandfather' => $grandfather,
        ];
        
        //dd($gabo->getParents());
                
        return view('home', $data);
    }
}
