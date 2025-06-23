@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-title mb-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-xl-6">
                            <div class="page-title-content">
                                <h3>Edit Pengguna</h3>
                                <p class="mb-0">Perbarui data pengguna di bawah ini.</p>
                            </div>
                        </div>
                        <div class="col-xl-auto">
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali ke List</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('users.update', $user->id) }}" method="POST" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input id="username" type="text" name="username"
                                    value="{{ old('username', $user->username) }}"
                                    class="form-control @error('username') is-invalid @enderror" required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password Baru</label>
                                <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror">
                                <small class="text-muted">Kosongkan jika tidak ingin mengubah password.</small>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                <select id="role" name="role"
                                    class="form-select @error('role') is-invalid @enderror" required>
                                    <option value="superadmin" {{ $user->role === 'superadmin' ? 'selected' : '' }}>
                                        Superadmin</option>
                                    <option value="mayor" {{ $user->role === 'mayor' ? 'selected' : '' }}>Walikota</option>
                                    <option value="deputy_mayor" {{ $user->role === 'deputy_mayor' ? 'selected' : '' }}>
                                        Wakil Walikota</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="full_name" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input id="full_name" type="text" name="full_name"
                                    value="{{ old('full_name', $user->detail->full_name ?? '') }}"
                                    class="form-control @error('full_name') is-invalid @enderror" required>
                                @error('full_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email" name="email"
                                    value="{{ old('email', $user->detail->email ?? '') }}"
                                    class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Nomor Telepon</label>
                                <input id="phone_number" type="text" name="phone_number"
                                    value="{{ old('phone_number', $user->detail->phone_number ?? '') }}"
                                    class="form-control @error('phone_number') is-invalid @enderror">
                                @error('phone_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">Batal</a>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
