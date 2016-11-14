<?php

use Illuminate\Database\Seeder;

class PeopleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fid = DB::table('people')->insertGetId([
            'identifier' => 'BRSGRL81C03D205D',
            'firstname' => 'Gabriele',
            'surname' => 'Brosulo',
            'gender' => 'Male',
            'date_of_birth' => '1981-03-03',
            'birth_place' => 'Cuneo (CN)',
        ]);
        $mid = DB::table('people')->insertGetId([
            'identifier' => 'GRDLDI81T48L219E',
            'firstname' => 'Lidia',
            'surname' => 'Brosulo',
            'surname_at_birth' => 'Gardino',
            'gender' => 'Female',
            'date_of_birth' => '1981-12-08',
            'birth_place' => 'Torino (TO)',
        ]);
        $cid = DB::table('people')->insertGetId([
            'identifier' => 'BRSRCR16R16F351L',
            'firstname' => 'Riccardo',
            'surname' => 'Brosulo',
            'father_id' => $fid,
            'mother_id' => $mid,
            'gender' => 'Male',
            'date_of_birth' => '2016-10-16',
            'birth_place' => 'Mondoví (CN)',
        ]);
        
        // Genitori Gabo
        $fid = DB::table('people')->insertGetId([
            'firstname' => 'Ferdinando',
            'surname' => 'Brosulo',
            'gender' => 'Male',
            'birth_place' => 'Sanfront (CN)',
        ]);
        $mid = DB::table('people')->insertGetId([
            'identifier' => 'DNNMGR56P44L219B',
            'firstname' => 'Mariagrazia',
            'surname' => 'Danna',
            'gender' => 'Female',
            'date_of_birth' => '1956-09-04',
            'birth_place' => 'Torino (TO)',
        ]);
        
        DB::table('people')
            ->where('identifier', 'BRSGRL81C03D205D')
            ->update(['father_id' => $fid, 'mother_id' => $mid]);

        
        // Genitori Lily
        $fid = DB::table('people')->insertGetId([
            'firstname' => 'Alberto',
            'surname' => 'Gardino',
            'gender' => 'Male',
        ]);
        $mid = DB::table('people')->insertGetId([
            'firstname' => 'Anelise',
            'surname' => 'Nardo',
            'gender' => 'Female',
        ]);
        
        DB::table('people')
            ->where('identifier', 'GRDLDI81T48L219E')
            ->update(['father_id' => $fid, 'mother_id' => $mid]);
        
        
    }
}
