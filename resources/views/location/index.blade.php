@extends('layouts.app')

@section('content-app')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="mb-3"><i class="fa fa-list"></i> Lokasi</h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:void(0)" class="btn btn-primary" id="add">
                        <i class="fa fa-plus"></i> Tambah
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
                                <th>Deskripsi</th>
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
                            <label for="description">Deskripsi</label>
                            <input type="text" name="description" id="description" class="form-control"
                                placeholder="Masukkan Deskripsi">
                        </div>

                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control" style="width: 100%">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
        const baseUrl = "{{ url('location') }}";
        let datatable;

        datatable = CrudUtils.initDataTable({
            tableId: "datatable",
            ajaxUrl: `${baseUrl}/data`,
            searchableFields: ['name'],
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
                    data: 'description',
                    orderable: false
                },
                {
                    data: 'status',
                    render: function(data, type, row) {
                        if (data == '1') {
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
                                    data-description="${row.description}"
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
            const id = $(this).data("id");

            $("#formTitle").text("Edit Data");
            $("#dataId").val(id);
            $("#name").val($(this).data("name"));
            $('#status').val($(this).data('status')).trigger('change');
            $('#description').val($(this).data('description'));

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
                description: $('#description').val(),
                status: $('#status').val()
            }),
            onSaved: () => {
                datatable.ajax.reload(null, false);
            },
        });
    </script>
@endsection
