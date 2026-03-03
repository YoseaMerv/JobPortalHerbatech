@extends('layouts.seeker')

@section('title', 'Instruksi Tes DISC')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white p-4 border-bottom text-center">
                    <div class="icon-box bg-indigo-subtle text-indigo rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; background-color: #e0e7ff; color: #4338ca;">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                    <h4 class="fw-bold mb-0">Instruksi Tes D.I.S.C.</h4>
                </div>

                <div class="card-body p-4 p-md-5">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2 text-primary"></i>Cara Mengerjakan:</h6>
                    <p class="text-muted">Setiap nomor di bawah ini memuat 4 (empat) kalimat gambaran diri. Tugas Anda adalah:</p>

                    <ul class="list-group list-group-flush mb-4">
                        <li class="list-group-item border-0 px-0 d-flex align-items-start">
                            <span class="badge bg-primary rounded-pill me-3 mt-1">1</span>
                            <span>Pilihlah satu kalimat yang <b>PALING (P)</b> menggambarkan diri Anda.</span>
                        </li>
                        <li class="list-group-item border-0 px-0 d-flex align-items-start">
                            <span class="badge bg-danger rounded-pill me-3 mt-1">2</span>
                            <span>Pilihlah satu kalimat yang <b>KURANG (K)</b> menggambarkan diri Anda.</span>
                        </li>
                    </ul>

                    <div class="alert alert-warning border-0 rounded-4 p-3 mb-4">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle mt-1 me-3"></i>
                            <div>
                                <h6 class="alert-heading fw-bold mb-1">PENTING:</h6>
                                <p class="small mb-0">Setiap nomor <b>HARUS</b> memiliki tepat satu pilihan di kolom <b>P</b> dan satu pilihan di kolom <b>K</b>. Pilihan untuk P dan K tidak boleh berada pada baris kalimat yang sama.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-light p-4 rounded-4 border mb-4">
                        <h6 class="fw-bold mb-3">Contoh:</h6>
                        <table class="table table-sm table-bordered bg-white text-center mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>P</th>
                                    <th>K</th>
                                    <th class="text-start ps-3">Gambaran Diri</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><i class="fas fa-check text-primary"></i></td>
                                    <td></td>
                                    <td class="text-start ps-3 small">Petualang, Mengambil resiko</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><i class="fas fa-check text-danger"></i></td>
                                    <td class="text-start ps-3 small">Toleran, Menghormati</td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="extra-small text-muted mt-2 mb-0 italic">*Artinya Anda merasa "Petualang" adalah yang paling mirip dengan Anda, dan "Toleran" adalah yang paling tidak mirip.</p>
                    </div>

                    <div class="d-grid mt-5">
                        <a href="{{ route('seeker.disc.start', $application->id) }}" class="btn btn-primary btn-lg fw-bold rounded-pill shadow-sm py-3">
                            Mulai Tes Sekarang <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection