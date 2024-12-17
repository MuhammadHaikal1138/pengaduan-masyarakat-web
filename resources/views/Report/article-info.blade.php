@extends('layout.layout')

@section('content')
    <a href="{{route('report.article')}}">Back</a>
    <style>
        /* Handle scroll bar */
        .scrollable::-webkit-scrollbar-thumb {
            background: #38b2ac;
            /* Warna handle */
            border-radius: 10px;
            /* Membuat sudut melengkung */
        }

        /* Tambahan untuk browser lain */
        .scrollable {
            scrollbar-width: thin;
            /* Untuk Firefox */
            scrollbar-color: #38b2ac #f0f0f0;
            /* Handle dan track */
        }
    </style>

    <div class="container my-4">

        <div class="row">
            <!-- Left column with posts -->
            <div class="col-lg-8 overflow-auto max-height-500px scrollable">
                <div class="card mb-4">
                    <div class="card-body d-flex">
                        <!-- Gambar -->
                        <img class="w-25 rounded" 
                            src="{{ $report->image ? asset('storage/' . $report->image) : 'https://via.placeholder.com/150' }}" 
                            width="150" height="100" alt="Report Image" />
                        
                        <div class="ml-3">
                            <!-- Judul -->
                            <p class="font-weight-bold">
                                {{ $report->created_at->format('j F Y') }}
                            </p>
                            <p>{{ $report->description }}</p>
                            <div class="bg-warning my-4 py-2 w-36 rounded text-center">
                                {{ $report->type }}
                            </div>

                            <!-- Statistik -->
                            <div class="text-muted text-sm mt-2">
                                <p class="mb-1">Email Pengguna: {{ $report->user->email ?? 'Anonim' }}</p>
                                <p class="mb-1">{{ $report->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="font-weight-bold">Komentar</h5>
                        @foreach ($comment as $comment)
                        <div class="mb-4 border p-3 rounded shadow-sm">
                            <p class="text-primary">{{ $comment->user->email }}</p>
                            <p class="text-muted">{{ $comment->created_at->diffForHumans() }} - {{ \Carbon\Carbon::parse($report->created_at)->format('d F Y') }}</p>
                            <p>{{ $comment->comment }}</p>
                        </div>
                        @endforeach
                        <form action="{{ route('comment.store', $report->id) }}" method="POST">
                            @csrf
                            <div class="d-flex">
                                <i class="fas fa-user text-xl mt-2"></i>
                                <textarea name="comment" id="comment" class="form-control flex-grow-1 border-gray-300 p-2" rows="4"></textarea>
                            </div>
                            <div class="text-right mt-2">
                                <button class="btn btn-teal">Buat Komentar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right sidebar with information -->
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="font-weight-bold mb-2">Informasi Pembuatan Pengaduan</h5>
                        <ol class="list-unstyled">
                            <li>Pengaduan bisa dibuat hanya jika Anda telah membuat akun sebelumnya.</li>
                            <li>Keseluruhan data pada pengaduan bernilai <strong>BENAR dan DAPAT DIPERTANGGUNG JAWABKAN</strong>.</li>
                            <li>Seluruh bagian data perlu diisi.</li>
                            <li>Pengaduan Anda akan ditanggapi dalam 2x24 Jam.</li>
                            <li>Periksa tanggapan Kami, pada <strong>Dashboard</strong> setelah Anda <strong>Login</strong>.</li>
                            <li>Pembuatan pengaduan dapat dilakukan pada halaman berikut: <a href="#" class="text-primary">Ikuti Tautan</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @if (Session::get('success'))
        <div id="alert" class="fixed-bottom right-0 m-4 alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#alert').alert('close');
            });
        </script>
        @endif

    </div>
@endsection
