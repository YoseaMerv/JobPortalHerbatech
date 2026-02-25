<div class="modal fade" id="modalEducation" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('seeker.profile.education.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Tambah Riwayat Pendidikan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Institusi / Nama Sekolah</label>
                    <input type="text" name="institution" class="form-control" placeholder="Contoh: Universitas Indonesia" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gelar / Jenjang</label>
                    <input type="text" name="degree" class="form-control" placeholder="Contoh: Sarjana (S1)" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Bidang Studi / Jurusan</label>
                    <input type="text" name="field_of_study" class="form-control" placeholder="Contoh: Teknik Informatika">
                </div>
                <div class="row">
                    <div class="col-6 mb-3">
                        <label class="form-label">Tahun Mulai</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-6 mb-3">
                        <label class="form-label">Tahun Lulus</label>
                        <input type="date" name="end_date" class="form-control">
                        <small class="text-muted">Kosongkan jika masih menempuh studi.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Pendidikan</button>
            </div>
        </form>
    </div>
</div>