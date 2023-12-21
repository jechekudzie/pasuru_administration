@extends('administration.layouts.admin')

@section('content')

    <h2 class="content-heading">Create Organisations</h2>
    <div class="row">
        <div class="col-xl-4">
            <!-- With Icons -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Template Instances</h3>
                </div>
                <div class="block-content block-content-full">
                    <div id="tree"></div>
                </div>
            </div>
            <!-- END With Icons -->
        </div>
        <div class="col-xl-8">

            <!-- Block Tabs Animated Fade -->
            <div class="block block-rounded overflow-hidden">
                <ul class="nav nav-tabs nav-tabs-block" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="btabs-animated-fade-home-tab" data-bs-toggle="tab"
                                data-bs-target="#btabs-animated-fade-home" role="tab"
                                aria-controls="btabs-animated-fade-home" aria-selected="true">Details
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="btabs-animated-fade-profile-tab" data-bs-toggle="tab"
                                data-bs-target="#btabs-animated-fade-profile" role="tab"
                                aria-controls="btabs-animated-fade-profile" aria-selected="false">Roles & Permissions
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="btabs-users-tab" data-bs-toggle="tab"
                                data-bs-target="#btabs-users" role="tab"
                                aria-controls="btabs-users" aria-selected="false">User Accounts
                        </button>
                    </li>

                </ul>
                <div class="block-content tab-content overflow-hidden">
                    <div class="tab-pane fade show active" id="btabs-animated-fade-home" role="tabpanel"
                         aria-labelledby="btabs-animated-fade-home-tab" tabindex="0">

                        <div class="block-content">
                            <form id="saveOrgInstanceForm"
                                  action="{{ url('/administration/organisations/instances/store') }}" method="POST"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="name" name="name"
                                                   placeholder="Enter organization name" required>
                                            <label class="form-label" for="name">Name</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="contact_person"
                                                   name="contact_person" placeholder="Enter contact person">
                                            <label class="form-label" for="contact_person">Contact Person</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="contact_number"
                                                   name="contact_number" placeholder="Enter contact number">
                                            <label class="form-label" for="contact_number">Contact Number</label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-floating">
                                            <input type="email" class="form-control" id="contact_email"
                                                   name="contact_email"
                                                   placeholder="Enter contact email">
                                            <label class="form-label" for="contact_email">Contact Email</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="physical_address"
                                               name="physical_address" placeholder="Enter physical address">
                                        <label class="form-label" for="physical_address">Physical Address</label>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label" for="example-file-input">Upload Company Logo</label>
                                    <input class="form-control" type="file" name="logo" id="example-file-input">
                                </div>
                                <input name="parent" type="hidden" id="parent_id">
                                <input name="id" type="hidden" id="id_id">
                                <input name="type" type="hidden" id="type_id">
                                <div class="mb-4">
                                    <button id="saveOrgInstance" type="submit" class="btn btn-primary">
                                        <i class="fa fa-plus opacity-50 me-1"></i> Add Organization
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="btabs-animated-fade-profile" role="tabpanel"
                         aria-labelledby="btabs-animated-fade-profile-tab" tabindex="0">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="block block-rounded">
                                    <div class="block-content">
                                        <!-- Form Labels on top - Default Style -->
                                        <form class="mb-5" action="{{ url('administration/organisations/role') }}"
                                              method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="form-label" for="example-ltf-email">Role Name</label>
                                                <input type="text" class="form-control" id="example-ltf-email"
                                                       name="name" placeholder="Super Admin">
                                            </div>
                                            <input type="hidden" name="organisation_id" id="role_organisation_id">
                                            <div class="mb-4">
                                                <button type="submit" class="btn btn-primary">Create Role</button>
                                            </div>
                                        </form>
                                        <!-- END Form Labels on top - Default Style -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="block block-rounded">
                                    <!-- Small Table -->
                                    <div class="block block-rounded">
                                        <div class="block-header block-header-default">
                                            <h3 class="block-title">Current Roles</h3>
                                        </div>

                                        <table class="table table-sm table-vcenter">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Role Name</th>
                                                <th class="d-none d-sm-table-cell">Permissions</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody id="rolesTable">

                                            </tbody>
                                        </table>

                                    </div>
                                    <!-- END Small Table -->
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="btabs-users" role="tabpanel"
                         aria-labelledby="btabs-users" tabindex="0">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="block block-rounded">
                                    <div class="block-content">
                                        <!-- Form Labels on top - Default Style -->
                                        <form class="mb-5" action="{{ url('administration/organisations/role') }}"
                                              method="POST">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="form-label" for="example-ltf-email">User Name</label>
                                                <input type="text" class="form-control" id="example-ltf-email"
                                                       name="name" placeholder="John Doe">
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label" for="example-ltf-email">Email Address</label>
                                                <input type="email" class="form-control" id="example-ltf-email"
                                                       name="email" placeholder="Enter email">
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label" for="example-ltf-email">Select Role</label>
                                                <select id="org-role-id" class="form-select"
                                                        name="role_id"></select>
                                            </div>
                                            <input type="hidden" name="organisation_id" id="role_organisation_id">
                                            <div class="mb-4">
                                                <button type="submit" class="btn btn-primary">Create Role</button>
                                            </div>
                                        </form>
                                        <!-- END Form Labels on top - Default Style -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="block block-rounded">
                                    <!-- Small Table -->
                                    <div class="block block-rounded">
                                        <div class="block-header block-header-default">
                                            <h3 class="block-title">Current Roles</h3>
                                        </div>

                                        <table class="table table-sm table-vcenter">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>User Name</th>
                                                <th class="d-none d-sm-table-cell">User Role</th>
                                                <th>Actions</th>
                                            </tr>
                                            </thead>
                                            <tbody id="rolesTable">

                                            </tbody>
                                        </table>

                                    </div>
                                    <!-- END Small Table -->
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <!-- END Block Tabs Animated Fade -->
        </div>
    </div>



    <!-- Large Modal -->
    <div class="modal" id="modal-large" tabindex="-1" role="dialog" aria-labelledby="modal-large" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="block block-rounded shadow-none mb-0">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">Set Role Permissions [ <span id="orgname"></span>&nbsp; - &nbsp;<span
                                id="orgrole"></span> ]</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content fs-sm">
                        <table class="table">
                            <thead class="table-dark">
                            <tr>
                                <th class="whitespace-nowrap">Module</th>
                                <th class="whitespace-nowrap">Create</th>
                                <th class="whitespace-nowrap">Read</th>
                                <th class="whitespace-nowrap">Update</th>
                                <th class="whitespace-nowrap">Delete</th>
                            </tr>
                            </thead>
                            <tbody id="pbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END Large Modal -->

