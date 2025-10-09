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
                    <h4><i class="fa fa-list"></i> Publish Survey</h4>
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
                            <th>Lokasi</th>
                            <th>Template</th>
                            <th>Tipe</th>
                            <th width="10%">Status</th>
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
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" name="name" id="name" required />
                                </div>

                                <div class="form-group">
                                    <label for="description">Deskripsi</label>
                                    <input type="text" name="description" id="description" class="form-control"
                                        placeholder="Masukkan Deskripsi">
                                </div>

                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select id="status" class="form-control" name="status" required>
                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="template">Template</label>
                                    <select id="template" class="form-control" name="template" required>
                                        <option value="">-- Pilih Template --</option>
                                        @foreach ($templates as $template)
                                            <option value="{{ $template->id }}">{{ $template->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="location">Lokasi</label>
                                    <select id="location" class="form-control" name="location" required>
                                        <option value="">-- Pilih Lokasi --</option>
                                        @foreach ($locations as $location)
                                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="type">Tipe</label>
                                    <select id="type" class="form-control" name="type" required>
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="single">Single</option>
                                        <option value="multi">Multi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                {{-- Untuk tipe single --}}
                                <div class="wrapSingle d-none">
                                    <div class="form-group">
                                        <label for="single_question">Pilih Question</label>
                                        <select id="single_question" name="question_id" class="form-control">
                                            <option value="">-- Pilih Question --</option>
                                            @foreach ($questions as $question)
                                                <option value="{{ $question->id }}">{{ $question->question }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Untuk tipe multi --}}
                                <div class="wrapMulti d-none">
                                    <label>Pilih Beberapa Question</label>
                                    <div id="multiQuestions"></div>
                                    <button type="button" class="btn btn-sm btn-success mt-2" id="addQuestion">
                                        <i class="fa fa-plus"></i> Tambah Question
                                    </button>
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
        const baseUrl = "{{ url('survey') }}";
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
                    data: 'location',
                    orderable: false,
                    render: d => d ? d.name : '-'
                },
                {
                    data: 'template',
                    orderable: false,
                    render: d => d ? d.name : '-'
                },
                {
                    data: 'type',
                    orderable: false
                },
                {
                    data: 'status',
                    render: d => d == 1 ? '<span class="badge badge-success text-white">Aktif</span>' :
                        '<span class="badge badge-danger text-white">Tidak Aktif</span>',
                    className: "text-center"
                },
                {
                    data: 'uid',
                    render: (data, type, row) => `
                    <div class="action-icon-wrapper">
                        <i class="fa fa-eye btn-sm  text-info preview-btn"
                            data-toggle="tooltip" data-placement="top"
                            data-id="${row.id}" title="Preview"></i>
                        <i class="fa fa-pencil-square text-primary edit-btn"
                            data-id="${data}"
                            data-name="${row.name}"
                            data-description="${row.description}"
                            data-templates="${row.template_id}"
                            data-location="${row.location_id}"
                            data-typesurvey="${row.type}"
                            data-status="${row.status}"
                            data-toggle="tooltip" data-placement="top"
                            title="Edit"></i>
                        <i class="fa fa-times-circle btn-sm  text-danger delete-btn"
                            data-toggle="tooltip" data-placement="top"
                            data-id="${data}" title="Delete"></i>
                    </div>`,
                    orderable: false,
                    className: "text-center"
                }
            ]
        });

        // Add
        $("#add").click(() => {
            CrudUtils.resetForm("dataForm", "formTitle", "Tambah Survey", "dataId");
            $('#formModal').modal('show');
        });

        // Edit
        $(document).on("click", ".edit-btn", function() {
            $("#formTitle").text("Edit Survey");
            const id = $(this).data("id");
            $("#dataId").val(id);
            $("#name").val($(this).data("name"));
            $("#status").val($(this).data("status"));
            $("#description").val($(this).data("description"));
            $('#template').val($(this).data('templates')).trigger('change');
            $('#location').val($(this).data('location')).trigger('change');
            $('#type').val($(this).data('typesurvey')).trigger('change');

            // Ambil detail pertanyaan dari backend
            $.get(`${baseUrl}/details/${id}`, function(res) {
                // Kosongkan dulu
                $("#multiQuestions").empty();

                if ($('#type').val() === 'single') {
                    $('#single_question').val(res[0]?.question_id || '').trigger('change');
                } else if ($('#type').val() === 'multi') {
                    res.forEach(q => {
                        const questionSelect = `
                    <div class="input-group mb-2 multi-question-item">
                        <select name="question_ids[]" class="form-control" required>
                            <option value="">-- Pilih Question --</option>
                            @foreach ($questions as $question)
                                <option value="{{ $question->id }}">{{ $question->question }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-danger removeQuestion" type="button"><i class="fa fa-times"></i></button>
                        </div>
                    </div>`;
                        $("#multiQuestions").append(questionSelect);
                        $("#multiQuestions select:last").val(q.question_id);
                    });
                }
            });

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

        $(document).on("click", ".preview-btn", function (){
            let uid = $(this).data('id');
            window.location.href = `{{ url('survey/preview') }}/${uid}`
        })

        $(document).on("change", "#type", function() {
            if ($(this).val() === 'single') {
                $('.wrapSingle').removeClass('d-none');
                $('.wrapMulti').addClass('d-none');
            } else if ($(this).val() === 'multi') {
                $('.wrapSingle').addClass('d-none');
                $('.wrapMulti').removeClass('d-none');
            } else {
                $('.wrapSingle, .wrapMulti').addClass('d-none');
            }
        });

        // Tombol tambah question untuk multi
        $(document).on("click", "#addQuestion", function() {
            const questionSelect = `
        <div class="input-group mb-2 multi-question-item">
            <select name="question_ids[]" class="form-control" required>
                <option value="">-- Pilih Question --</option>
                @foreach ($questions as $question)
                    <option value="{{ $question->id }}">{{ $question->question }}</option>
                @endforeach
            </select>
            <div class="input-group-append">
                <button class="btn btn-danger removeQuestion" type="button"><i class="fa fa-times"></i></button>
            </div>
        </div>`;
            $("#multiQuestions").append(questionSelect);
        });

        // Hapus question di mode multi
        $(document).on("click", ".removeQuestion", function() {
            $(this).closest(".multi-question-item").remove();
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
    </script>
@endsection
