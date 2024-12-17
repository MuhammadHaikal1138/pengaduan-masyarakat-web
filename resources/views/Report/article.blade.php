@extends('layout.layout')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-end">
            <a href="{{ route('report.create') }}" class="btn btn-primary my-2">Buat Pengaduan</a>
        </div>
        <div class="d-flex align-items-center mb-4">
            <form action="{{route('report.article')}}" method="POST" class="col-md-12 d-flex">
                @csrf
                @method('GET')
                <select class="form-select" id="province" name="province" required>
                    <option >Pilih Provinsi</option>
                </select>
                <button type="submit" class="btn btn-primary">Cari</button>
            </form>
        </div>

        <div class="row">
            @forelse ( $reports as $report)
            <div class="col-md-5 mb-4">
                <div class="card">
                    <img src="{{ asset('storage/' . $report->image) }}" class="card-img-top" alt="...">
                    <div class="card-body">
                        <a href="{{route('report.article.info', $report->id)}}" class="card-title text-underline">{{Str::limit($report->description, 50)}}</a>
                        <p class="card-text">{{$report->user->email}}</p> 
                        <div class="d-flex justify-content-between">
                            <div class="d-flex">
                                <small>{{$report->viewers}}</small>
                                <i class="fas fa-heart ms-3 me-1"></i>
                                <span id="voting-{{ $report->id }}">{{ count(json_decode($report->voting, true) ?? []) }}</span>
                            </div>
                            <button class="like-btn btn ms-4 d-flex align-items-center" data-id="{{ $report->id }}" data-action="like">
                                <i class="fas fa-heart text-muted"></i>
                                <span class="ms-1 text-muted">vote</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Informasi Pembuatan Pengaduan</h5>
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item">Pengaduan bisa dibuat hanya jika Anda telah membuat akun sebelumnya,
                            </li>
                            <li class="list-group-item">Keseluruhan data pada pengaduan bernilai <strong>BENAR dan DAPAT
                                    DIPERTANGGUNG JAWABKAN</strong>,</li>
                            <li class="list-group-item">Seluruh bagian data perlu diisi</li>
                            <li class="list-group-item">Pengaduan Anda akan ditanggapi dalam 2x24 Jam,</li>
                            <li class="list-group-item">Periksa tanggapan Kami, pada <strong>Dashboard</strong> setelah Anda
                                <strong>Login</strong>,</li>
                            <li class="list-group-item">Pembuatan pengaduan dapat dilakukan pada halaman berikut: <a
                                    class="text-primary" href="{{ route('report.create') }}">Ikuti Tautan</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
         //logika tombol like/voting
         $(document).ready(function () {
                    $('.like-btn').on('click', function () {
                        var reportId = $(this).data('id');
                        var action = $(this).data('action');
                        var $button = $(this); // Referensi tombol yang diklik

                        $.ajax({
                            url: `/reports/${reportId}/vote`,
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                action: action,
                            },
                        })
                        .then(function (response) {
                            // Update jumlah voting
                            $('#voting-' + reportId).text(response.voting);

                            // Toggle action (like <-> unlike) dan warna ikon hati
                            if (action === 'like') {
                                $button.find('i').removeClass('text-muted').addClass('text-danger'); // Ubah warna ke merah
                                $button.data('action', 'unlike'); // Ubah action menjadi unlike
                            } else if (action === 'unlike') {
                                $button.find('i').removeClass('text-danger').addClass('text-muted'); // Ubah warna ke default (abu-abu)
                                $button.data('action', 'like'); // Ubah action menjadi like
                            }
                        })
                        .catch(function (xhr) {
                            alert(xhr.responseJSON?.error || 'An error occurred. Please try again.');
                        });
                    });
                });
    </script>
    <script>
        $.ajax({
            method: "GET",
            url: "https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json",
            dataType: "json",
            success: function (data) {
                data.forEach(function (provinsi) {
                    $('#province').append(`<option value="${provinsi.name}" data-name="${provinsi.name}">${provinsi.name}</option>`);
                });
            }
        });
    </script>
@endsection