@endsection

@push('scripts')
    <script>
        //set up laravel ajax csrf token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        let [rand, type, node_id] = [null, null, null];

        var tree = $('#tree').tree({
            primaryKey: 'id',
            dataSource: '/api/administration/organisations/instances',
            uiLibrary: 'bootstrap4',
            multiSelect: false,
            cascadeSelection: false,
            cascade: false,
            select: function (e, node, id) {
                var parentNode = $('#treeview').tree('getParent', id);
                var parentId = parentNode ? parentNode.id : null;
                [rand, type, node_id] = id.split('-');
                $('#type_id').val(type);
                $('#parent').val(parentId);
                $('#id_id').val(node_id);
                $('#role_organisation_id').val(node_id);
                fetchOrganizationRoles(node_id, type);
            }
        });

        $('#saveOrgType').click(function (event) {
            event.preventDefault();
            var result = tree.getCheckedNodes();
            $('#parent_id').val(result.join());

            //submit form with ajax
            $.ajax({
                url: '/administration/templates/store',
                type: 'POST',
                data: $('#saveOrgTypeForm').serialize(),
                success: function (response) {
                    //reet form
                    $('#saveOrgTypeForm').trigger('reset');
                    tree.reload();
                },
                error: function (response) {
                    console.log(response);
                }
            });

        });

        //save organisation instance with ajax
        document.getElementById('saveOrgInstanceForm').addEventListener('submit', function (event) {
            event.preventDefault();
            var formData = new FormData(this);
            $.ajax({
                url: '/administration/organisations/instances/store',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#saveOrgInstanceForm').trigger('reset');
                    tree.reload();
                },
                error: function (response) {
                    console.log(response);
                }
            });
        });

        function fetchOrganizationRoles(id, type) {
            $.ajax({
                url: '/api/administration/roles/' + id + '/' + type,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#rolesTable').empty();
                    $('#org-role-id').empty();
                    $('#user_organization_id').val(id);
                    $.each(data, function (index, role) {
                        $('#rolesTable').append(`<tr>
                        <td>${index + 1}</td>
                        <td>${role.name}</td>
                        <td>
                          <div class="btn-group">
                            <button type="button" data-role="${role.id}" data-orgname="${role.organisation_name}"
                                   data-rolename="${role.name}" class="btn btn-sm btn-dark configpermissions" data-bs-toggle="tooltip" title="Edit">
                              <i class="fa fa-key"></i>&nbsp;Configure
                            </button>
                          </div>
                        </td>
                        <td class="text-center">
                          <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Edit">
                              <i class="fa fa-pencil-alt"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="Delete">
                              <i class="fa fa-times"></i>
                            </button>
                          </div>
                        </td>
                    </tr>`);
                        $('#org-role-id').append(`<option value="${role.id}">${role.name}</option>`);
                    });
                }
            });
        }

        function fetchOrganizationUsers(id, type) {
            $.ajax({
                url: '/api/administration/users/' + id + '/' + type,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#user_organization_id').val(id);
                    $('#userAccountsTable').empty();
                    $.each(data, function (index, user) {
                        $('#userAccountsTable').append(`<tr>
                        <td>${index + 1}</td>
                        <td>${user.user.name}</td>
                        <td>${user.user.email}</td>
                        <td>${user.role.name}</td>
                        <td>
                            <a href="#">Revoke</a>
                        </td>`);
                    });
                }
            });
        }

        $(document).on('click', '.configpermissions', function () {
            $('#orgname').text($(this).data('orgname'));
            $('#orgrole').text($(this).data('rolename'));
            var roleid = $(this).data('role');
            $('#modal-large').modal('show');
            $.ajax({
                url: '/api/administration/permissions/' + roleid,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#permissions_slideover').find('tbody').empty();
                    $.each(data, function (index, permission) {
                        $('#permissions_slideover').find('tbody').append(`<tr>
                        <td>${permission.module}</td>
                        <td><input data-role="${roleid}" data-module="${permission.prefix}" data-permission="create" class="permission-checkbox" type="checkbox" ${permission.permissions.create ? 'checked' : ''}></td>
                        <td><input data-role="${roleid}" data-module="${permission.prefix}" data-permission="read" class="permission-checkbox" type="checkbox" ${permission.permissions.read ? 'checked' : ''}></td>
                        <td><input data-role="${roleid}" data-module="${permission.prefix}" data-permission="update" class="permission-checkbox" type="checkbox" ${permission.permissions.update ? 'checked' : ''}></td>
                        <td><input data-role="${roleid}" data-module="${permission.prefix}" data-permission="delete" class="permission-checkbox" type="checkbox" ${permission.permissions.delete ? 'checked' : ''}></td>class="permission-checkbox" type="checkbox" ${permission.permissions.delete ? 'checked' : ''} ></td>
                    </tr>`);
                    });
                }
            });
        });


    </script>
@endpush
