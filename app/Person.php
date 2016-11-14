<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    public function getFather() {
        return Person::find($this->father_id);
    }
    
    public function getMother() {
        return Person::find($this->mother_id);
    }
    
    public function getParents() {
        return [
            'father' => $this->getFather(),
            'mother' => $this->getMother()
        ];
    }
}
