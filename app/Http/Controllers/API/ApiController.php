<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\OrganisationType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class ApiController extends Controller
{
    private $generatedNumbers = [];

    public function fetchTemplate()
    {
        $organisations = OrganisationType::whereDoesntHave('parents')->get();
        $data = $this->formatTreeData($organisations);
        return response()->json($data);
    }

    private function formatTreeData($organisations)
    {
        $data = [];

        foreach ($organisations as $organisation) {

            $data[] = [
                'id' => $organisation->id,
                'text' => $organisation->name,
                'children' => $this->formatTreeData($organisation->children),
            ];

        }
        return $data;
    }

    function generateUniqueNumber($min, $max)
    {
        $num = rand($min, $max);
        while (in_array($num, $this->generatedNumbers)) {
            $num = rand($min, $max);
        }
        $this->generatedNumbers[] = $num;
        return $num;
    }

    public function fetchOrganisationInstances()
    {
        $organisations = OrganisationType::whereDoesntHave('parents')->get();
        $data = [];

        foreach ($organisations as $organisation) {
            //random number
            $rand = $this->generateUniqueNumber(1, 1000000);

            $data[] = [
                'id' => $rand . '-ot-' . $organisation->id,
                'text' => $organisation->name,
                'children' => $this->formatOrganisationTreeData($organisation->organisations()->get()),
            ];
        }

        return response()->json($data);
    }

    private function formatOrganisationTreeData($organisations)
    {
        $data = [];

        foreach ($organisations as $organisation) {
            //random number
            $rand = $this->generateUniqueNumber(1, 1000000);

            if ($organisation instanceof Organisation) {

                //add the organisation id to the organizationType children array elements
                $organisation->organisationType->children->map(function ($item) use ($organisation) {
                    $item->organization_id = $organisation->id;
                });

                $data[] = [
                    'id' => $rand . '-o-' . $organisation->id,
                    'text' => $organisation->name,
                    'children' => $this->formatOrganisationTreeData($organisation->organisationType->children),
                ];
            } else {
                $data[] = [
                    'id' => $rand . '-ot-' . $organisation->id,
                    'text' => $organisation->name,
                    'children' => $this->formatOrganisationTreeData($organisation->organisations()->where('organisation_id', $organisation->organisation_id)->get()),
                ];
            }
        }
        return $data;
    }

    public function fetchOrganisationRoles($id, $type)
    {
        if ($type == 'ot') {
            return response()->json([]);
        } else {
            $data = DB::table('roles')->where('organisation_id', $id)->get();
            //add organisation name to the data collection as well
            $organisation = Organisation::findOrFail($id);
            $data->map(function ($item) use ($organisation) {
                $item->organisation_name = $organisation->name;
            });
            return response()->json($data);
        }
    }

    public function fetchOrganisationUsers($id, $type)
    {
        if ($type == 'ot') {
            return response()->json([]);
        } else {
            $data = DB::table('organisation_users')->where('organisation_id', $id)->get();
            $organisation = Organisation::findOrFail($id);
            $data->map(function ($item) use ($organisation) {
                $item->organisation = $organisation;
                $item->role = Role::findOrFail($item->role_id);
                $item->user = User::findOrFail($item->user_id);
            });
            return response()->json($data);
        }
    }

    public function fetchRolePermissions($id)
    {
        //get the role and all permissions and return them
        $role = Role::findOrFail($id);
        $permissions = $role->permissions;

        $moduleInfo = [
            ["module" => 'Dashboard', "prefix" => 'dashboard'],
            ["module" => 'Organisations', "prefix" => 'organizations'],
            ["module" => 'Users', "prefix" => 'users'],
            ["module" => 'Roles', "prefix" => 'roles'],
            ["module" => 'Permissions', "prefix" => 'permissions'],
            ["module" => 'Settings', "prefix" => 'settings'],
        ];

        //permissions suffixes
        $suffixes = ['create', 'view', 'update', 'delete'];

        //create list of all permissions by combining prefix and suffix
        foreach ($moduleInfo as $module) {
            foreach ($suffixes as $suffix) {
                $permissions[] = $module['prefix'] . '.' . $suffix;
            }
        }

        $data = [];
        //foreach module info add permissions array with corresponding key data
        foreach ($moduleInfo as $module) {
            $permissionsData = [];
            foreach ($suffixes as $suffix) {
                $permissionsData[$suffix] = $role->hasPermissionTo($module['prefix'] . '.' . $suffix);
            }
            $module['permissions'] = $permissionsData;
            $data[] = $module;
        }
        return response()->json($data);
    }

    public function updateRolePermissions(Request $request)
    {
        $role = Role::findOrFail($request->role_id);
        $request->checked == "true" ? $role->givePermissionTo($request->permission) : $role->revokePermissionTo($request->permission);
        return response()->json($request->permissions);
    }

    public function fetchRole($id)
    {
        $role = Role::findOrFail($id);
        $role->organization = Organisation::findOrFail($role->organization_id);
        return response()->json($role);
    }

    public function destroyUser($user, $organisation)
    {
        DB::table('organisation_users')->where('user_id', $user)->where('organisation_id', $organisation)->delete();
        User::findOrFail($user)->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function fetchOrganisationDetails($id)
    {
        $organisation = Organisation::findOrFail($id);
        $organisation->type = OrganisationType::findOrFail($organisation->organisation_type_id);
        return response()->json($organisation);
    }

}
