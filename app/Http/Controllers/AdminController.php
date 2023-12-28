<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\OrganisationType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index()
    {
        return view('administration.organisations.create');
    }

    public function create()
    {
        return view('administration.organisations.create');
    }

    public function template()
    {
        return view('administration.organisations.template');
    }

    public function manage()
    {
        return view('administration.organisations.index');
    }

    public function storeTemplate(Request $request)
    {

        //explode the parents into an array
        $parents = explode(',', $request->parent_id);

        $ids = [];

        //loop through each parent and create a new organisation
        foreach ($parents as $parent) {
            $organisation = new OrganisationType();
            $organisation->name = $request->name;
            $organisation->description = $request->description;
            $organisation->save();

            $ids[] = $organisation->id;

            if (!empty($parent)) {
                $organisation->parents()->sync($parent);
            }
        }


        return response()->json([
            'message' => 'Organisation template created successfully',
            'ids' => $ids
        ]);
    }

    public function addNewOrganisation(Request $request)
    {
        if ($request->type == 'ot') {
            $organization = new Organisation();
            $organization->organisation_type_id = $request->id;
            if ($request->parent != null) {
                $parts = explode('-', $request->parent);
                $lastPart = end($parts);
                $organization->organization_id = $lastPart;
            }
        } else {
            $organization = Organisation::findOrFail($request->id);
        }

        //first create the organisation
        $organization->name = $request->name;
        $organization->physical_address = $request->physical_address;
        $organization->contact_number = $request->contact_number;
        $organization->contact_email = $request->contact_email;
        $organization->contact_person = $request->contact_person;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/organisation/logos'), $filename);
            $organization->logo = $filename;
        }

        $organization->save();

        return response()->json($organization);
    }

    public function addOrganisationRole(Request $request)
    {
        $role = new Role();
        $role->name = $request->name;
        $role->organisation_id = $request->organisation_id;
        $role->save();
        return response()->json($role);

    }

    public function addOrganisationUser(Request $request)
    {
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'),
        ]);
        $user->save();

        //get the role and all permissions and return them
        $role = Role::findOrFail($request->role_id);

        //assign user to role
        $user->roles()->attach($role, ['organisation_id' => $request->organisation_id]);

        //db insert into organisation_users table
        DB::table('organisation_users')->insert([
            'user_id' => $user->id,
            'organisation_id' => $request->organisation_id,
            'role_id' => $role->id,
        ]);

        return response()->json($role);
    }
}
