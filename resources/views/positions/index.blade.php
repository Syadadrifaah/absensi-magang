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

                                                    <button type="button"
                                                        class="btn btn-sm btn-danger btn-delete-position"
                                                        data-action="{{ route('positions.destroy', $pos->id) }}"
                                                        data-name="{{ $pos->name }}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#deletePositionModal">
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

        <!-- DELETE MODAL -->
        @foreach ($positions as $pos)
            
        <div class="modal fade" id="deletePositionModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form id="deletePositionForm" method="POST" action="#">
                        @csrf
                        @method('DELETE')

                        <!-- HEADER TANPA BORDER -->
                        <div class="modal-header border-0">
                            <button type="button"
                                    class="btn-close ms-auto"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>

                        <div class="modal-body text-center py-4">

                            <!-- ICON BULAT + SHADOW -->
                            <div
                                class="d-inline-flex align-items-center justify-content-center rounded-circle shadow bg-light bg-opacity-10 text-danger mb-3"
                                style="width: 90px; height: 90px;"
                            >
                                <i class="fas fa-question fs-1"></i>
                            </div>

                            <h5 class="mb-1">Hapus Jabatan?</h5>

                            <p class="text-muted mb-4">
                                Apakah Anda yakin ingin menghapus jabatan
                                <strong id="delete-position-name">—</strong>?
                            </p>

                            <div class="d-flex justify-content-center gap-2">
                                <button type="button"
                                        class="btn btn-danger"
                                        data-bs-dismiss="modal">
                                    Batal
                                </button>

                                <button type="submit"
                                        class="btn btn-success">
                                    Ya, Hapus
                                </button>
                            </div>

                        </div>

                    </form>

                </div>
            </div>
        </div>
        @endforeach



</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    // EDIT MODAL
    const editModal = document.getElementById('editPositionModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            const id    = button.dataset.id;
            const name  = button.dataset.name;
            const level = button.dataset.level;

            document.getElementById('edit-name').value  = name || '';
            document.getElementById('edit-level').value = level || '';

            document.getElementById('editPositionForm').action = `/positions/${id}`;
        });
    }

    // DELETE MODAL
    const deleteModal = document.getElementById('deletePositionModal');
    const deleteForm = document.getElementById('deletePositionForm');
    const deleteName = document.getElementById('delete-position-name');

    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            if (!button) return;

            deleteForm.action = button.dataset.action;
            deleteName.textContent = button.dataset.name ?? '—';
        });
    }

});
</script>

@endsection


