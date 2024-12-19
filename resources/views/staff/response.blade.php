@extends('layout.layout')
@section('content')
    <div class="container bg-body-secondary">
        <div class="col-md-12">
            <div class="card p-4 my-5">
                <div class="d-flex justify-content-between">
                    <h4>{{ $report->user->email }}</h4>
                    <a href="{{ route('report.staff') }}" class="btn btn-secondary">Kembali</a>
                </div>
                <p>{{ $report->created_at->format('j F Y') }} <b>Status Tanggapan : </b><span
                        class="bg-success p-1 rounded text-light">{{ $report->response->response_status }}</span></p>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <p class="card-title"><b>{{ $report->village }}, {{ $report->subdistrict }},
                                        {{ $report->regency }}, {{ $report->province }}</b></p>
                                <p class="card-text">{{ $report->description }}</p>
                            </div>
                            <img src="{{ asset('storage/' . $report->image) }}" class="card-img-top rounded-bottom"
                                alt="Card Image">
                        </div>
                    </div>
                    <div class="col-md-6">
                        @forelse ($responses->responseProgress as $progress)
                            <div class="d-flex justify-content-between align-items-center">
                                {{-- <span class="text-danger"><i class="fa fa-trash"></i></span> --}}
                                <div class="my-2">
                                    <p class="m-0 p-0">{{ $progress->created_at->format('j F Y') }}</p>
                                    <h5>{{ $progress->histories }}</h5>
                                </div>
                                @if ($responses->response_status != "DONE")
                                <button class="btn btn-danger" onclick="showModalDelete('{{$progress->id}}', '{{$progress->histories}}')"><i class="fa fa-trash"></i></button>
                                @endif
                            </div>
                            <hr class="bg-secondary">
                        @empty
                                <h6 class="fst-italic text-center text-secondary">Tidak ada Progress.</h6>
                        @endforelse
                    </div>
                </div>
                <div>
                    @if ($responses->response_status != "DONE")
                    <div class="d-flex justify-content-end my-2">
                        <form action="{{route('progress.update', $responses->id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <button class="btn btn-success mx-2">Nyatakan Selesai</button>
                        </form>
                        <button type="button" class="btn" onclick="showModal({{ $report->id }})">
                            Tambah Progres
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Report -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" id="form-response" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ResponseModalLabel">Tambah Progres</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <small class="text-warning">Progress*</small>
                        <input type="text" name="response" id="response" class="form-control">
                        <button type="submit" class="btn btn-primary my-3">Tambah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Hapus Report -->
    <div class="modal fade" id="modal-delete-progress" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="" id="form-delete-progress" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Hapus Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin menghapus report yang dibuat pada
                        <span id="modal-histories" class="fw-bold"></span>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function showModal(id) {
            // Set action untuk form, mengarah ke route yang dituju
            let action = "{{ route('response.add', ':id') }}";
            action = action.replace(':id', id);


            // Ubah action form
            $('#form-response').attr('action', action);

            // Tampilkan modal
            $('#exampleModal').modal('show');
        }

        function showModalDelete(id, histories) {
            let action = "{{route('progress.delete', ':id')}}";
            action = action.replace(':id', id);
            
            $('#form-delete-progress').attr('action', action);

            $('#modal-histories').text(histories);

            $('#modal-delete-progress').modal('show');
        }
    </script>
@endsection
