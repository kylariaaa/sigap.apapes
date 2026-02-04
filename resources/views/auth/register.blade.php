@extends('layouts.master')

@section('title', 'Daftar Akun Warga')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Registrasi Warga Baru</h5>
            </div>

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <div class="card-body">
                <form action="{{ route('register.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">NIK</label>
                        <input
                            type="number"
                            name="nik"
                            class="form-control"
                            placeholder="Masukkan NIK"
                            value="{{ old('nik') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Nama Sesuai KTP"
                            value="{{ old('name') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            placeholder="email@contoh.com"
                            value="{{ old('email') }}"
                            required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            placeholder="Buat Username Unik"
                            value="{{ old('username') }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Minimal 6 Karakter"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">No. Telepon</label>
                        <input
                            type="text"
                            name="telp"
                            class="form-control"
                            placeholder="08..."
                            value="{{ old('telp') }}"
                            required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">
                        DAFTAR SEKARANG
                    </button>

                    <div class="text-center mt-3">
                        <small>
                            Sudah punya akun?
                            <a href="{{ route('login') }}">Login di sini</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
