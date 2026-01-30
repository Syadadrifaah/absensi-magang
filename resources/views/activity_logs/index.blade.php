@extends('layouts.index')

@section('judul', 'Log-Aktivitas')

@section('content')

<div class="container">
    <div class="mb-3">
            <h4 class="fw-semibold mb-1">Activity Logs</h4>
            <small class="text-muted">
                <a href="#">Dashboard</a> • Activity Logs
            </small>
        </div>
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
                            <th>Tanggal Log</th>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                            <th>IP</th>
                            <th>User Agent</th>
                            {{-- <th width="8%">Edit</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <h5 class="text-log-date">
                                    {{ $log->created_at->format('d M Y') }}<br>
                                    {{ $log->created_at->format('H:i') }}
                                <h5>
                            </td>
                            <td class="fw-semibold">
                                {{ $log->user->name ?? '-' }}
                                <span class="badge bg-info text-white">{{ $log->user->email ?? '-' }}</span>
                                <span class="badge bg-success text-white">
                                    {{ $log->user->employee->nip ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge 
                                    @if($log->action === 'CREATE') bg-success
                                    @elseif($log->action === 'UPDATE') bg-warning text-dark
                                    @elseif($log->action === 'DELETE') bg-danger
                                    @elseif($log->action === 'AUTH') bg-warning-subtle text-dark
                                    @else bg-info text-dark
                                    @endif">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <td>
                                <span>
                                    {{ $log->description }}
                                </span>
                            </td>

                            <td>
                                {{ $log->ip_address }}
                            </td>


                            <td>
                                {{ $log->user_agent }}
                            </td>

                            {{-- <td class="text-center">
                                <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal{{ $log->id }}">
                                    Edit
                                </button>
                            </td> --}}
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
