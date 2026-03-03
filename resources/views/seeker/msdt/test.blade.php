@extends('layouts.seeker')

@section('content')
<div class="container py-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Psikotes: MSDT</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info">
                <strong>Instruksi:</strong> Pilih salah satu pernyataan (A atau B) yang paling sesuai dengan diri Anda dalam situasi kerja.
            </div>

            <form action="{{ route('seeker.msdt.submit', $testResult->id) }}" method="POST">
                @csrf
                @foreach($questions as $question)
                <div class="mb-4 p-3 border rounded">
                    <div class="sticky-top bg-white py-2 shadow-sm mb-4">
                        <div class="container text-center">
                            <span class="badge bg-primary">Total Soal: 64</span>
                        </div>
                    </div>
                    <p class="fw-bold">{{ $question->question_number }}. Pilih salah satu:</p>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="answers[{{ $question->question_number }}]"
                            value="a" id="msdt_{{ $question->id }}_a" required>
                        <label class="form-check-label" for="msdt_{{ $question->id }}_a">
                            {{ $question->option_a }}
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="answers[{{ $question->question_number }}]"
                            value="b" id="msdt_{{ $question->id }}_b" required>
                        <label class="form-check-label" for="msdt_{{ $question->id }}_b">
                            {{ $question->option_b }}
                        </label>
                    </div>
                </div>
                @endforeach

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">Kirim Jawaban MSDT</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection