<script type="text/javascript">
    window.CrudUtils = {
        page: 1,
        pageSize: 10,
        recordsTotal: 0,

        resetForm: function(formId, formTitleId, titleText, hiddenIdField) {
            $(`#${formId}`)[0].reset();
            if (formTitleId) $(`#${formTitleId}`).text(titleText || "Form");
            if (hiddenIdField) $(`#${hiddenIdField}`).val("");
        },

        initDataTable: function({
            tableId,
            ajaxUrl,
            columns,
            searchableFields = [], // contoh: ['name', 'description']
            ajaxDataBuilder = null,
            ajaxDataSrc = (res) => res.data,
            onCreated = null,
        }) {
            const table = $(`#${tableId}`).DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"],
                ],
                ajax: {
                    url: ajaxUrl,
                    type: "GET",
                    data: function(d) {
                        // Hapus data.columns biar tidak kirim columns[x][search]
                        delete d.columns;
                        delete d.search; // opsional, kalau kamu pakai custom search

                        // Global search (custom filter kamu)
                        const searchValue = $('#datatable_filter input')
                    .val(); // atau d.search?.value
                        const filters = {};
                        if (searchValue && searchableFields.length > 0) {
                            searchableFields.forEach(field => {
                                filters[field] = searchValue;
                            });
                        }

                        const baseQuery = {
                            draw: d.draw,
                            start: d.start,
                            length: d.length,
                            orderBy: d.order?.[0]?.column != null ? columns[d.order[0].column]
                                .data : null,
                            ascending: d.order?.[0]?.dir !== "desc",
                            filters: filters,
                        };

                        return ajaxDataBuilder ? ajaxDataBuilder(d, baseQuery) : baseQuery;
                    },
                    dataSrc: ajaxDataSrc,
                },
                columns: columns,
            });

            if (onCreated) onCreated(table);
            return table;
        },

        getItem: function({
            url,
            beforeFunction
        }) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'json',
                    before: function() {
                        beforeFunction();
                    },
                    success: function(response) {
                        resolve(response);
                    },
                    error: function(xhr, status, error) {
                        console.error("Failed to fetch item:", error);
                        reject({
                            xhr,
                            status,
                            error
                        });
                    }
                });
            });
        },

        deleteItem: function({
            url,
            id,
            onDeleted
        }) {
            swal({
                title: "Are you sure?",
                text: "This will delete the data.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url: `${url}/${id}`,
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        success: function() {
                            showToast.success("Data deleted successfully!");
                            if (onDeleted) onDeleted();
                        },
                        error: function() {
                            showToast.error("Failed to delete data!");
                        }
                    });
                }
            });
        },


        submitFormWithFormData: function({
            formId,
            modalId,
            createUrl,
            updateUrl,
            getPayload, // Harus mengembalikan instance FormData
            idFieldId,
            onSaved,
        }) {
            $(`#${formId}`).off("submit").on("submit", function(e) {
                e.preventDefault();

                const id = $(`#${idFieldId}`).val();
                const formData = getPayload(); // Harus instanceof FormData

                const method = id ? "PUT" : "POST";
                const url = id ? `${updateUrl}/${id}` : createUrl;

                swal({
                    title: "Please wait...",
                    text: "Saving data...",
                    buttons: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                });

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            $(`#${modalId}`).modal("hide");
                            swal.close();
                            showToast.success("Data saved successfully!");
                            if (onSaved) onSaved();
                        } else {
                            swal({
                                icon: "error",
                                title: "Validation Failed",
                                content: {
                                    element: "div",
                                    attributes: {
                                        innerHTML: res.msg
                                    }
                                }
                            })
                        }
                    },
                    error: function(xhr, status, error) {
                        swal.close();

                        if (xhr.status === 400 && xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            let messages = "";

                            for (const field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    errors[field].forEach(msg => {
                                        messages += `• ${msg}<br/>`;
                                    });
                                }
                            }

                            swal({
                                icon: "error",
                                title: "Validation Failed",
                                content: {
                                    element: "div",
                                    attributes: {
                                        innerHTML: messages
                                    }
                                }
                            });
                        } else {
                            showToast.error("Error saving data!", "Error");
                        }

                        console.error("Submit error:", error);
                    }
                });
            });
        },

        submitForm: function({
            formId,
            modalId,
            createUrl,
            updateUrl,
            getPayload,
            idFieldId,
            onSaved,
        }) {
            $(`#${formId}`).submit(function(e) {
                e.preventDefault();

                const id = $(`#${idFieldId}`).val();
                const payload = getPayload();

                const method = id ? "PUT" : "POST";
                const url = id ? `${updateUrl}/${id}` : createUrl;
                swal({
                    title: "Please wait...",
                    text: "Saving data...",
                    buttons: false,
                    closeOnClickOutside: false,
                    closeOnEsc: false,
                });

                $.ajax({
                    url: url,
                    method: method,
                    contentType: "application/json",
                    data: JSON.stringify(payload),
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    success: function(res) {
                        if (res.status == 'success') {
                            $(`#${modalId}`).modal("hide");
                            swal.close();
                            showToast.success("Data saved successfully!");
                            if (onSaved) onSaved();
                        } else {
                            swal({
                                icon: "error",
                                title: "Validation Failed",
                                content: {
                                    element: "div",
                                    attributes: {
                                        innerHTML: res.msg
                                    }
                                }
                            })
                        }
                    },
                    error: function(xhr, status, error) {
                        swal.close();

                        if (xhr.status === 422 && xhr.responseJSON?.errors) {
                            const errors = xhr.responseJSON.errors;
                            let messages = "";

                            for (const field in errors) {
                                if (errors.hasOwnProperty(field)) {
                                    errors[field].forEach(msg => {
                                        messages += `• ${msg}<br/>`;
                                    });
                                }
                            }

                            swal({
                                icon: "error",
                                title: "Validation Failed",
                                content: {
                                    element: "div",
                                    attributes: {
                                        innerHTML: messages
                                    }
                                }
                            });
                        } else {
                            // message, title = 'Error', reload = false
                            showToast.error("Error saving data!", "Error");
                        }

                        console.error("Submit error:", error);
                    }
                });
            });
        },
    };
</script>

<script>
    function openPrintWindow(url, followPc = 1, width = 600, height = 600, ) {
        // ukuran window 90% lebar dan tinggi layar
        if (followPc === 1)
        {
            width = Math.floor(window.innerWidth * 0.9);
            height = Math.floor(window.innerHeight * 0.9);
        }

        // posisi window di tengah layar
        const left = Math.floor((window.screen.width - width) / 2);
        const top = Math.floor((window.screen.height - height) / 2);

        window.open(
            url,
            '_blank',
            `width=${width},height=${height},top=${top},left=${left},resizable=yes,scrollbars=yes`
        );
    }
</script>
