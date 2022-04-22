<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class MenusTableSeeder extends Seeder
{
    private $menuId = null;
    private $dropdownId = array();
    private $dropdown = false;
    private $sequence = 1;
    private $joinData = array();
    private $superRole = null;
    private $adminRole = null;
    private $userRole = null;
    private $subFolder = '';

    public function join($roles, $menusId){
        $roles = explode(',', $roles);
        foreach($roles as $role){
            array_push($this->joinData, array('role_name' => $role, 'menus_id' => $menusId));
        }
    }

    /*
        Function assigns menu elements to roles
        Must by use on end of this seeder
    */
    public function joinAllByTransaction(){
        DB::beginTransaction();
        foreach($this->joinData as $data){
            DB::table('menu_role')->insert([
                'role_name' => $data['role_name'],
                'menus_id' => $data['menus_id'],
            ]);
        }
        DB::commit();
    }

    public function insertLink($roles, $name, $href, $icon = null){
        $href = $this->subFolder . $href;
        if($this->dropdown === false){
            DB::table('menus')->insert([
                'slug' => 'link',
                'name' => $name,
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'sequence' => $this->sequence
            ]);
        }else{
            DB::table('menus')->insert([
                'slug' => 'link',
                'name' => $name,
                'icon' => $icon,
                'href' => $href,
                'menu_id' => $this->menuId,
                'parent_id' => $this->dropdownId[count($this->dropdownId) - 1],
                'sequence' => $this->sequence
            ]);
        }
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        $permission = Permission::where('name', '=', $name)->get();
        if(empty($permission)){
            $permission = Permission::create(['name' => 'visit ' . $name]);
        }
        $roles = explode(',', $roles);
        if(in_array('user', $roles)){
            $this->userRole->givePermissionTo($permission);
        }
        if(in_array('admin', $roles)){
            $this->adminRole->givePermissionTo($permission);
        }
        if(in_array('super', $roles)){
            $this->adminRole->givePermissionTo($permission);
        }
        return $lastId;
    }

    public function insertTitle($roles, $name){
        DB::table('menus')->insert([
            'slug' => 'title',
            'name' => $name,
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence
        ]);
        $this->sequence++;
        $lastId = DB::getPdo()->lastInsertId();
        $this->join($roles, $lastId);
        return $lastId;
    }

    public function beginDropdown($roles, $name, $icon = ''){
        if(count($this->dropdownId)){
            $parentId = $this->dropdownId[count($this->dropdownId) - 1];
        }else{
            $parentId = null;
        }
        DB::table('menus')->insert([
            'slug' => 'dropdown',
            'name' => $name,
            'icon' => $icon,
            'menu_id' => $this->menuId,
            'sequence' => $this->sequence,
            'parent_id' => $parentId
        ]);
        $lastId = DB::getPdo()->lastInsertId();
        array_push($this->dropdownId, $lastId);
        $this->dropdown = true;
        $this->sequence++;
        $this->join($roles, $lastId);
        return $lastId;
    }

    public function endDropdown(){
        $this->dropdown = false;
        array_pop( $this->dropdownId );
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    { 
        /* Get roles */
        $this->superRole = Role::where('name' , '=' , 'super' )->first();
        $this->adminRole = Role::where('name' , '=' , 'admin' )->first();
        $this->userRole = Role::where('name', '=', 'user' )->first();
        /* Create Sidebar menu */
        DB::table('menulist')->insert([
            'name' => 'sidebar menu'
        ]);
        $this->menuId = DB::getPdo()->lastInsertId();  //set menuId
        $this->insertLink('user,admin,super', 'Dashboard', '/sitemaster/', 'cil-speedometer');
        $this->beginDropdown('admin,super', 'User Management', 'cil-user');    
            $this->insertLink('super,admin', 'Partially Registered','/sitemaster/partial-users');
            $this->insertLink('super,admin', 'Review Request','/sitemaster/review-requests');
            $this->insertLink('super,admin', 'Approved Registered','/sitemaster/users');
        $this->endDropdown();
        
        
        $this->beginDropdown('admin,super', 'Settings', 'cil-calculator');    
            $this->insertLink('super', 'Edit menu',               '/sitemaster/menu/menu');
            $this->insertLink('super', 'Edit menu elements',      '/sitemaster/menu/element');
            $this->insertLink('admin,super', 'Edit roles',              '/sitemaster/roles');
            $this->insertLink('admin,super', 'Media',                   '/sitemaster/media');
            $this->insertLink('admin,super', 'BREAD',                   '/sitemaster/bread');
            $this->insertLink('admin,super', 'Email',                   '/sitemaster/mail');
        $this->endDropdown();
        $this->insertLink('guest', 'Login', '/sitemaster/login', 'cil-account-logout');

        /* Create top menu */
        DB::table('menulist')->insert([
            'name' => 'top menu'
        ]);
        $this->menuId = DB::getPdo()->lastInsertId();  //set menuId
        $this->beginDropdown('guest,user,admin', 'Pages');
        $id = $this->insertLink('guest,user,admin', 'Dashboard',    '/');
        $id = $this->insertLink('user,admin,super', 'Notes',              '/sitemaster/notes');
        $id = $this->insertLink('admin,super', 'Users',                   '/sitemaster/users');
        $this->endDropdown();
        $id = $this->beginDropdown('admin,super', 'Settings');

        $id = $this->insertLink('super', 'Edit menu',               '/sitemaster/menu/menu');
        $id = $this->insertLink('super', 'Edit menu elements',      '/sitemaster/menu/element');
        $id = $this->insertLink('super', 'Edit roles',              '/sitemaster/roles');
        $id = $this->insertLink('admin/super', 'Media',                   '/sitemaster/media');
        $id = $this->insertLink('super', 'BREAD',                   '/sitemaster/bread');
        $this->endDropdown();

        $this->joinAllByTransaction(); ///   <===== Must by use on end of this seeder
    }
}