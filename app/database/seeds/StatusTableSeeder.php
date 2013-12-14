<?php

class StatusTableSeeder extends Seeder {

    public function run()
    {
        DB::table('status')->delete();


        $types = array(
            array(
                'name'          => 'Preparation',
                'description'   => 'The carrier is being prepared, including the addition of any supporting artifacts.',
            ),
            array(
                'name'          => 'Digitisation',
                'description'   => 'The carrier is being digitised.',
            ),
            array(
                'name'          => 'Complete',
                'description'   => 'Preparation and digitisation are complete, and the digital objects including all metadata have been bundled and sent for ingest into the catalogue.',
            )
        );

        DB::table('status')->insert( $types );
    }

}
