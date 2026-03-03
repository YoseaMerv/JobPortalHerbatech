@extends('layouts.seeker')

@section('content')
<style>
    :root {
        --brand-indigo: #4338ca;
    }

    .test-header {
        background: white;
        border-bottom: 1px solid #e2e8f0;
        position: sticky;
        top: 0;
        z-index: 1020;
        margin-top: -1.5rem;
    }

    #timer-display {
        font-family: 'Courier New', Courier, monospace;
        /* Font angka agar tidak goyang */
        letter-spacing: 1px;
        background: #fff5f5;
        padding: 5px 15px;
        border-radius: 10px;
        border: 1px solid #feb2b2;
    }

    .question-card {
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        transition: transform 0.2s;
        background: white;
    }

    .question-card:hover {
        transform: translateY(-5px);
        border-color: var(--brand-indigo);
    }

    .q-num {
        width: 35px;
        height: 35px;
        background: var(--brand-indigo);
        color: white;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }

    .progress {
        height: 8px;
        border-radius: 10px;
        background: #e2e8f0;
    }

    .progress-bar {
        background: var(--brand-indigo);
        transition: width 0.4s;
    }

    .btn-submit-float {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1030;
        box-shadow: 0 10px 20px rgba(67, 56, 202, 0.3);
    }
</style>

{{-- Progress Tracker --}}
<div class="test-header shadow-sm mb-4">
    <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div>
                <h5 class="fw-bold mb-0 text-primary">Psikotes D.I.S.C.</h5>
                <span class="text-muted extra-small" id="progress-text">0 / 24 Soal Terisi</span>
            </div>
            {{-- BAGIAN TIMER --}}
            <div class="text-end">
                <div class="fw-bold text-danger fs-5" id="timer-display">15.00</div>
                <span class="text-muted extra-small uppercase">Sisa Waktu</span>
            </div>
        </div>
        <div class="progress-container">
            <div id="progress-bar"></div>
        </div>
    </div>
</div>

<div class="container pb-5">
    <form action="{{ route('seeker.disc.submit', $testResult->id) }}" method="POST" id="discForm">
        @csrf
        <div class="row">
            @foreach($questions as $q)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card question-card h-100 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="q-num me-3">{{ $q->question_number }}</div>
                            <small class="text-muted fw-bold text-uppercase">Nomor Soal</small>
                        </div>

                        @php $options = json_decode($q->question_text, true); @endphp

                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr class="text-center small text-muted">
                                    <th>P</th>
                                    <th>K</th>
                                    <th class="text-start ps-3">Pernyataan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($options as $index => $text)
                                <tr>
                                    <td class="text-center">
                                        <input type="radio" name="answers[{{ $q->question_number }}][p]" value="{{ $index + 1 }}" class="form-check-input disc-input" required data-no="{{ $q->question_number }}" data-row="{{ $index + 1 }}">
                                    </td>
                                    <td class="text-center">
                                        <input type="radio" name="answers[{{ $q->question_number }}][k]" value="{{ $index + 1 }}" class="form-check-input disc-input" required data-no="{{ $q->question_number }}" data-row="{{ $index + 1 }}">
                                    </td>
                                    <td class="small ps-3 text-dark" style="line-height: 1.3;">{{ $text }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 fw-bold btn-submit-float border-0" style="background: #4338ca;">
            Kirim Jawaban <i class="fas fa-paper-plane ms-2"></i>
        </button>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('disc-input')) {
            const no = e.target.dataset.no;
            const row = e.target.dataset.row;
            const isP = e.target.name.includes('[p]');

            const oppositeType = isP ? 'k' : 'p';
            const oppositeRadio = document.querySelector(`input[name="answers[${no}][${oppositeType}]"][data-row="${row}"]`);

            if (e.target.checked && oppositeRadio && oppositeRadio.checked) {
                alert('Peringatan: Pernyataan yang sama tidak boleh dipilih untuk P dan K!');
                e.target.checked = false;
                return;
            }
            updateProgress();
        }
    });

    function updateProgress() {
        let done = 0;
        for (let i = 1; i <= 24; i++) {
            if (document.querySelector(`input[name="answers[${i}][p]"]:checked`) &&
                document.querySelector(`input[name="answers[${i}][k]"]:checked`)) done++;
        }
        document.getElementById('progBar').style.width = (done / 24 * 100) + '%';
        document.getElementById('progText').innerText = `${done} / 24 Selesai`;
    }
</script>
<script>
    // Konfigurasi Waktu (Dalam Menit)
    // DISC: 15, MSDT: 30, PAPI: 30
    const durationInMinutes = 15;
    let timeInSeconds = durationInMinutes * 60;

    const timerDisplay = document.getElementById('timer-display');
    const discForm = document.getElementById('discForm');

    const countdown = setInterval(function() {
        let minutes = Math.floor(timeInSeconds / 60);
        let seconds = timeInSeconds % 60;

        // Format tampilan 00:00
        seconds = seconds < 10 ? '0' + seconds : seconds;
        minutes = minutes < 10 ? '0' + minutes : minutes;

        timerDisplay.innerHTML = `${minutes}:${seconds}`;

        if (timeInSeconds <= 0) {
            clearInterval(countdown);
            alert("Waktu habis! Jawaban Anda akan dikirim secara otomatis.");
            // Matikan pengecekan validasi saat auto-submit agar tidak terhalang alert "Belum Lengkap"
            discForm.onsubmit = null;
            discForm.submit();
        }

        // Beri warna merah berkedip jika waktu sisa 1 menit
        if (timeInSeconds <= 60) {
            timerDisplay.classList.add('text-blink');
        }

        timeInSeconds--;
    }, 1000);

    // Tambahkan CSS sederhana untuk efek berkedip
    const style = document.createElement('style');
    style.innerHTML = `
        .text-blink {
            animation: blinker 1s linear infinite;
        }
        @keyframes blinker {
            50% { opacity: 0; }
        }
    `;
    document.head.appendChild(style);
</script>
@endpush
@endsection