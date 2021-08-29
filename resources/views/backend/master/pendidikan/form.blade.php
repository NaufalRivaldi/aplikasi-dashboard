@extends('layouts.content_form')

@section('content-form')
<form action="{{ $pendidikan->id == null ? route('master.pendidikan.store') : route('master.pendidikan.update', $pendidikan->id) }}" method="POST">
    @csrf
    @if($pendidikan->id != null)
        @method('PUT')
    @endif
    <!-- Start - Hidden input -->
    <input type="hidden" name="id" value="{{ $pendidikan->id }}">
    <!-- End - Hidden input -->

    <!-- Start - Nama -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Nama</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="text" name="nama" class="form-control @if($errors->has('nama')) is-invalid @endif" value="{{ $pendidikan->nama ? $pendidikan->nama : old('nama') }}">
            <!-- Start - Error handling -->
            @if($errors->has('nama'))
                <div class="invalid-feedback">{{ $errors->first('nama') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - Nama -->

    <!-- Start - warna -->
    <div class="form-group row">
        <label class="col-sm-2 col-form-label">
            <span class="badge badge-danger">Required</span><br>
            <div class="label-form">Warna</div>
        </label>
        <div class="col-sm-10 input-form">
            <input type="color" id="warna" class="form-control @if($errors->has('warna')) is-invalid @endif col-2" name="warna" value="{{ $pendidikan->warna ? $pendidikan->warna : old('warna') }}">
            <!-- Start - Error handling -->
            @if($errors->has('warna'))
                <div class="invalid-feedback">{{ $errors->first('warna') }}</div>
            @endif
            <!-- End - Error handling -->
        </div>
    </div>
    <!-- End - warna -->

    <!-- Start - Nama -->
    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-10">
            <button type="submit" class="btn btn-success">
                <i class="ti-save"></i> Simpan
            </button>
        </div>
    </div>
    <!-- End - Nama -->
</form>
@endsection

@section('card-button-footer')
<a href="{{ route('master.pendidikan.index') }}" class="btn btn-info">
    <i class="ti-arrow-circle-left"></i> Kembali
</a>
@endsection

@push('scripts')
<script>
    // ----------------------------------------------------------------------------
    // Set Vue
    // ----------------------------------------------------------------------------
    new Vue({
        // ------------------------------------------------------------------------
        el: '#app',
        // ------------------------------------------------------------------------
        // Data for pendidikan page
        // ------------------------------------------------------------------------
        data: {
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Methods for pendidikan page
        // ------------------------------------------------------------------------
        methods: {
            //
        },
        // ------------------------------------------------------------------------

        // ------------------------------------------------------------------------
        // Mounted for pendidikan page
        // ------------------------------------------------------------------------
        mounted() {
            // --------------------------------------------------------------------
            let vm = this;
            // --------------------------------------------------------------------
        },
        // ------------------------------------------------------------------------
    })
    // ----------------------------------------------------------------------------
</script>
@endpush