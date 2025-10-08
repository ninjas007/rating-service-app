@extends('layouts.app')

@section('css')
    <style>
        textarea {
            height: 100px !important;
        }
    </style>
@endsection

@section('content-app')
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <h4><i class="fa fa-list"></i> List Template</h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="javascript:void(0)" class="btn btn-primary" id="add">
                        <i class="fa fa-plus"></i> Tambah
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-light" id="datatable">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th width="5%" class="text-center">No</th>
                            <th>Nama</th>
                            <th>Gambar Background</th>
                            <th>Text Berjalan</th>
                            <th>Status</th>
                            <th class="text-center" width="10%">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-fullscreen" role="document">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="formTitle"></h5>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="dataForm" enctype="multipart/form-data">
                        <input type="hidden" id="dataId" />

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Nama Template</label>
                                    <input type="text" class="form-control" name="name" id="name" required />
                                </div>

                                <div class="form-group">
                                    <label for="running_text">Text Berjalan</label>
                                    <textarea class="form-control" name="running_text" id="running_text" rows="2" required>Terima kasih atas kunjungannya</textarea>
                                </div>

                                <div class="form-group">
                                    <label for="running_text_color">Warna Text Berjalan</label>
                                    <input type="color" class="form-control" name="running_text_color"
                                        id="running_text_color" required />
                                </div>

                                <div class="form-group">
                                    <label for="bg_running_text">Warna Background Text Berjalan</label>
                                    <input type="color" class="form-control" name="bg_running_text" id="bg_running_text"
                                        value="#ffffff" required />
                                </div>

                                <div class="form-group">
                                    <label for="running_text_speed">Kecepatan Text Berjalan</label>
                                    <input type="number" class="form-control" name="running_text_speed"
                                        id="running_text_speed" value="5" required />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="bg_image_path">Gambar Background</label>
                                    <input type="file" class="form-control" name="bg_image_path" id="bg_image_path"
                                        accept="image/png, image/gif, image/jpeg, images/jpg"  />
                                    <div class="mt-1" id="preview_bg_image"></div>
                                </div>

                                <div class="form-group">
                                    <label for="logo_template_path">Logo Template</label>
                                    <input type="file" class="form-control" name="logo_template_path"
                                        id="logo_template_path" accept="image/png, image/gif, image/jpeg, images/jpg"
                                         />
                                    <div class="mt-1" id="preview_logo"></div>
                                </div>
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" class="form-control" name="status" required>
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" form="dataForm">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        const baseUrl = "{{ url('templates') }}";
        let datatable;

        datatable = CrudUtils.initDataTable({
            tableId: "datatable",
            ajaxUrl: `${baseUrl}/data`,
            searchableFields: ['name'],
            orders: [
                [1, "asc"]
            ],
            columns: [{
                    render: (data, type, row, meta) => meta.row + meta.settings._iDisplayStart + 1,
                    className: "text-center",
                    orderable: false
                },
                {
                    data: 'name'
                },
                {
                    data: 'bg_image_path',
                    render: function(data, type, row) {
                        return `<img src="${data || '/images/noimage.png'}" style="width: 100px">`;
                    }
                },
                {
                    data: 'running_text',
                    render: d => d ? d.substring(0, 30) + '...' : '-'
                },
                {
                    data: 'status',
                    render: d => d == 1 ? '<span class="badge badge-success text-white">Aktif</span>' :
                        '<span class="badge badge-danger text-white">Tidak Aktif</span>'
                },
                {
                    data: 'uid',
                    render: (data, type, row) => `
                    <div class="action-icon-wrapper">
                        <i class="fa fa-pencil-square text-primary edit-btn"
                            data-id="${data}"
                            data-name="${row.name}"
                            data-bg_color="${row.bg_color}"
                            data-bg_image_path="${row.bg_image_path}"
                            data-bg_running_text="${row.bg_running_text}"
                            data-running_text="${row.running_text}"
                            data-running_text_color="${row.running_text_color}"
                            data-running_text_speed="${row.running_text_speed}"
                            data-logo_template_path="${row.logo_template_path}"
                            data-status="${row.status}"
                            title="Edit"></i>
                        <i class="fa fa-times-circle btn-sm  text-danger delete-btn"
                            data-id="${data}" title="Delete"></i>
                    </div>`,
                    orderable: false,
                    className: "text-center"
                }
            ]
        });

        // Add
        $("#add").click(() => {
            CrudUtils.resetForm("dataForm", "formTitle", "Tambah Template", "dataId");
            $('#preview_bg_image, #preview_logo').html('');
            $('#formModal').modal('show');
        });

        // Edit
        $(document).on("click", ".edit-btn", function() {
            $("#formTitle").text("Edit Template");
            $("#dataId").val($(this).data("id"));
            $("#name").val($(this).data("name"));
            $("#bg_color").val($(this).data("bg_color"));
            $("#bg_running_text").val($(this).data("bg_running_text"));
            $("#running_text").val($(this).data("running_text"));
            $("#running_text_color").val($(this).data("running_text_color"));
            $("#running_text_speed").val($(this).data("running_text_speed"));
            $("#status").val($(this).data("status"));

            const bgImg = $(this).data("bg_image_path");
            const logoImg = $(this).data("logo_template_path");
            $('#preview_bg_image').html(bgImg ? `<img src="${bgImg}" width="120" class="img-thumbnail mt-1">` : '');
            $('#preview_logo').html(logoImg ? `<img src="${logoImg}" width="120" class="img-thumbnail mt-1">` : '');

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

        // Submit (Create / Update)
        $("#dataForm").submit(function(e) {
            e.preventDefault();
            const id = $("#dataId").val();
            const formData = new FormData(this);

            const url = id ? `${baseUrl}/update/${id}` : `${baseUrl}/store`;

            $.ajax({
                url: url,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                success: function(res) {
                    $('#formModal').modal('hide');
                    datatable.ajax.reload(null, false);
                    toastr.success("Data berhasil disimpan!");
                },
                error: function(xhr) {
                    toastr.error("Terjadi kesalahan saat menyimpan data.");
                }
            });
        });

        // preview image
        $(document).on("change", "#bg_image_path", function() {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_bg_image').html(`<img src="${e.target.result}" width="120" class="img-thumbnail mt-1">`);
            };
            reader.readAsDataURL(file);
        });

        $(document).on("change", "#logo_template_path", function() {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview_logo').html(`<img src="${e.target.result}" width="120" class="img-thumbnail mt-1">`);
            };
            reader.readAsDataURL(file);
        });
    </script>
@endsection
