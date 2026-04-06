@props([
    'size' => 'lg',
    'title',
    'action' => null,
    'isEdit' => false,
    'type' => null,
])
<div class="modal fade m-dark m-p" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-{{ $size ?? 'md' }} modal-dialog-scrollable">
        <div class="modal-content">
            <div class="m-hd">
                <h5 class="m-hd-title"><i class="bi bi-shield-plus"></i>
                    @php
                        if (($type ?? null) === 'show') {
                            $finalTitle = "Detail $title";
                        } elseif (($type ?? null) === 'akses') {
                            // <-- PINDAHKAN PENGECEKAN AKSES KE SINI
                            $finalTitle = "Akses $title";
                        } elseif ($isEdit ?? false) {
                            // <-- TARUH isEdit DI BAWAHNYA
                            $finalTitle = "Edit $title";
                        } else {
                            $finalTitle = "Tambah Data $title";
                        }
                    @endphp
                    {{ $finalTitle }}
                </h5>
                <button type="button" class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
            </div>

            <form id="form_action" action="{{ $action }}" method="POST">
                @csrf
                @if ($isEdit ?? false)
                    @method('PUT')
                @endif

                <div class="m-bd" style="max-height:calc(80vh - 130px); overflow-y: auto;">
                    {{ $slot }}
                </div>



                @if ($action)
                    <div class="m-ft">
                        <button type="button" class="btn-mcancel" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i> Batal
                        </button>
                        <button type="submit" class="btn-msave">
                            <span><i class="bi bi-shield-plus"></i> Simpan</span>
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
