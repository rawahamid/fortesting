<?php

use App\Model\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->RoleAndPermission();
    }
    
        private $permissions = [
            'create post',
            'view post',
            'edit post',
            'delete post'
          
        /*     'create-branch', 
            'edit-branch', 
            'delete-branch', 
            'index-branch' */
        ];
        public function RoleAndPermission() {
            foreach ($this->permissions as $permission) {
                Permission::create(['name' => $permission]);
            }
            // Create Manager role and give permission
            Role::create(['name' => 'sys_admin'])->givePermissionTo(Permission::all());
            Role::create(['name' => 'author'])->givePermissionTo(['create post', 'view post', 'edit post','delete post']);
            Role::create(['name' => 'guest'])->givePermissionTo([ 'view post']);
           
                
            $superAdmin = User::create([
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => 'admin',
    
            ])->assignRole('sys_admin');
    
        }

        

}
