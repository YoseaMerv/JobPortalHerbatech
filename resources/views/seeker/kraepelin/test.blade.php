@extends('layouts.seeker')

@section('title', 'Pengerjaan Tes Kraepelin')

@section('content')
<style>
    .kraepelin-container { 
        overflow-x: auto; 
        white-space: nowrap; 
        padding: 40px 20px; 
        background: #f8fafc; 
        height: calc(100vh - 160px); 
        scroll-behavior: smooth;
    }
    .test-column { 
        display: inline-block; 
        width: 75px; 
        vertical-align: top; 
        margin-right: 20px; 
        transition: all 0.3s ease; 
        opacity: 0.2; 
        padding: 15px 5px; 
        border-radius: 12px; 
        border: 2px solid transparent; 
    }
    .test-column.active { 
        opacity: 1; 
        transform: scale(1.02); 
        background: #ffffff; 
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1); 
        border: 2px solid #4338ca; 
    }
    .number-box { 
        text-align: center; 
        font-size: 1.5rem; 
        font-weight: 800; 
        padding: 4px 0; 
        color: #1e293b; 
        line-height: 1; 
    }
    .input-box { 
        width: 45px; 
        margin: 5px auto; 
        border: 2px solid #e2e8f0; 
        text-align: center; 
        border-radius: 8px; 
        font-weight: 900; 
        font-size: 1.2rem; 
        padding: 4px 0; 
        background: #f1f5f9; 
        display: block; 
    }
    .test-column.active .input-box { 
        background: #ffffff; 
        border-color: #cbd5e1; 
    }
    .input-box:focus { 
        outline: none; 
        border-color: #4338ca; 
        box-shadow: 0 0 0 3px rgba(67, 56, 202, 0.15); 
    }
    input::-webkit-outer-spin-button, 
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }

    .timer-header { 
        position: sticky; 
        top: 0; 
        background: white; 
        z-index: 100; 
        padding: 20px 40px; 
        border-bottom: 2px solid #e2e8f0; 
    }
</style>

@php
    $questionsData = $test->questions;
    $questionsArray = is_string($questionsData) ? json_decode($questionsData, true) : $questionsData;
@endphp

<div class="container-fluid p-0">
    <div class="timer-header d-flex justify-content-between align-items-center shadow-sm">
        <div>
            <h5 class="mb-0 fw-bold text-dark text-uppercase">Tes Kraepelin</h5>
            <p class="text-muted small mb-0">Jumlahkan dua angka dari bawah ke atas. Ketik digit terakhir saja.</p>
        </div>
        <div class="d-flex align-items-center gap-5">
            <div class="text-center border-end pe-5">
                <div class="text-muted small fw-bold text-uppercase">Kolom</div>
                <div id="progress-text" class="h4 fw-bold mb-0 text-dark">1 / {{ count($questionsArray) }}</div>
            </div>
            <div class="text-end" style="min-width: 140px;">
                <div class="text-muted small fw-bold text-uppercase">Sisa Waktu Kolom</div>
                <div id="column-timer" class="display-6 fw-bold text-primary mb-0">45s</div>
            </div>
        </div>
    </div>

    <div class="kraepelin-container" id="main-test-area">
        @foreach($questionsArray as $colIndex => $column)
            <div class="test-column" id="col-{{ $colIndex }}">
                <div class="text-center small fw-bold text-primary mb-3">#{{ $colIndex + 1 }}</div>
                @php 
                    $reversedCol = array_reverse($column, true);
                    $totalItems = count($column);
                @endphp
                @foreach($reversedCol as $visualRowIndex => $num)
                    <div class="number-box">{{ $num }}</div>
                    @if(!$loop->last)
                        @php 
                            $originalIndex = ($totalItems - 1) - $visualRowIndex; 
                        @endphp
                        <input type="number" class="input-box" maxlength="1"
                               oninput="if (this.value.length > 1) this.value = this.value.slice(-1);"
                               data-col="{{ $colIndex }}" 
                               data-row="{{ $originalIndex }}"
                               id="input-{{ $colIndex }}-{{ $originalIndex }}" 
                               disabled>
                    @endif
                @endforeach
            </div>
        @endforeach
    </div>
</div>

