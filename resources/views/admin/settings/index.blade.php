@extends('layouts.admin')

@section('title', 'Pengaturan - WAKANDE')

@section('content')
<div class="container-fluid px-4">
    <h1 class="h3 fw-bold mb-4">Pengaturan</h1>

    <div class="admin-card p-4 rounded-3">
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Aplikasi</label>
                <input type="text" name="app_name" class="form-control" value="{{ env('APP_NAME') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="app_description" class="form-control" rows="3">{{ env('APP_DESCRIPTION') }}</textarea>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input type="checkbox" name="maintenance_mode" class="form-check-input" value="1" {{ app()->isDownForMaintenance() ? 'checked' : '' }}>
                    <label class="form-check-label">Mode Maintenance</label>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
        </form>
    </div>
</div>
@endsection
