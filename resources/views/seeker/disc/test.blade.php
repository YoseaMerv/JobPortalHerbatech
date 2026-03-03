@extends('layouts.seeker')

@section('content')
<div class="container py-5">
    <form action="{{ route('seeker.disc.submit', $testResult->id) }}" method="POST" id="discForm">
        @csrf
        <div class="card shadow-sm rounded-4 border-0">
            <div class="card-header bg-white p-4 border-bottom">
                <h4 class="fw-bold mb-1">D.I.S.C. Test</h4>
                <p class="text-muted small mb-0">Pilih satu pernyataan yang <b>PALING (P)</b> menggambarkan diri Anda, dan satu yang <b>KURANG (K)</b> menggambarkan diri Anda.</p>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    @foreach($questions as $q)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <table class="table table-bordered align-middle text-center small">
                            <thead class="table-dark">
                                <tr>
                                    <th width="15%">No. {{ $q->question_number }}</th>
                                    <th width="15%">P</th>
                                    <th width="15%">K</th>
                                    <th class="text-start">Gambaran Diri</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                // Asumsi konten soal disimpan sebagai array [ 'kalimat1', 'kalimat2', ... ]
                                $options = json_decode($q->question_text, true);
                                @endphp
                                @foreach($options as $index => $text)
                                <tr>
                                    <td class="bg-light fw-bold">{{ $index + 1 }}</td>
                                    <td>
                                        <input type="radio" name="answers[{{ $q->question_number }}][p]"
                                            value="{{ $index + 1 }}"
                                            class="form-check-input p-radio-{{ $q->question_number }}"
                                            required data-no="{{ $q->question_number }}" data-row="{{ $index + 1 }}">
                                    </td>
                                    <td>
                                        <input type="radio" name="answers[{{ $q->question_number }}][k]"
                                            value="{{ $index + 1 }}"
                                            class="form-check-input k-radio-{{ $q->question_number }}"
                                            required data-no="{{ $q->question_number }}" data-row="{{ $index + 1 }}">
                                    </td>
                                    <td class="text-start small">{{ $text }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="card-footer bg-white p-4 border-top text-end">
                <button type="submit" class="btn btn-primary px-5 fw-bold rounded-pill shadow-sm">Kirim Hasil Tes</button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('form-check-input')) {
        const questionNo = e.target.getAttribute('data-no');
        const row = e.target.getAttribute('data-row');
        const isP = e.target.name.includes('[p]');
        
        // Cari radio button lawannya di baris yang sama
        const oppositeType = isP ? 'k' : 'p';
        const oppositeRadio = document.querySelector(`input[name="answers[${questionNo}][${oppositeType}]"][data-row="${row}"]`);

        if (e.target.checked && oppositeRadio.checked) {
            alert('Peringatan: Anda tidak boleh memilih pernyataan yang sama untuk PALING (P) dan KURANG (K)!');
            e.target.checked = false; // Batalkan pilihan terakhir
        }
    }
});

// Validasi saat submit: pastikan semua nomor sudah diisi P dan K-nya
document.getElementById('discForm').onsubmit = function() {
    // Tambahkan logika pengecekan completeness jika diperlukan
    return confirm('Apakah Anda yakin ingin mengirim hasil tes DISC ini?');
};
</script>
@endpush