@extends('layout.layout')
@section('content')
<div class="container bg-body-secondary">
    <div class="col-md-12">
        <div class="card p-4 my-5">
            <div class="d-flex justify-content-between">
                <h4>{{$report->user->email}}</h4>
                <a href="{{route('report.staff')}}" class="btn btn-secondary">Kembali</a>
            </div>
            <p>{{ $report->created_at->format('j F Y') }} <b>Status Tanggapan : </b><span class="bg-success p-1 rounded text-light">{{$report->response->response_status}}</span></p>
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <p class="card-title"><b>{{ $report->village }}, {{ $report->subdistrict }}, {{ $report->regency }}, {{ $report->province }}</b></p>
                            <p class="card-text">{{$report->description}}</p>
                        </div>
                        <img src="{{asset('storage/' . $report->image)}}" class="card-img-top rounded-bottom" alt="Card Image">
                    </div>
                </div>
                <div class="col-md-6">
                    
                </div>
            </div>
            <div>
                <div class="d-flex justify-content-end my-2">
                    <a href="#" class="btn btn-success mx-2">Nyatakan Selesai</a>
                    <a href="#" class="btn btn-secondary">Tambah Progres</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection