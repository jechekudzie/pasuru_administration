@extends('administration.layouts.admin')

@section('content')

    <h2 class="content-heading">Create Organisation Type Templates</h2>
    <div class="row">
        <div class="col-xl-4">
            <!-- Simple -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Add Organisation Type</h3>
                </div>
                <div class="block-content block-content-full">
                    <form id="saveOrgTypeForm" action="{{ url('/administration/templates/store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name"
                                       placeholder="Enter organization type name" required>
                                <label for="name">Name</label>
                            </div>
                        </div>
                        <div class="mb-4">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="description" name="description"
                                       placeholder="Enter organization type description">
                                <label for="description">Description</label>
                            </div>
                        </div>
                        <input type="hidden" name="parent_id" id="parent_id">
                        <div class="mb-4">
                            <button id="saveOrgType" class="btn btn-primary">Add Organization Type</button>
                        </div>
                    </form>
                </div>
            </div>
            <!-- END Simple -->
        </div>
        <div class="col-xl-8">
            <!-- With Icons -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Template Tree</h3>
                </div>
                <div class="block-content block-content-full">
                    <div id="tree"></div>
                </div>
            </div>
            <!-- END With Icons -->
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        //set up laravel ajax csrf token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        var tree = $('#tree').tree({
            primaryKey: 'id',
            dataSource: '/api/administration/organisations/templates',
            checkboxes: true,
            uiLibrary: 'bootstrap4',
            cascadeCheck: false,
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

    </script>
@endpush
