@extends('layouts.seeker')

@section('title', 'Pengerjaan Tes Kraepelin')

@section('content')
<style>
    /* Layout Utama */
    .kraepelin-container { 
        overflow-x: auto; 
        white-space: nowrap; 
        padding: 60px 20px; 
        background: #f8fafc; 
        height: calc(100vh - 160px); 
        scroll-behavior: smooth;
        display: flex;
        align-items: flex-start;
    }

    /* Kolom Tes */
    .test-column { 
        display: inline-block; 
        min-width: 85px; 
        vertical-align: top; 
        margin-right: 30px; 
        transition: all 0.3s ease; 
        opacity: 0.15; 
        padding: 20px 10px; 
        border-radius: 16px; 
        border: 2px solid transparent; 
        background: #f1f5f9;
    }

    .test-column.active { 
        opacity: 1; 
        transform: scale(1.05); 
        background: #ffffff; 
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); 
        border: 2px solid #4338ca; 
    }

    /* Angka Kraepelin */
    .number-box { 
        text-align: center; 
        font-size: 1.75rem; 
        font-weight: 800; 
        padding: 5px 0; 
        color: #1e293b; 
        line-height: 1;
        user-select: none;
    }

    /* Input Jawaban */
    .input-box { 
        width: 50px; 
        margin: 8px auto; 
        border: 2px solid #cbd5e1; 
        text-align: center; 
        border-radius: 10px; 
        font-weight: 900; 
        font-size: 1.4rem; 
        padding: 6px 0; 
        background: #f8fafc; 
        display: block;
        color: #4338ca;
        transition: all 0.2s;
    }

    .test-column.active .input-box { 
        background: #fff;
        border-color: #94a3b8;
    }

    .input-box:focus { 
        outline: none; 
        border-color: #4338ca; 
        background: #eef2ff !important;
        box-shadow: 0 0 0 4px rgba(67, 56, 202, 0.2); 
    }

    /* Sembunyikan Arrow Number */
    input::-webkit-outer-spin-button, 
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }

    /* Sticky Header */
    .timer-header { 
        position: sticky; 
        top: 0; 
        background: white; 
        z-index: 1000; 
        padding: 20px 40px; 
        border-bottom: 2px solid #e2e8f0; 
    }

    .text-danger-custom { color: #ef4444 !important; animation: blinker 1s linear infinite; }
    @keyframes blinker { 50% { opacity: 0; } }
</style>

@php
    $questionsData = $test->questions;
    $questionsArray = is_string($questionsData) ? json_decode($questionsData, true) : $questionsData;
@endphp

<div class="container-fluid p-0">
    <div class="timer-header d-flex justify-content-between align-items-center shadow-sm">
        <div>
            <h5 class="mb-0 fw-bold text-dark text-uppercase letter-spacing-1">Tes Kraepelin</h5>
            <p class="text-muted small mb-0">Lakukan penjumlahan dari <b>Bawah ke Atas</b>. Ketik digit terakhir saja.</p>
        </div>
        <div class="d-flex align-items-center gap-5">
            <div class="text-center border-end pe-5">
                <div class="text-muted small fw-bold text-uppercase">Kolom</div>
                <div id="progress-text" class="h4 fw-bold mb-0 text-dark">1 / {{ count($questionsArray) }}</div>
            </div>
            <div class="text-end" style="min-width: 160px;">
                <div class="text-muted small fw-bold text-uppercase">Sisa Waktu</div>
                <div id="column-timer" class="display-6 fw-bold text-primary mb-0">30s</div>
            </div>
        </div>
    </div>

    <div class="kraepelin-container" id="main-test-area">
        @foreach($questionsArray as $colIndex => $column)
            <div class="test-column" id="col-{{ $colIndex }}">
                <div class="text-center small fw-bold text-primary mb-4">KOLOM {{ $colIndex + 1 }}</div>
                
                @php 
                    $totalItems = count($column);
                @endphp

                {{-- Render: Kita mulai loop dari angka paling atas (index terakhir) menuju angka terbawah (index 0) --}}
                @for ($i = $totalItems - 1; $i >= 0; $i--)
                    <div class="number-box">{{ $column[$i] }}</div>
                    
                    {{-- Input diletakkan setelah angka (kecuali angka terakhir/paling bawah) --}}
                    {{-- Indeks row 0 akan tercipta di paling bawah visual --}}
                    @if ($i > 0)
                        <input type="number" 
                               class="input-box" 
                               maxlength="1"
                               data-col="{{ $colIndex }}" 
                               data-row="{{ $i - 1 }}"
                               id="input-{{ $colIndex }}-{{ $i - 1 }}" 
                               oninput="if (this.value.length > 1) this.value = this.value.slice(-1);"
                               disabled>
                    @endif
                @endfor
            </div>
        @endforeach
    </div>
</div>

{{-- Modal Loading Simpan --}}
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered border-0">
        <div class="modal-content bg-transparent border-0 text-center">
            <div class="spinner-border text-primary mb-3" style="width: 4rem; height: 4rem;" role="status"></div>
            <h4 class="text-white fw-bold">Sedang Mengolah Hasil...</h4>
            <p class="text-white-50">Mohon jangan tutup halaman ini.</p>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const totalColumns = {{ count($questionsArray) }};
    const testId = "{{ $test->id }}";
    const submitUrl = "{{ route('seeker.kraepelin.submit', ':testId') }}".replace(':testId', testId);
    
    let currentCol = 0;
    let columnTimeLimit =30; // 30 detik per kolom
    let timerInterval;
    let isSubmitting = false;
    let allAnswers = {};

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

    // Proteksi Tab
    window.addEventListener('beforeunload', function (e) {
        if (!isSubmitting) {
            e.preventDefault();
            e.returnValue = 'Progres tes akan hilang jika Anda keluar.';
        }
    });

    function startColumnTimer() {
        if (timerInterval) clearInterval(timerInterval);

        let timeLeft = columnTimeLimit;
        const timerDisplay = document.getElementById('column-timer');
        const progressDisplay = document.getElementById('progress-text');

        timerDisplay.innerText = timeLeft + 's';
        timerDisplay.classList.remove('text-danger-custom');
        progressDisplay.innerText = `${currentCol + 1} / ${totalColumns}`;
        
        // Reset kolom lama & Aktifkan kolom baru
        document.querySelectorAll('.test-column').forEach(el => el.classList.remove('active'));
        const activeEl = document.getElementById(`col-${currentCol}`);
        if (!activeEl) return;
        
        activeEl.classList.add('active');
        activeEl.querySelectorAll('.input-box').forEach(input => input.disabled = false);
        
        // FOKUS: Selalu ke index 0 (Input paling BAWAH di kolom tersebut)
        setTimeout(() => {
            const firstInput = document.getElementById(`input-${currentCol}-0`);
            if(firstInput) {
                firstInput.focus();
                firstInput.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
            }
        }, 100); 

        // Auto Scroll Horizontal
        activeEl.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });

        timerInterval = setInterval(() => {
            timeLeft--;
            timerDisplay.innerText = timeLeft + 's';

            if (timeLeft <= 5) timerDisplay.classList.add('text-danger-custom');

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                saveCurrentColumnData();
                moveToNextColumn();
            }
        }, 1000);
    }

    function saveCurrentColumnData() {
        const activeEl = document.getElementById(`col-${currentCol}`);
        if (!activeEl) return;
        activeEl.querySelectorAll('.input-box').forEach(input => {
            if(input.value !== "") {
                allAnswers[`${input.dataset.col}-${input.dataset.row}`] = input.value;
            }
            input.disabled = true; // Matikan input kolom lama
        });
    }

    function moveToNextColumn() {
        currentCol++;
        if (currentCol < totalColumns) {
            startColumnTimer();
        } else {
            finishTest();
        }
    }

    // Event PINDAH FOKUS KE ATAS (Row + 1)
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('input-box')) {
            if (e.target.value !== "") {
                const col = e.target.dataset.col;
                const currentRow = parseInt(e.target.dataset.row);
                
                // Cari input dengan row di atasnya (currentRow + 1)
                const nextInput = document.getElementById(`input-${col}-${currentRow + 1}`);
                if (nextInput) {
                    nextInput.focus();
                }
            }
        }
    });

    // Event BACKSPACE KE BAWAH (Row - 1)
    document.addEventListener('keydown', function(e) {
        if (e.target.classList.contains('input-box')) {
            const col = e.target.dataset.col;
            const currentRow = parseInt(e.target.dataset.row);

            if (e.key === "Backspace" && e.target.value === "") {
                const prevInput = document.getElementById(`input-${col}-${currentRow - 1}`);
                if (prevInput) prevInput.focus();
            }
        }
    });

    async function finishTest() {
        if (isSubmitting) return;
        isSubmitting = true;

        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();
        
        try {
            const response = await axios.post(submitUrl, {
                answers: allAnswers,
                total_answered: Object.keys(allAnswers).length
            });
            
            if (response.data.status === 'success') {
                window.location.href = response.data.redirect;
            }
        } catch (error) {
            alert('Gagal mengirim jawaban. Silakan hubungi admin.');
            console.error(error);
            isSubmitting = false;
            loadingModal.hide();
        }
    }

    document.addEventListener('DOMContentLoaded', startColumnTimer);
</script>
@endsection