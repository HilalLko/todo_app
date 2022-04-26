<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\RoleHierarchy;

class UsersAndNotesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /* Create roles */
        $superRole = Role::create(['name' => 'super']); 
        RoleHierarchy::create([
            'role_id' => $superRole->id,
            'hierarchy' => 1,
        ]);
        $adminRole = Role::create(['name' => 'admin']); 
        RoleHierarchy::create([
            'role_id' => $adminRole->id,
            'hierarchy' => 2,
        ]);
        $userRole = Role::create(['name' => 'user']);
        RoleHierarchy::create([
            'role_id' => $userRole->id,
            'hierarchy' => 3,
        ]);
        $guestRole = Role::create(['name' => 'guest']); 
        RoleHierarchy::create([
            'role_id' => $guestRole->id,
            'hierarchy' => 4,
        ]);
        
        /*  insert status  */
        DB::table('status')->insert([
            'name' => 'ongoing',
            'class' => 'badge badge-pill badge-primary',
        ]);
        DB::table('status')->insert([
            'name' => 'stopped',
            'class' => 'badge badge-pill badge-secondary',
        ]);
        DB::table('status')->insert([
            'name' => 'completed',
            'class' => 'badge badge-pill badge-success',
        ]);
        DB::table('status')->insert([
            'name' => 'expired',
            'class' => 'badge badge-pill badge-warning',
        ]);
        /*  insert users   */
        $super = User::create([ 
            'name' => 'Hilal Super',
            'email' => 'hilal_rf+super@hotmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Super@123$'),
            'remember_token' => Str::random(10),
            'menuroles' => 'super' 
        ]);
        $super->assignRole('super');
        $user = User::create([ 
            'name' => 'Hilal Admin',
            'email' => 'hilal_rf+admin@hotmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Admin@123$'),
            'remember_token' => Str::random(10),
            'menuroles' => 'admin' 
        ]);
        $user->assignRole('admin');
        $normal1 = User::create([ 
            'name' => 'Normal User',
            'email' => 'normal@user.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'menuroles' => 'user' 
        ]);
        $normal1->assignRole('user');
        $normal2 = User::create([ 
            'name' => 'Normal User2',
            'email' => 'normal2@user.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'menuroles' => 'user' 
        ]);
        $normal2->assignRole('user');
    }
}