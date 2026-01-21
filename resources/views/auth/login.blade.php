@extends('layouts.master')

@section('title', 'Login Pengguna')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">

                <div class="card-header">
                    Silakan Masuk
                </div>

                <div class="card-body">
                    <form action="{{ route('login.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">
                                Password
                            </label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            LOGIN
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
