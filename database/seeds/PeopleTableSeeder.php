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
        $gabo_id = DB::table('people')->insertGetId([
            'identifier' => 'BRSGRL81C03D205D',
            'firstname' => 'Gabriele',
            'surname' => 'Brosulo',
            'gender' => 'Male',
            'date_of_birth' => '1981-03-03',
            'birth_place' => 'Cuneo (CN)',
        ]);
        $lily_id = DB::table('people')->insertGetId([
            'identifier' => 'GRDLDI81T48L219E',
            'firstname' => 'Lidia',
            'surname' => 'Brosulo',
            'surname_at_birth' => 'Gardino',
            'gender' => 'Female',
            'date_of_birth' => '1981-12-08',
            'birth_place' => 'Torino (TO)',
        ]);
        $ricky_id = DB::table('people')->insertGetId([
            'identifier' => 'BRSRCR16R17F351N',
            'firstname' => 'Riccardo',
            'surname' => 'Brosulo',
            'father_id' => $gabo_id,
            'mother_id' => $lily_id,
            'gender' => 'Male',
            'date_of_birth' => '2016-10-16',
            'birth_place' => 'Mondoví (CN)',
        ]);
        
        // Genitori Gabo
        $nando_id = DB::table('people')->insertGetId([
            'firstname' => 'Ferdinando',
            'surname' => 'Brosulo',
            'gender' => 'Male',
            'birth_place' => 'Sanfront (CN)',
        ]);
        $grazia_id = DB::table('people')->insertGetId([
            'identifier' => 'DNNMGR56P44L219B',
            'firstname' => 'Mariagrazia',
            'surname' => 'Danna',
            'gender' => 'Female',
            'date_of_birth' => '1956-09-04',
            'birth_place' => 'Torino (TO)',
        ]);
        
        DB::table('people')
            ->where('identifier', 'BRSGRL81C03D205D')
            ->update(['father_id' => $nando_id, 'mother_id' => $grazia_id]);
        
        // Cristina
        $cry_id = DB::table('people')->insertGetId([
            'identifier' => 'BRSCST75T69C589N',
            'firstname' => 'Cristina',
            'surname' => 'Brosulo',
            'gender' => 'Female',
            'birth_place' => 'Ceva (CN)',
        ]);
        
        DB::table('people')
            ->where('identifier', 'BRSCST75T69C589N')
            ->update(['father_id' => $nando_id, 'mother_id' => $grazia_id]);
        
        // Lorenzo
        $lorenzo_id = DB::table('people')->insertGetId([
            'identifier' => 'LRNGSS09S19D205M',
            'firstname' => 'Lorenzo',
            'surname' => 'Grosso',
            'gender' => 'Male',
            'birth_place' => 'Cuneo (CN)',
        ]);
        
        DB::table('people')
            ->where('identifier', 'LRNGSS09S19D205M')
            ->update(['mother_id' => $cry_id]);
        
        // Genitori Lily
        $alberto_id = DB::table('people')->insertGetId([
            'firstname' => 'Alberto',
            'surname' => 'Gardino',
            'gender' => 'Male',
        ]);
        $anelise_id = DB::table('people')->insertGetId([
            'firstname' => 'Anelise',
            'surname' => 'Nardo',
            'gender' => 'Female',
        ]);
        
        DB::table('people')
            ->where('identifier', 'GRDLDI81T48L219E')
            ->update(['father_id' => $alberto_id, 'mother_id' => $anelise_id]);
        
        // Sara
        $sara_id = DB::table('people')->insertGetId([
            'firstname' => 'Sara',
            'surname' => 'Gardino',
            'gender' => 'Female',
        ]);
        
        DB::table('people')
            ->where('id', $sara_id)
            ->update(['father_id' => $alberto_id, 'mother_id' => $anelise_id]);
        
       
        // Sofia
        $sofia_id = DB::table('people')->insertGetId([
            'firstname' => 'Sofia',
            'surname' => 'Magnera',
            'gender' => 'Female',
        ]);
        
        DB::table('people')
            ->where('id', $sofia_id)
            ->update(['mother_id' => $sara_id]);
        
        // Zii paterni gabo
        $carla_id = DB::table('people')->insertGetId([
            'firstname' => 'Carla',
            'surname' => 'Brosulo',
            'gender' => 'Female',
        ]);
        
        $nani_id = DB::table('people')->insertGetId([
            'firstname' => 'Giovanni',
            'surname' => 'Brosulo',
            'gender' => 'Male',
        ]);
        
        // Nonni paterni gabo
        $michele_id = DB::table('people')->insertGetId([
            'firstname' => 'Michele',
            'surname' => 'Brosulo',
            'gender' => 'Male',
        ]);
        
        $mely_id = DB::table('people')->insertGetId([
            'firstname' => 'Mely',
            'surname' => 'Martino',
            'gender' => 'Female',
        ]);
        
        DB::table('people')
            ->whereIn('id', [$nando_id, $carla_id, $nani_id])
            ->update(['father_id' => $michele_id, 'mother_id' => $mely_id]);
        
        // Flo
        $flo_id = DB::table('people')->insertGetId([
            'firstname' => 'Flo',
            'surname' => 'Martino',
            'gender' => 'Female',
        ]);
        
        $bisnonno_mely_id = DB::table('people')->insertGetId([
            'firstname' => '??',
            'surname' => 'Martino',
            'gender' => 'Male',
        ]);
        
        $bisnonna_mely_id = DB::table('people')->insertGetId([
            'firstname' => '??',
            'surname' => '??',
            'gender' => 'Female',
        ]);
        
        DB::table('people')
            ->whereIn('id', [$flo_id, $mely_id])
            ->update(['father_id' => $bisnonno_mely_id, 'mother_id' => $bisnonna_mely_id]);
        
        // Giorgio e Giovanni Lombardo
        $giovanni_lombardo_id = DB::table('people')->insertGetId([
            'firstname' => 'Giovanni',
            'surname' => 'Lombardo',
            'gender' => 'Male',
        ]);
        
        $giorgio_lombardo_id = DB::table('people')->insertGetId([
            'firstname' => 'Giorgio',
            'surname' => 'Lombardo',
            'gender' => 'Male',
        ]);
        
        DB::table('people')
            ->whereIn('id', [$giovanni_lombardo_id, $giorgio_lombardo_id])
            ->update(['mother_id' => $flo_id]);
        
        // Andrea, Elia e Marta Lombardo
        $andrea_lombardo_id = DB::table('people')->insertGetId([
            'firstname' => 'Andrea',
            'surname' => 'Lombardo',
            'gender' => 'Male',
        ]);
        
        $elia_lombardo_id = DB::table('people')->insertGetId([
            'firstname' => 'Elia',
            'surname' => 'Lombardo',
            'gender' => 'Male',
        ]);
        
        $marta_lombardo_id = DB::table('people')->insertGetId([
            'firstname' => 'Marta',
            'surname' => 'Lombardo',
            'gender' => 'Female',
        ]);
        
        DB::table('people')
            ->whereIn('id', [$andrea_lombardo_id, $elia_lombardo_id, $marta_lombardo_id])
            ->update(['father_id' => $giovanni_lombardo_id]);
        
        // Nonni materni gabo
        $michele_id = DB::table('people')->insertGetId([
            'firstname' => 'Adolfo',
            'surname' => 'Danna',
            'gender' => 'Male',
        ]);
        
        $mely_id = DB::table('people')->insertGetId([
            'firstname' => 'Emiliana',
            'surname' => 'Tomatis',
            'gender' => 'Female',
        ]);
        
        DB::table('people')
            ->whereIn('id', [$grazia_id])
            ->update(['father_id' => $michele_id, 'mother_id' => $mely_id]);
    }
}
