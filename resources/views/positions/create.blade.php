@extends('layouts.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Tambah Jabatan</h4>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('positions.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Jabatan</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Level</label>
                    <input type="text" name="level" class="form-control">
                </div>
                <button class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection
