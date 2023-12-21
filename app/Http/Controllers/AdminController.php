<?php

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Models\OrganisationType;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
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
}
