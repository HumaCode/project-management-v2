<x-master-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/project-create.css') }}">
    @endpush

    <div class="page-header" data-aos="fade-down">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="ph-left">
                    <div class="ph-icon"><i class="{{ $icon }}"></i></div>
                    <div>
                        <div class="ph-title">{{ $title }}</div>
                        <div class="ph-sub">{{ $subtitle }}</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0 text-start text-lg-end">
                <div class="breadcrumb-bar d-inline-flex">
                    <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
                    <span class="sep"><i class="bi bi-chevron-right"></i></span>
                    <a href="{{ route('projects.index') }}">Project</a>
                    <span class="sep"><i class="bi bi-chevron-right"></i></span>
                    <span class="here">Ubah</span>
                </div>
            </div>
        </div>
    </div>

    <div class="crd mb-24" data-aos="fade-up">
        <div class="crd-head">
            <div class="crd-title"><i class="bi bi-pencil-square"></i> Form Ubah Project</div>
            <span class="crd-badge"><i class="bi bi-asterisk" style="font-size:8px;margin-right:2px"></i>Wajib diisi</span>
        </div>

        <div class="form-body">
            <form id="formEditProject" action="{{ route('projects.update', $project->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="fsec-title"><i class="bi bi-info-circle-fill"></i> Informasi Dasar</div>

                <div class="row">
                    <div class="col-12">
                        <div class="fg">
                            <label>Nama Project <span class="req">*</span></label>
                            <div class="fiw">
                                <i class="bi bi-kanban-fill fi-ic"></i>
                                <input type="text" name="name" class="fi" id="fNama"
                                    placeholder="Contoh: Sistem Informasi PPID Kota Pekalongan" maxlength="120"
                                    autocomplete="off" value="{{ $project->name }}" />
                            </div>
                            <div class="ccnt" id="cNama">0 / 120</div>
                            <div class="emsg">Nama project wajib diisi.</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="fg">
                            <label>Deskripsi Project</label>
                            <textarea name="description" class="fa" id="fDesc"
                                placeholder="Jelaskan tujuan, ruang lingkup, dan target project ini..." maxlength="500" rows="3">{!! $project->description !!}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg">
                            <label>Status <span class="req">*</span></label>
                            <select name="status" class="fsl" id="fStatus">
                                <option value="">-- Pilih Status --</option>
                                <option value="to_do" {{ $project->status == 'to_do' ? 'selected' : '' }}>To Do</option>
                                <option value="in_progress" {{ $project->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="done" {{ $project->status == 'done' ? 'selected' : '' }}>Done</option>
                            </select>
                            <div class="emsg">Status wajib dipilih.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg">
                            <label>Prioritas Project <span class="req">*</span></label>
                            <select name="priority" class="fsl" id="fPriority">
                                <option value="low" {{ $project->priority == 'low' ? 'selected' : '' }}>Low (Biasa)</option>
                                <option value="medium" {{ $project->priority == 'medium' ? 'selected' : '' }}>Medium (Menengah)</option>
                                <option value="high" {{ $project->priority == 'high' ? 'selected' : '' }}>High (Tinggi)</option>
                                <option value="urgent" {{ $project->priority == 'urgent' ? 'selected' : '' }}>Urgent (Mendesak)</option>
                            </select>
                            <div class="emsg">Prioritas wajib dipilih.</div>
                        </div>
                    </div>
                </div>

                <div class="fsec-title" style="margin-top:28px"><i class="bi bi-calendar3-fill"></i> Jadwal Project</div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="fg">
                            <label>Tanggal Mulai <span class="req">*</span></label>
                            <div class="fiw">
                                <i class="bi bi-calendar-event fi-ic"></i>
                                <input type="text" name="start_date" class="fi" id="fStart"
                                    style="padding-left:40px" placeholder="Pilih tanggal..." readonly value="{{ $project->start_date?->format('d-m-Y') }}" />
                            </div>
                            <div class="emsg">Tanggal mulai wajib diisi.</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="fg">
                            <label>Deadline <span class="req">*</span></label>
                            <div class="fiw">
                                <i class="bi bi-calendar-x fi-ic"></i>
                                <input type="text" name="deadline" class="fi" id="fDeadline"
                                    style="padding-left:40px" placeholder="Pilih tanggal..." readonly value="{{ $project->deadline?->format('d-m-Y') }}" />
                            </div>
                            <div class="emsg">Deadline wajib diisi &amp; harus setelah tanggal mulai.</div>
                        </div>
                    </div>
                </div>

                <div class="fsec-title" style="margin-top:28px"><i class="bi bi-people-fill"></i> Tim Pelaksana</div>

                <div class="row">
                    <div class="col-12">
                        <div class="fg">
                            <label>Pilih Tim <span class="req">*</span></label>
                            <select name="team_id" class="fsl" id="fTeam">
                                <option value="">-- Pilih Tim --</option>
                                @foreach ($teams as $team)
                                    <option value="{{ $team->id }}" {{ $project->team_id == $team->id ? 'selected' : '' }}>{{ $team->name }}</option>
                                @endforeach
                            </select>
                            <div class="emsg">Tim pelaksana wajib dipilih.</div>
                            <div style="font-size:11.5px;color:var(--muted);font-family:var(--mono);margin-top:6px">
                                <i class="bi bi-info-circle"></i>&nbsp;Anggota dari tim yang dipilih akan otomatis menjadi pelaksana di project ini.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fsec-title" style="margin-top:28px"><i class="bi bi-palette-fill"></i> Media & Estetika</div>

                <div class="row">
                    <div class="col-md-8">
                        <div class="fg">
                            <label>Thumbnail Project</label>
                            <div class="thumb-upload {{ $project->hasMedia('thumbnail') ? 'has-file' : '' }}" id="thumbUpload">
                                <button type="button" class="tu-remove" id="btnRemoveThumb" title="Hapus Gambar">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                                <div class="tu-preview" id="tuPreview">
                                    @if($project->hasMedia('thumbnail'))
                                        <img src="{{ $project->getFirstMediaUrl('thumbnail') }}" alt="Thumbnail">
                                    @else
                                        <i class="bi bi-image"></i>
                                    @endif
                                </div>
                                <div class="tu-info">
                                    <div class="tu-title">Klik atau seret gambar ke sini</div>
                                    <div class="tu-sub">Format: JPG, PNG, WEBP (Maks. 2MB)</div>
                                    <input type="file" name="thumbnail" id="fThumb" accept="image/*" style="display:none" />
                                    <button type="button" class="btn-tu" onclick="document.getElementById('fThumb').click()">
                                        <i class="bi bi-cloud-upload"></i> Ganti Gambar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="fg">
                            <label>Warna Tema Project</label>
                            <div class="color-picker-wrapper">
                                <input type="color" name="color" class="fcolor" id="fColor" value="{{ $project->color ?? '#4f46e5' }}" />
                                <div class="color-info">
                                    <span id="colorHex">{{ strtoupper($project->color ?? '#4f46e5') }}</span>
                                    <small>Warna ini akan digunakan sebagai aksen di dashboard.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="fsec-title" style="margin-top:28px"><i class="bi bi-bar-chart-fill"></i> Progress Project</div>

                <div class="row">
                    <div class="col-12">
                        <div class="fg">
                            <label>Persentase Progress</label>
                            <div class="sl-wrap">
                                <div class="sl-val" id="slVal" style="left:{{ $project->progress }}%">{{ $project->progress }}%</div>
                                <input type="range" name="progress" class="sl" id="slRange" min="0" max="100"
                                    value="{{ $project->progress }}" step="5" />
                            </div>
                            <div class="sl-track">
                                <div class="sl-fill" id="slFill" style="width:{{ $project->progress }}%"></div>
                            </div>
                            <div class="sl-labels">
                                <span>0%</span><span>25%</span><span>50%</span><span>75%</span><span>100%</span></div>
                        </div>
                    </div>
                </div>

                <div class="fsec-title" style="margin-top:28px"><i class="bi bi-journal-text"></i> Catatan Tambahan</div>

                <div class="row">
                    <div class="col-12">
                        <div class="fg">
                            <label>Catatan Internal</label>
                            <textarea name="notes" class="fa" id="fNotes"
                                placeholder="Catatan khusus atau info tambahan untuk tim..." maxlength="300" rows="3">{{ $project->notes }}</textarea>
                            <div class="ccnt" id="cNotes">0 / 300</div>
                        </div>
                    </div>
                </div>

                <div class="factions mt-4">
                    <a href="{{ route('projects.index') }}" class="btn-batal"><i class="bi bi-arrow-left"></i>
                        Kembali</a>
                    <button type="button" class="btn-save" id="btnSave">
                        <span><i class="bi bi-check2-circle"></i> Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        @php
            $existingPics = $project->pics->map(function ($u) {
                return [
                    'id' => $u->id,
                    'n' => $u->name,
                    'i' => $u->initials,
                    'r' => $u->roles->first()?->name ?? 'Anggota',
                ];
            });
        @endphp
        <script>
            window.projectIndexUrl = "{{ route('projects.index') }}";
            window.existingPics = @json($existingPics);
        </script>
        <script src="{{ asset('assets/auth/backend/js/project-edit.js') }}"></script>
    @endpush
</x-master-layout>
