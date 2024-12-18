<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- cdn jquery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body class="bg-light">
    <!-- Header -->
    <nav class="navbar navbar-light bg-white shadow-sm mb-4">
        <div class="container d-flex align-items-center">
            <span class="ms-2 h4 mb-0">Pengaduan</span>
            <a href="{{ route('report.article') }}" class="btn btn-secondary ms-auto">Kembali</a>
        </div>
    </nav>

    <!-- Content -->
    <div class="container">
        @foreach ($reports as $report)
            <div class="card bg-warning text-white mb-4">
                <div class="card-header d-flex justify-content-between align-items-center toggle-detail"
                    data-id="{{ $report->id }}">
                    <h5 class="mb-0">Pengaduan {{ \Carbon\Carbon::parse($report->created_at)->format('d F Y') }}</h5>
                    <i class="fas fa-chevron-down"></i>
                </div>

                <div class="card-body detail-section d-none" id="detail-{{ $report->id }}">
                    <!-- Tab Header -->
                    <ul class="nav nav-tabs mb-3">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="tab"
                                data-bs-target="#data-{{ $report->id }}">Data</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#image-{{ $report->id }}">Gambar</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab"
                                data-bs-target="#status-{{ $report->id }}">Status</button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content">
                        <!-- Data Tab -->
                        <div class="tab-pane fade show active" id="data-{{ $report->id }}">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item bg-warning text-white"><strong>Tipe:</strong> {{ $report->type }}</li>
                                <li class="list-group-item bg-warning text-white"><strong>Lokasi:</strong> {{ $report->village }}, {{ $report->subdistrict }}, {{ $report->regency }}, {{ $report->province }}</li>
                                <li class="list-group-item bg-warning text-white"><strong>Deskripsi:</strong> {{ $report->description }}</li>
                            </ul>
                        </div>

                        <!-- Image Tab -->
                        <div class="tab-pane fade" id="image-{{ $report->id }}">
                            @if ($report->image)
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $report->image) }}" alt="Gambar Pengaduan"
                                        class="img-fluid rounded shadow">
                                </div>
                            @else
                                <p class="text-center text-white">Tidak ada gambar pendukung</p>
                            @endif
                        </div>

                        <!-- Status Tab -->
                        <div class="tab-pane fade" id="status-{{ $report->id }}">
                            @if ($report->response !== null && $report->response->isNotEmpty())
                                
                            @else
                            <p>Pengaduan belum direspon petugas, ingin menghapus pengaduan?</p>
                            <button class="btn btn-danger mt-2" onclick="showModal({{ $report->id }}, '{{ $report->created_at }}')">
                                Delete
                            </button>
                            @endif

                            <h5 class="mt-4">Response History</h5>
                            <ol class="timeline">
                                @forelse ($report->responseProgress as $progress)
                                    @php
                                        // Decode JSON from histories column
                                        $histories = json_decode($progress->histories, true);
                                    @endphp
                                    <li class="mb-3">
                                        <strong>{{ $progress->created_at->format('d M Y, H:i') }}</strong>
                                        <p>{{ $histories['response'] }}</p>
                                    </li>
                                @empty
                                    <p class="text-muted">Belum ada respons</p>
                                @endforelse
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Jika tidak ada laporan -->
        @if ($reports->isEmpty())
            <p class="text-center">Tidak ada laporan untuk ditampilkan.</p>
        @endif
    </div>

    <!-- Modal Hapus Report -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" id="form-delete-report" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Hapus Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus report yang dibuat pada
                        {{ \Carbon\Carbon::parse($report?->created_at ?? '')->format('d F Y') }}?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle detail visibility
        document.querySelectorAll('.toggle-detail').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const detailSection = document.getElementById(`detail-${id}`);
                detailSection.classList.toggle('d-none');
            });
        });
    </script>
    <script>
        function showModal(id, createdAt) {
            // Set action untuk form delete, mengarah ke route penghapusan report
            let action = "{{ route('report.destroy', ':id') }}";
            action = action.replace(':id', id);
            
            
            // Ubah action form delete
            $('#form-delete-report').attr('action', action);
            
            // Tampilkan ID report dan tanggal pembuatan report di modal
            $('#report-id').text(id);
            $('#created_at-report').text(createdAt);

            // Tampilkan modal
            $('#exampleModal').modal('show');
        }
    </script>
</body>

</html>
