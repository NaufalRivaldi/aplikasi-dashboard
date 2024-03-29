@extends('layouts.content_datatable')

@section('card-button')
    <a href="{{ route('master.materi.create') }}" class="btn btn-info"><i class="ti-plus"></i> Add data</a>
@endsection

@section('content-table')
    <th>Nama</th>
    <th data-type="none">Warna Chart</th>
    <th data-type="select" data-filtering='{!! parseJson($filtering->kategori) !!}'>Kategori</th>
    <th data-type="select" data-filtering='{!! parseJson($filtering->status) !!}'>Status</th>
    <th data-type="none">Jumlah Grade</th>
    <th>Action</th>
@endsection

<!-- Start - Set column -->
@php
    $column = [
        ["data" => "nama", "name" => "nama", "defaultContent" => "-"],
        ["data" => "warna", "name" => "warna", "defaultContent" => "-"],
        ["data" => "kategori.nama", "name" => "kategori.nama", "defaultContent" => "-"],
        ["data" => "status", "name" => "status", "defaultContent" => "-"],
        ["data" => "materi_grades_count", "name" => "materi_grades_count", "defaultContent" => "-"],
        ["data" => "action", "name" => "action", "orderable" => false, "searchable" => false],
    ];
@endphp
<!-- End - Set column -->

@push('scripts')
<script>
    // ----------------------------------------------------------------------------
    // Set Vue
    // ----------------------------------------------------------------------------
    new Vue({
        // ------------------------------------------------------------------------
        el: '#app',
        // ------------------------------------------------------------------------
        // Data for Materi page
        // ------------------------------------------------------------------------
        data: {
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for Materi page
        // ------------------------------------------------------------------------
        methods: {
            // --------------------------------------------------------------------
            // Update status function
            // --------------------------------------------------------------------
            setStatus: function(type, id){
                // ----------------------------------------------------------------
                let url = "{{ route('master.materi.update.status', ['type' => ':type', 'id' => ':id']) }}";
                url = url.replace(':type', type);
                url = url.replace(':id', id);
                // ----------------------------------------------------------------
                // Set confirm
                // ----------------------------------------------------------------
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data status akan terubah.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ubah',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    // ------------------------------------------------------------
                    if (result.value) {
                        // --------------------------------------------------------
                        // Set request
                        // --------------------------------------------------------
                        let request = axios.put(url);
                        // --------------------------------------------------------
                        // If request success
                        // --------------------------------------------------------
                        request.then((response)=>{
                            // ----------------------------------------------------
                            let data = response.data;
                            // ----------------------------------------------------
                            $('#myDataTable').DataTable().ajax.reload();
                            // ----------------------------------------------------
                            Vue.nextTick(function () {
                                toastr.success(data.message);    
                            })
                            // ----------------------------------------------------
                        })
                        // --------------------------------------------------------
                        // If request error
                        // --------------------------------------------------------
                        request.catch((error)=>{
                            toastr.error(error.message);
                        })
                        // --------------------------------------------------------
                    }
                    // ------------------------------------------------------------
                })
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Delete data function
            // --------------------------------------------------------------------
            deleteData: function(id){
                // ----------------------------------------------------------------
                let url = "{{ route('master.materi.destroy', ':id') }}";
                url = url.replace(':id', id);
                // ----------------------------------------------------------------
                // Set confirm
                // ----------------------------------------------------------------
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data terhapus pada list.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    // ------------------------------------------------------------
                    if (result.value) {
                        // --------------------------------------------------------
                        // Set request
                        // --------------------------------------------------------
                        let request = axios.delete(url);
                        // --------------------------------------------------------
                        // If request success
                        // --------------------------------------------------------
                        request.then((response)=>{
                            // ----------------------------------------------------
                            let data = response.data;
                            // ----------------------------------------------------
                            $('#myDataTable').DataTable().ajax.reload();
                            // ----------------------------------------------------
                            Vue.nextTick(function () {
                                toastr.success(data.message);    
                            })
                            // ----------------------------------------------------
                        })
                        // --------------------------------------------------------
                        // If request error
                        // --------------------------------------------------------
                        request.catch((error)=>{
                            toastr.error(error.message);
                        })
                        // --------------------------------------------------------
                    }
                    // ------------------------------------------------------------
                })
                // ----------------------------------------------------------------
            },
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for Materi page
        // ------------------------------------------------------------------------
        mounted() {
            // --------------------------------------------------------------------
            let vm = this;
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Status event
            // --------------------------------------------------------------------
            $(document).on('click', '.btn-status', function(){
                let $id = $(this).data('id');
                let $type = $(this).data('type');
                vm.setStatus($type, $id);
            })
            // --------------------------------------------------------------------

            // --------------------------------------------------------------------
            // Delete event
            // --------------------------------------------------------------------
            $(document).on('click', '.btn-delete', function(){
                let $id = $(this).data('id');
                vm.deleteData($id);
            })
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------
    })
    // ----------------------------------------------------------------------------
</script>
@endpush