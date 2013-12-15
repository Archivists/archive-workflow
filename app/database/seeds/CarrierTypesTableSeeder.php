<?php

class CarrierTypesTableSeeder extends Seeder {

    public function run()
    {
        DB::table('carrier_types')->delete();


        $types = array(
            array(
                'name'          => 'Ampex 631 1/4" x 1200\', 7" Reel, 7.5ins',
                'description'   => 'Ampex Professional Audio Tape',
                'created_at'    => date('Y-m-d H:i:s'),
            ),
            array(
                'name'          => 'Audiotape 1/4" x 1200\', 7" Reel, 7.5ins',
                'description'   => 'Audio Devices Inc. Audiotape',
                'created_at'    => date('Y-m-d H:i:s'),
            ),
            array(
                'name'          => 'Scotch 3M 1/4 1/4" x 1200\', 7" Reel, 7.5ins',
                'description'   => 'Scotch 3M 206 Reel to Reel Tape',
                'created_at'    => date('Y-m-d H:i:s'),
            )
        );

        DB::table('carrier_types')->insert( $types );
    }

}
