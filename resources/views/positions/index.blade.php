@extends('layouts.index')

@section('judul','Data Jabatan')

@section('content')
<div class="page-inner">
         <div class="mb-3">
            <h4 class="fw-semibold mb-1">Data Jabatan</h4>
            <small class="text-muted">
                <a href="#">Dashboard</a> • Data Jabatan
            </small>
        </div>

        <div class="card">
                <div class="card-body">
                        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createPositionModal">Tambah Jabatan</button>

                        <div class="positions-list">
                                @foreach($positions as $pos)
                                        @php
                                                $level = is_numeric($pos->level) ? (int)$pos->level : 0;
                                                $indent = $level * 28; // pixels indentation
                                        @endphp
                                        <div class="d-flex align-items-center mb-2" style="margin-left: {{ $indent }}px;">
                                                <div class="flex-grow-1">
                                                        <strong>{{ $pos->name }}</strong>
                                                        <div class="text-muted small">Level: {{ $pos->level ?? '-' }}</div>
                                                </div>
                                                <div>
                                                    <button class="btn btn-sm btn-warning btn-edit-position" 
                                                        data-id="{{ $pos->id }}" 
                                                        data-name="{{ $pos->name }}" 
                                                        data-level="{{ $pos->level }}"
                                                        data-bs-toggle="modal" data-bs-target="#editPositionModal">
                                                        Edit
                                                    </button>

                                                    <button type="button" class="btn btn-sm btn-danger btn-delete-position"
                                                        data-action="{{ route('positions.destroy', $pos->id) }}"
                                                        data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
                                                        Hapus
                                                    </button>
                                                </div>
                                        </div>
                                @endforeach
                        </div>
                </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" id="createPositionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Jabatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('positions.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Jabatan</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Level (angka)</label>
                                <input type="text" name="level" class="form-control">
                                <div class="form-text">Gunakan angka untuk level agar hierarki sesuai (lebih kecil = lebih kiri).</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editPositionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Jabatan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <!-- Confirm Delete Modal -->
                    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Yakin ingin menghapus data ini?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="button" id="confirm-delete-btn" class="btn btn-danger">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form id="editPositionForm" method="POST" action="#">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nama Jabatan</label>
                                <input type="text" name="name" id="edit-name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Level (angka)</label>
                                <input type="text" name="level" id="edit-level" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function(){
    // Populate edit modal when shown (uses Bootstrap event.relatedTarget)
    var editModal = document.getElementById('editPositionModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            if (!button) return;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var level = button.getAttribute('data-level');

            document.getElementById('edit-name').value = name || '';
            document.getElementById('edit-level').value = level || '';

            var form = document.getElementById('editPositionForm');
            form.action = '/positions/' + id;
        });
    }

    // Confirm delete modal: set action and submit on confirm
    var deleteModal = document.getElementById('confirmDeleteModal');
    var deleteAction = null;
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            if (!button) return;
            deleteAction = button.getAttribute('data-action');
        });

        document.getElementById('confirm-delete-btn').addEventListener('click', function(){
            if (!deleteAction) return;
            // create form and submit
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteAction;
            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var inputToken = document.createElement('input');
            inputToken.type = 'hidden';
            inputToken.name = '_token';
            inputToken.value = token;
            form.appendChild(inputToken);
            var inputMethod = document.createElement('input');
            inputMethod.type = 'hidden';
            inputMethod.name = '_method';
            inputMethod.value = 'DELETE';
            form.appendChild(inputMethod);
            document.body.appendChild(form);
            form.submit();
        });
    }
});
</script>
@endsection
