<style>
    * {
        font-weight: 500
    }

    .fs18 {
        font-size: 18px;
    }

    .form-control {
        min-height: 33px !important;
        height: 33px !important;
    }

    .footer {
        bottom: 0;
        position: fixed;
        right: 0;
        left: 0;
    }

    .content-body {
        min-height: 100% !important;
        padding-bottom: 80px !important;
    }

    .nav-header .brand-logo a {
        padding: 0.6135rem 1.8125rem;
        display: block;
    }

    input[type="checkbox"] {
        cursor: pointer;
    }

    select,
    .select2 {
        width: 100%;
    }

    .metismenu a {
        display: flex !important;
        align-items: middle !important;
    }

    .required-label::after {
        content: " *wajib diisi";
        color: red;
    }

    .optional-label::after {
        content: " (optional)";
        color: #999;
        font-style: italic;
        font-weight: normal;
    }

    .action-icon-wrapper {
        display: flex;
        justify-content: center;
        gap: 5px;
        /* jarak antar ikon */
        align-items: center;
    }

    .action-icon-wrapper i {
        font-size: 2rem;
        /* ukuran ikon */
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .action-icon-wrapper i:hover {
        transform: scale(1.2);
        opacity: 0.8;
    }

    #datatable thead th {
        vertical-align: middle;
    }

    #datatable_wrapper {
        padding: 0px !important;
    }

    table {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    table.dataTable.table-bordered {
        border-collapse: collapse !important;
    }

    table.dataTable th {
        font-weight: bold;
    }

    table.dataTable td {
        padding: 6px 10px;
    }

    .dataTables_filter input,
    .dataTables_filter input:focus,
    .dataTables_length select,
    .dataTables_length select:focus {
        border: 1px solid #888888;
        /* Atau warna lain */
        border-radius: 4px;
        padding: 4px 8px;
        outline: none;
    }

    .modal-fullscreen {
        width: 100vw;
        height: 100vh;
        margin: 0;
        padding: 0;
        max-width: none;
    }

    .modal-fullscreen .modal-content {
        height: 100vh;
        border-radius: 0;
    }

    .modal-body {
        overflow: auto;
    }

    input[type="checkbox"]:after {
        border: 1px solid #818181;
    }
</style>


{{-- whatsapp button --}}

<style>
    .whatsapp-float {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background-color: #25D366;
        /* warna hijau WA */
        color: white;
        border-radius: 50%;
        font-size: 28px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        text-decoration: none;
        transition: 0.3s;
    }

    .whatsapp-float:hover {
        background-color: #1ebe5d;
        /* sedikit lebih gelap saat hover */
        color: white;
        transform: scale(1.1);
    }
</style>

{{-- fitur chat --}}
<style>
    .modal.right .modal-dialog {
        position: fixed;
        margin: auto;
        width: 500px;
        /* atur lebar modal */
        height: 100%;
        right: 0;
        top: 0;
        transform: translate3d(100%, 0, 0);
        transition: transform 0.3s ease-out;
    }

    .modal.right.show .modal-dialog {
        transform: translate3d(0, 0, 0);
    }

    .modal.right .modal-content {
        height: 100%;
        overflow-y: auto;
        border: none;
        border-radius: 0;
    }

    .mention {
        color: #00bfff;
        font-weight: bold;
        background: rgba(0, 191, 255, 0.1);
        padding: 2px 4px;
        border-radius: 4px;
    }
</style>
