@extends('layouts.app')

@section('title', 'Antrian | CV. Kassab Syariah')

@section('username', Auth::user()->name)

@section('page', 'User Superadmin')

@section('breadcrumb', 'Pengaturan Pengguna')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Pengaturan Pengguna</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modal-edit{{ $user->id }}">Edit</button>
                                        {{-- Modal Edit --}}
                                        <div class="modal fade" id="modal-edit{{ $user->id }}">
                                            <div class="modal-dialog">
                                              <div class="modal-content">
                                                <div class="modal-header">
                                                  <h4 class="modal-title">Edit Pengguna</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('user.update', $user->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <label for="roleSelect"></label>
                                                        <select class="custom-select rounded-0" id="roleSelect" name="role">
                                                            <option selected>Pilih Role</option>
                                                            <option value="staff">Staff</option>
                                                            <option value="superadmin">Superadmin</option>
                                                            <option value="admin">Admin</option>
                                                            <option value="sales">Sales</option>
                                                            <option value="dokumentasi">Dokumentasi</option>
                                                            <option value="keuangan">Keuangan</option>
                                                            <option value="pimpinan">Pimpinan</option>
                                                        </select>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                  <button type="submit" class="btn btn-primary">Simpan</button>
                                                </div>
                                                </form>
                                              </div>
                                              <!-- /.modal-content -->
                                            </div>
                                            <!-- /.modal-dialog -->
                                          </div>
                                          <!-- /.modal -->
                                        {{-- End Modal Edit --}}
                                        <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin ingin menghapus data ini?')">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('user.create') }}" class="btn btn-primary">Tambah</a>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
