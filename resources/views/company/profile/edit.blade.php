@extends('layouts.company')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('company.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <h5 class="text-primary mb-4">Informasi Akun</h5>
                    <div class="mb-3 row">
                        <label for="name" class="col-sm-3 col-form-label">Nama Kontak Persona</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                    </div>

                    <hr class="my-4">

                    <h5 class="text-primary mb-4">Informasi Perusahaan</h5>
                    
                    <div class="mb-3 row">
                        <label for="logo" class="col-sm-3 col-form-label">Logo Perusahaan</label>
                        <div class="col-sm-9">
                            @if($company->logo)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="img-thumbnail" width="100">
                                </div>
                            @endif
                            <input class="form-control" type="file" id="logo" name="logo">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="company_name" class="col-sm-3 col-form-label">Nama Perusahaan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $company->company_name) }}" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="company_email" class="col-sm-3 col-form-label">Email Perusahaan</label>
                        <div class="col-sm-9">
                            <input type="email" class="form-control" id="company_email" name="company_email" value="{{ old('company_email', $company->company_email) }}" required>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="company_phone" class="col-sm-3 col-form-label">Telepon</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="company_phone" name="company_phone" value="{{ old('company_phone', $company->company_phone) }}">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="company_website" class="col-sm-3 col-form-label">Situs Web</label>
                        <div class="col-sm-9">
                            <input type="url" class="form-control" id="company_website" name="company_website" value="{{ old('company_website', $company->company_website) }}" placeholder="https://contoh.com">
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="company_address" class="col-sm-3 col-form-label">Alamat</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="company_address" name="company_address" rows="3">{{ old('company_address', $company->company_address) }}</textarea>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="company_description" class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="company_description" name="company_description" rows="5" required>{{ old('company_description', $company->company_description) }}</textarea>
                            <div class="form-text">Jelaskan perusahaan Anda, budaya, dan apa yang menjadikannya tempat yang luar biasa untuk bekerja.</div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                         <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
