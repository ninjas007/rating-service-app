@extends('layouts.app')

@section('content-app')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mb-3"><i class="fa fa-list"></i> User</h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:void(0)" class="btn btn-primary" id="add">
                        <i class="fa fa-plus"></i> Tambah User
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 table-responsive">
                    <table class="table table-hover table-bordered bg-light" id="datatable">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th width="8%" class="text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header text-white bg-primary">
                    <h5 class="modal-title text-white" id="formTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="text-white">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="dataForm">
                        <input type="hidden" id="dataId" />
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" id="name" required autocomplete="off" />
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
                        </div>


                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control" required autocomplete="off">
                                <option value="">-- Pilih Role --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role['id'] }}">
                                        {{ ucfirst($role['name']) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="active">Aktif</option>
                                <option value="inactive">Tidak Aktif</option>
                            </select>
                        </div>

                        {{-- password --}}
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required autocomplete="off">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="dataForm">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const baseUrl = "{{ url('users') }}";
        let datatable;

        datatable = CrudUtils.initDataTable({
            tableId: "datatable",
            ajaxUrl: `${baseUrl}/data`,
            searchableFields: ['name', 'role', 'username', 'email'],
            orders: [
                [1, "asc"]
            ],
            columns: [{
                    render: function(data, type, row, meta) {
                        return meta.row + meta.settings._iDisplayStart + 1;
                    },
                    className: "text-center no-sort",
                    orderable: false
                },
                {
                    data: 'name'
                },
                {
                    data: 'username'
                },
                {
                    data: 'email'
                },
                {
                    data: 'role'
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        if (data == 'active') {
                            return '<span class="badge badge-success text-white">Aktif</span>';
                        } else {
                            return '<span class="badge badge-danger text-white">Tidak Aktif</span>';
                        }
                    }
                },
                {
                    data: 'uid',
                    render: function(data, type, row) {
                        return `
                            <div class="action-icon-wrapper">
                                <i class="fa fa-pencil-square text-primary edit-btn"
                                    data-id="${data}"
                                    data-name="${row.name}"
                                    data-username="${row.username}"
                                    data-email="${row.email}"
                                    data-role="${row.role}"
                                    data-status="${row.status}"
                                    data-toggle="tooltip" data-placement="top"
                                    title="Edit">
                                </i>
                                <i class="fa fa-times-circle text-danger delete-btn"
                                    data-id="${data}"
                                    data-toggle="tooltip" data-placement="top"
                                    title="Delete"></i>
                            </div>`
                    },
                    orderable: false,
                    searchable: false,
                    className: "text-center"
                }
            ]
        });

        // Add
        $("#add").click(function() {
            CrudUtils.resetForm("dataForm", "formTitle", "Simpan Data", "dataId");
            $('#formModal').modal('show');
        });

        // Edit
        $(document).on("click", ".edit-btn", function() {
            // reset form
            CrudUtils.resetForm("dataForm", "formTitle", "Simpan Data", "dataId");

            const id = $(this).data("id");

            $("#formTitle").text("Edit Data");
            $("#dataId").val(id);
            $("#name").val($(this).data("name"));
            $("#username").val($(this).data("username"));
            $("#email").val($(this).data("email"));
            $("#role").val($(this).data("role")).trigger('change');
            $('#status').val($(this).data('status')).trigger('change');


            $('#formModal').modal('show');
        });

        // Delete
        $(document).on("click", ".delete-btn", function() {
            CrudUtils.deleteItem({
                url: `${baseUrl}/delete`,
                id: $(this).data("id"),
                onDeleted: () => datatable.ajax.reload(null, false)
            });
        });

        // Submit Create/Update
        CrudUtils.submitForm({
            formId: "dataForm",
            modalId: "formModal",
            createUrl: `${baseUrl}/store`,
            updateUrl: `${baseUrl}/update`,
            idFieldId: "dataId",
            getPayload: () => ({
                name: $("#name").val(),
                username: $("#username").val(),
                email: $("#email").val(),
                role: $("#role").val(),
                status: $('#status').val(),
                password: $('#password').val()
            }),
            onSaved: () => {
                datatable.ajax.reload(null, false);
            },
        });
    </script>
@endsection
