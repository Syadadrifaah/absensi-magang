@extends('layouts.index')

@section('content')
<div class="page-inner">
    <div class="page-header">
        <h4 class="page-title">Edit Jabatan</h4>
    </div>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('positions.update', $position->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Nama Jabatan</label>
                    <input type="text" name="name" class="form-control" value="{{ $position->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Level</label>
                    <input type="text" name="level" class="form-control" value="{{ $position->level }}">
                </div>
                <button class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
