@extends('layouts.index')

@section('judul', 'Log-Aktivitas')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">User Activity Logs</h5>

            <form method="GET" class="d-flex">
                <input type="text" name="search"
                    value="{{ request('search') }}"
                    class="form-control form-control-sm me-2"
                    placeholder="Cari nama / email / NIP">
                <button class="btn btn-sm btn-primary">Cari</button>
            </form>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Email</th>
                            <th>NIP</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                            <th>IP</th>
                            <th>User Agent</th>
                            <th>Tanggal Log</th>
                            <th width="8%">Edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $log->user->name ?? '-' }}</td>
                            <td>{{ $log->user->email ?? '-' }}</td>
                            <td>{{ $log->user->nip ?? '-' }}</td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ strtoupper($log->action) }}
                                </span>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td class="text-truncate" style="max-width:150px">
                                {{ $log->user_agent }}
                            </td>
                            <td>
                                {{ $log->created_at->format('d-m-Y H:i') }}
                            </td>
                            <td class="text-center">
                                <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $log->id }}">
                                    Edit
                                </button>
                            </td>
                        </tr>

                        {{-- MODAL UPDATE --}}
                        <div class="modal fade" id="editModal{{ $log->id }}">
                            <div class="modal-dialog">
                                <form method="POST"
                                      action="{{ route('activity-logs.update', $log->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5>Edit Deskripsi Log</h5>
                                            <button class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <textarea name="description"
                                                class="form-control"
                                                rows="4">{{ $log->description }}</textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button class="btn btn-primary">Simpan</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                Data tidak ditemukan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between  mt-3">
                <small class="text-muted">
                    Menampilkan {{ $logs->firstItem() ?? 1 }}
                    - {{ $logs->lastItem() ?? $logs->count() }}
                    dari {{ $total }} data

                </small>
            </div>

            <div class="d-flex justify-content-end">
                {{ $logs->links('pagination::simple-bootstrap-5') }}
            </div>




        </div>
    </div>
</div>
@endsection
