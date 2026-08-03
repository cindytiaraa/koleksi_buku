@extends('layouts.admin')

@section('style_page')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css">

    <style>
        .card{ margin-bottom:24px; }
        .table-hover tbody tr{ cursor:pointer; }
        .table-hover tbody tr:hover{ background:#f5f7ff; }
        #btnLoading{ display:none; }
        .spinner-border-sm{ margin-right:6px; }
        .dataTables_wrapper .dataTables_filter input{ border-radius:8px; }
        .dataTables_wrapper .dataTables_length select{ border-radius:8px; }
        .modal-header{ 
            background:#b66dff;
            color:white;
        }
        .modal-header .close{
            color:white;
            opacity:1;
        }

    </style>
@endsection

@section('content')

<div class="page-header">
    <h3 class="page-title">
        Demo Javascript & JQuery
    </h3>
</div>

{{-- Loading Spinner --}}
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Demo Loading Spinner
                </h4>
            </div>

            <div class="card-body">
                <form id="spinnerForm">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input type="text" class="form-control" id="spinnerNama" required>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="form-group">
                                <label>Harga Barang</label>
                                <input type="number" class="form-control" id="spinnerHarga" required>
                            </div>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" id="btnSpinner" class="btn btn-gradient-primary btn-block">
                                Submit
                            </button>

                            <button type="button" id="btnLoading" class="btn btn-gradient-primary btn-block" style="display:none" disabled>
                                <span class="spinner-border spinner-border-sm"> </span>
                                Loading...
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>


{{-- HTML TABLE --}}
<div class="row mt-4">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Demo Tabel HTML
                </h4>
            </div>

            <div class="card-body">

                <form id="formHtml">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input id="namaBarang" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Barang</label>
                                <input id="hargaBarang" type="number" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button id="btnTambahHtml" type="button" class="btn btn-gradient-success btn-block">
                                Tambahkan
                            </button>
                        </div>

                    </div>
                </form>

                <hr>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="htmlTable">
                        <thead>
                            <tr>
                                <th width="80">ID</th>
                                <th>Nama Barang</th>
                                <th width="180">Harga</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>
</div>


{{--  DATATABLES --}}
<div class="row mt-4">
    <div class="col-12">

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">
                    Demo DataTables
                </h4>
            </div>

            <div class="card-body">
                <form id="formDataTable">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Nama Barang</label>
                                <input id="namaBarangDT" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Harga Barang</label>
                                <input id="hargaBarangDT" type="number" class="form-control" required>
                            </div>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" id="btnTambahDT" class="btn btn-gradient-success btn-block">
                            Tambahkan
                            </button>
                        </div>

                    </div>
                </form>

                <hr>

                <div class="table-responsive">
                    <table id="dataTableDemo" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Barang</th>
                                <th>Harga</th>
                            </tr>
                        </thead>

                        <tbody>

                        </tbody>

                    </table>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@section('js_page')
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let htmlData=[];
        let dtData=[];
        let table;

        $(function(){
            table=$("#dataTableDemo").DataTable();
        });

        // SPINNER
        $("#btnSpinner").click(function(){
            let form=document.getElementById("spinnerForm");

            if(!form.checkValidity()){
                form.reportValidity();
                return;
            }

            $("#btnSpinner").hide();
            $("#btnLoading").show();

            setTimeout(function(){
                form.reset();
                $("#btnLoading").hide();
                $("#btnSpinner").show();

                Swal.fire({
                    icon:"success",
                    title:"Berhasil",
                    text:"Demo Loading Spinner",
                    timer:1200,
                    showConfirmButton:false
                });
            },1000);
        });

        //TABEL HTML
        $("#btnTambahHtml").click(function(){
            let form=document.getElementById("formHtml");

            if(!form.checkValidity()){
                form.reportValidity();
                return;
            }

            htmlData.push({
                id:htmlData.length+1,
                nama:$("#namaBarang").val(),
                harga:$("#hargaBarang").val()
            });
            renderHtml();
            form.reset();
        });

        function renderHtml(){
            let html="";

            htmlData.forEach(function(item,index){
                html+=`
                <tr data-index="${index}">
                    <td>${item.id}</td>
                    <td>${item.nama}</td>
                    <td>Rp ${Number(item.harga).toLocaleString("id-ID")}</td>
                </tr>
                `;
            });
            $("#htmlTable tbody").html(html);
        }

        // DATATABLES
        $("#btnTambahDT").click(function(){
            let form=document.getElementById("formDataTable");

            if(!form.checkValidity()){
                form.reportValidity();
                return;
            }

            dtData.push({
                id:dtData.length+1,
                nama:$("#namaBarangDT").val(),
                harga:$("#hargaBarangDT").val()
            });
            renderDT();
            form.reset();
        });

        function renderDT(){
            table.clear();
            dtData.forEach(function(item){
                table.row.add([
                    item.id,
                    item.nama,
                    "Rp "+Number(item.harga).toLocaleString("id-ID")
                ]);
            });
            table.draw();
        }

        // CLICK HTML ROW
        $(document).on("click","#htmlTable tbody tr",function(){
            Swal.fire({
                title:"Demo",
                text:"Klik Row HTML Table",
                icon:"info"
            });
        });

        // CLICK DT ROW
        $("#dataTableDemo tbody").on("click","tr",function(){
            Swal.fire({
                title:"Demo",
                text:"Klik Row DataTables",
                icon:"info"
            });
        });
    </script>

@endsection