@extends('layout.layout')

@section('content')
    <div class="container my-3">
        <div class="card p-2">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>No</th>
                                <th>Email</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm">Reset</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <div class="text-center text-danger">Tidak ada Data</div>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <div class="card p-2">
                        <form action="{{route('headstaff.store')}}"  method="POST">
                            @csrf
                            <label for="email">Email*</label>
                            <input type="email" name="email" id="email" class="form-control">
                            <label for="password">Password*</label>
                            <input type="password" name="password" id="password" class="form-control">
                            <button type="submit" class="btn btn-primary my-2">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endsection
