@extends('layout.layout')

@section('content')
    <div class="container my-4 d-flex justify-content-center">
        <div class="card shadow-lg" style="width: 100%; max-width: 1200px;">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Daftar Pengaduan</h3>
                <div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Export (.xlsx)
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <!-- Export Semua -->
                        <li>
                            <a class="dropdown-item" href="{{ route('export', ['filter' => 'all']) }}">
                                Export Semua
                            </a>
                        </li>
                        <!-- Export Berdasarkan Tanggal -->
                        <li>
                            <form action="{{ route('export') }}" method="GET" class="px-3 py-2">
                                <label for="date" class="form-label">Pilih Tanggal:</label>
                                <input type="date" name="date" id="date" class="form-control mb-2" required>
                                <button type="submit" name="filter" value="date" class="btn btn-primary w-100">
                                    Export
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Gambar & Pengirim</th>
                                <th scope="col">Lokasi & Tanggal</th>
                                <th scope="col">Deskripsi</th>
                                <th scope="col">Jumlah Vote
                                    {{-- DESCENDING --}}
                                    <a href="{{route('report.staff', ['sort' => 'voting', 'order' => 'desc'])}}">
                                        <i class="fas fa-caret-up ml-1 cursor-pointer {{ $order == 'desc' && $sortBy == 'voting' ? 'text-success' : ''}}"></i>
                                    </a>
                                    {{-- ASCENDING --}}
                                    <a href="{{route('report.staff', ['sort' => 'voting', 'order' => 'asc'])}}">
                                        <i class="fas fa-caret-down ml-1 cursor-pointer {{ $order == 'asc' && $sortBy == 'voting' ? 'text-success' : ''}}"></i>
                                    </a>
                                </th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reports as $report)
                                <tr>
                                    <td class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $report->image) }}" class="rounded" width="50"
                                            height="50" alt="Gambar Pengaduan">
                                        <div class="ml-3">
                                            <strong>{{ $report->user->email }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        <p>{{ $report->village }}, {{ $report->subdistrict }}, {{ $report->regency }}</p>
                                        <p>{{ $report->created_at->format('d F Y') }}</p>
                                    </td>
                                    <td>{{ Str::limit($report->description, 50) }}</td>
                                    <td>{{ count(json_decode($report->voting ?? '[]', true))}}</td>
                                    <td class="align-items-center justify-content-center">
                                        <div class="dropdown">
                                            <button class="btn btn-secondary btn-sm dropdown-toggle py-1 px-3" type="button"
                                                id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                Aksi
                                            </button>
                                            <form action="">
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <button type="button" class="btn" onclick="showModal({{ $report->id }})">
                                                            Tindak Lanjuti
                                                        </button>
                                                    </li>
                                                </ul>
                                            </form>                                            
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Hapus Report -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" id="form-response" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Tindak Lanjut</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <small class="text-warning">Tanggapan*</small>
                        <select class="form-control" name="response_status" id="response">
                            <option value="" selected disabled>Pilih Tanggapan</option>
                            <option value="REJECT">Tolak</option>
                            <option value="ON_PROCESS">Proses Penyelesaian/Perbaikan</option>
                        </select>
                        <button type="submit" class="btn btn-primary my-3">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showModal(id) {
            // Set action untuk form, mengarah ke route yang dituju
            let action = "{{ route('response.staff', ':id') }}";
            action = action.replace(':id', id);
            
            
            // Ubah action form delete
            $('#form-response').attr('action', action);
            
            // Tampilkan ID report 
            $('#report-id').text(id);

            // Tampilkan modal
            $('#exampleModal').modal('show');
        }
    </script>
@endsection
