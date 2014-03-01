<?php

class PermissionsTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permissions')->delete();

        //Permission 1
        $manageUsers = new Permission;
        $manageUsers->name = 'manage_users';
        $manageUsers->display_name = 'Manage Users';
        $manageUsers->save();
        

        //Permission 2
        $manageWidgets = new Permission;
        $manageWidgets->name = 'manage_carriers';
        $manageWidgets->display_name = 'Manage Carriers';
        $manageWidgets->save();

        DB::table('permission_role')->delete();

        $admin = Role::where('name','=','admin')->first();
        $user = Role::where('name','=','user')->first();

        $permissions = array(
            array(
                'role_id'      => $admin->id,
                'permission_id' => 1
            ),
            array(
                'role_id'      => $admin->id,
                'permission_id' => 2
            ),            
            array(
                'role_id'      => $user->id,
                'permission_id' => 2
            ),
        );

        DB::table('permission_role')->insert( $permissions );
    }

}
