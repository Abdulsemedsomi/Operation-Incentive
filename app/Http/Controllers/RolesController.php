<?php

namespace App\Http\Controllers;

use App\Role;
use App\Role_user;
use Illuminate\Http\Request;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $input = $request->all();
        $role_name = $input['rolename'];
       // unset($input['rolename']);

        $keys = $request->keys();
        $permissions = [];
        foreach($keys as $key){
            if($key != '_token' && $key !='rolename'){
           $permissions[$key] = true;
            }
        }

        Role::create([
            'name' =>  $role_name,
            'slug' =>strtolower($role_name),
            'permissions' => json_encode($permissions),
        ]);
        return redirect()->back()
        ->with('successr', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $role = Role::find($id);
        return $role;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $input = $request->all();

        $role_name = $input['rolename'];
        // unset($input['rolename']);

         $keys = $request->keys();
         $permissions = [];
         foreach($keys as $key){
             if($key != '_token' && $key !='rolename' && $key !='_method'){

            $permissions[$key] = true;
             }
         }
         Role::where('id', $id)->update(
            ['name' =>  $role_name,
            'slug' =>strtolower($role_name),
            'permissions' => json_encode($permissions)]
        );

        return redirect()->back()
        ->with('successr', 'Role updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $role_users = Role_user::where("role_id", $id)->first();

        if($role_users){
       //
         return redirect()->back()
         ->with('errorr', 'Role can not be deleted. There is a user assigned this role.');
        }

         $role = Role::destroy($id);
         return redirect()->back()
         ->with('successr', 'Role deleted successfully');
    }
}