{{-- Modal Loading --}}
<div class="modal fade" id="loadingModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered border-0">
        <div class="modal-content bg-transparent border-0 text-center">
            <div class="spinner-border text-white mb-3" style="width: 3rem; height: 3rem;" role="status"></div>
            <h5 class="text-white fw-bold">Menyimpan Hasil Tes Anda...</h5>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const totalColumns = {{ count($questionsArray) }};
    const testId = "{{ $test->id }}";
    const submitUrl = "{{ route('seeker.kraepelin.submit', ':testId') }}".replace(':testId', testId);
    
    let currentCol = 0;
    let columnTimeLimit = 1; // Waktu diubah menjadi 45 detik
    let timerInterval;
    let isSubmitting = false;
    let allAnswers = {};

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;

    window.addEventListener('beforeunload', function (e) {
        if (!isSubmitting) {
            e.preventDefault();
            e.returnValue = 'Hati-hati! Progres tes Anda yang belum terkirim akan hilang.';
        }
    });

    function startColumnTimer() {
        if (timerInterval) clearInterval(timerInterval);

        let timeLeft = columnTimeLimit;
        const timerDisplay = document.getElementById('column-timer');
        const progressDisplay = document.getElementById('progress-text');

        timerDisplay.innerText = timeLeft + 's';
        progressDisplay.innerText = `${currentCol + 1} / ${totalColumns}`;
        
        document.querySelectorAll('.test-column').forEach(el => el.classList.remove('active'));
        
        const activeEl = document.getElementById(`col-${currentCol}`);
        if (!activeEl) return;
        
        activeEl.classList.add('active');
        activeEl.querySelectorAll('.input-box').forEach(input => input.disabled = false);
        
        // PENTING: Fokus awal ke input paling BAWAH.
        // Dalam HTML yang Anda buat, input paling bawah adalah index 0 (karena array di-reverse)
        const firstInput = document.getElementById(`input-${currentCol}-0`);
        if(firstInput) firstInput.focus();
        
        activeEl.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });

        timerInterval = setInterval(() => {
            timeLeft--;
            timerDisplay.innerText = timeLeft + 's';

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
        });
    }

    function moveToNextColumn() {
        const oldCol = document.getElementById(`col-${currentCol}`);
        if(oldCol) {
            oldCol.classList.remove('active');
            oldCol.querySelectorAll('.input-box').forEach(input => input.disabled = true);
        }
        
        currentCol++;
        if (currentCol < totalColumns) {
            startColumnTimer();
        } else {
            finishTest();
        }
    }

    async function finishTest() {
        if (isSubmitting) return;
        isSubmitting = true;

        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();
        document.body.style.pointerEvents = 'none';

        try {
            const response = await axios.post(submitUrl, {
                answers: allAnswers,
                total_answered: Object.keys(allAnswers).length
            });
            
            if (response.data.status === 'success') {
                window.location.href = response.data.redirect;
            }
        } catch (error) {
            console.error("Submission Error Detail:", error.response?.data);
            alert('Gagal menyimpan hasil: ' + (error.response?.data?.message || 'Error Server 500'));
            
            isSubmitting = false;
            document.body.style.pointerEvents = 'auto';
            loadingModal.hide();

        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        startColumnTimer();
    });

    // ---------------------------------------------------------
    // EVENT LISTENER UNTUK PINDAH FOKUS OTOMATIS
    // ---------------------------------------------------------
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('input-box')) {
            // Ambil angka terakhir jika terisi lebih dari 1 digit
            let val = e.target.value;
            if (val.length > 1) {
                e.target.value = val.slice(-1);
            }

            // Jika input sudah terisi 1 angka, pindah ke field atasnya
            if (e.target.value.length === 1) {
                const col = e.target.dataset.col;
                const currentRow = parseInt(e.target.dataset.row);
                
                // Cari input di ATAS-nya (index bertambah karena urutan dari bawah ke atas)
                const nextInput = document.getElementById(`input-${col}-${currentRow + 1}`);
                
                if (nextInput) {
                    nextInput.focus();
                }
            }
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.target.classList.contains('input-box')) {
            const col = e.target.dataset.col;
            const currentRow = parseInt(e.target.dataset.row);


            if (e.key === "Backspace" && e.target.value === "") {
                e.preventDefault(); 
                const prevInput = document.getElementById(`input-${col}-${currentRow - 1}`);
                if (prevInput) prevInput.focus();
            }
        }
    });
</script>
@endsection