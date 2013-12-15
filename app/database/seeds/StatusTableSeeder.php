<?php

class StatusTableSeeder extends Seeder {

    public function run()
    {
        DB::table('status')->delete();


        $types = array(
            array(
                'name'          => 'Preparation',
                'order'         => 1,
                'description'   => 'The carrier is being prepared, including the addition of any supporting artifacts.',
                'created_at'    => date('Y-m-d H:i:s'),
            ),
            array(
                'name'          => 'Digitisation',
                'order'         => 2,
                'description'   => 'The carrier is being digitised.',
                'created_at'    => date('Y-m-d H:i:s'),
            ),
            array(
                'name'          => 'Complete',
                'order'         => 3,
                'description'   => 'Preparation and digitisation are complete, and the digital objects including all metadata have been bundled and sent for ingest into the catalogue.',
                'created_at'    => date('Y-m-d H:i:s'),
            )
        );

        DB::table('status')->insert( $types );
    }

}
