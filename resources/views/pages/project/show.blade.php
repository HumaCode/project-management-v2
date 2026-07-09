<x-master-layout>

    @section('title', $title)

    @push('css')
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/project-detail.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/auth/backend/css/dokumen.css') }}">
    @endpush

    @push('js')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/index.min.js"></script>
        <script src="https://unpkg.com/picmo@latest/dist/umd/index.js"></script>
        <script src="https://unpkg.com/@picmo/popup-picker@latest/dist/umd/index.js"></script>
        <script src="{{ asset('assets/auth/backend/js/project-detail.js') }}"></script>
    @endpush

    <!-- Page Header -->
    <div class="ph-wrap" style="align-items: center;" data-aos="fade-down" id="projectContainer" data-id="{{ $projectId }}" data-url="{{ $detailDataUrl }}" data-current-user-id="{{ auth()->id() }}">
      <div class="ph-left">
        <div class="ph-icon"><i id="projectIcon" class="bi {{ $icon }}"></i></div>
        <div>
          <div class="ph-title" id="projectName">{{ $title }}</div>
          <div class="ph-meta" id="projectMeta">
            <span class="tag tag-prog" id="projectStatus"><span class="tdot"></span>Memuat...</span>
          </div>
        </div>
      </div>
      <div class="ph-actions-top">
        <div class="breadcrumb-bar d-none d-md-flex">
          <a href="{{ route('dashboard') }}"><i class="bi bi-house-fill"></i>&nbsp;Home</a>
          <span class="sep"><i class="bi bi-chevron-right"></i></span>
          <a href="{{ route('projects.index') }}">Project</a>
          <span class="sep"><i class="bi bi-chevron-right"></i></span>
          <span class="here">Detail</span>
        </div>
      </div>
    </div>

    <!-- Action Buttons Row -->
    <div class="row g-2 mb-4" data-aos="fade-up" data-aos-delay="20">
      <div class="row g-2 w-100 m-0">
        <div class="col-6 col-sm-auto flex-sm-grow-1">
          <a href="{{ route('projects.index') }}" class="btn-act btn-outline w-100 justify-content-center">
            <i class="bi bi-arrow-left"></i> <span class="ms-1">Kembali</span>
          </a>
        </div>
        @can('update', $project)
        <div class="col-6 col-sm-auto flex-sm-grow-1">
          <a href="#" id="editBtn" class="btn-act btn-outline w-100 justify-content-center">
            <i class="bi bi-pencil-fill"></i> <span class="ms-1">Edit</span>
          </a>
        </div>
        @endcan
        @can('delete', $project)
        <div class="col-12 col-sm-auto">
          <button class="btn-act btn-danger-outline w-100 justify-content-center" data-bs-toggle="modal" data-bs-target="#deleteModal">
            <i class="bi bi-trash3-fill"></i> <span class="ms-1 d-sm-none">Hapus Project</span>
          </button>
        </div>
        @endcan
      </div>
    </div>

    <!-- Mini Stats -->
    @php
      $isUser = auth()->user()->hasRole('user');
      $colClass = $isUser ? 'col-4 col-md-4' : 'col-6 col-md-3';
    @endphp
    <div class="row g-2 mb-4" data-aos="fade-up" data-aos-delay="40">
      <div class="{{ $colClass }}">
        <div class="msc h-100">
          <div class="msc-ico c"><i class="bi bi-folder2-open"></i></div>
          <div>
            <div class="msc-val" id="statDocs" data-count="0">0</div>
            <div class="msc-lbl">Dokumen</div>
          </div>
        </div>
      </div>
      @if(!$isUser)
      <div class="col-6 col-md-3">
        <div class="msc h-100">
          <div class="msc-ico g"><i class="bi bi-journal-check"></i></div>
          <div>
            <div class="msc-val" id="statNotes" data-count="0">0</div>
            <div class="msc-lbl">Diskusi</div>
          </div>
        </div>
      </div>
      @endif
      <div class="{{ $colClass }}">
        <div class="msc h-100">
          <div class="msc-ico w"><i class="bi bi-people-fill"></i></div>
          <div>
            <div class="msc-val" id="statMembers" data-count="0">0</div>
            <div class="msc-lbl">Anggota</div>
          </div>
        </div>
      </div>
      <div class="{{ $colClass }}">
        <div class="msc h-100">
          <div class="msc-ico r" id="statDaysIcon"><i class="bi bi-clock-history"></i></div>
          <div>
            <div class="msc-val" id="statDays" data-count="0">0</div>
            <div class="msc-lbl">Hari Tersisa</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Grid: Info + Tabs -->
    <div class="row g-3 mb-22">

      <!-- Left: Info Card -->
      <div class="col-12 col-lg-4" data-aos="fade-up" data-aos-delay="60">
        <div class="crd h-100" style="display:flex;flex-direction:column">

          <!-- Progress -->
          <div class="prog-wrap">
            <div class="prog-meta">
              <span class="prog-label">Progress</span>
              <span class="prog-pct" id="progPct">72%</span>
            </div>
            <div class="prog-track">
              <div class="prog-fill" id="progFill" style="width:0%"></div>
            </div>
          </div>

          <!-- Info grid -->
          <div class="info-grid" style="flex:1">
            <div class="info-item">
              <div class="info-label">Status</div>
              <div class="info-val"><span class="tag tag-prog" id="infoStatus"><span class="tdot"></span>-</span></div>
            </div>
            <div class="info-item">
              <div class="info-label">Dibuat Oleh</div>
              <div class="info-val" id="infoCreator">-</div>
            </div>
            <div class="info-item">
              <div class="info-label">Tgl Mulai</div>
              <div class="info-val mono" id="infoStartDate">-</div>
            </div>
            <div class="info-item">
              <div class="info-label">Deadline</div>
              <div class="info-val">
                <div class="mono" id="infoDeadline" style="margin-bottom:3px">-</div>
                <span class="dl-badge" id="infoDaysBadge"></span>
              </div>
            </div>
            <div class="info-item" style="grid-column:1/-1;border-bottom:1px solid var(--bd)">
              <div class="info-label">Deskripsi</div>
              <div class="info-val" id="infoDesc" style="font-weight:400;font-size:13px;color:var(--dim);line-height:1.65; max-height:160px; overflow-y:auto; padding-right:5px;">
                Memuat deskripsi...
              </div>
            </div>
            <div class="info-item" style="grid-column:1/-1">
              <div class="info-label" style="margin-bottom:10px">Tim Pelaksana</div>
              <div style="display:flex;flex-direction:column;gap:8px" id="picListWrap">
                <div class="team-badge-wrap" style="margin-bottom:4px">
                  <span class="tag tag-prog" id="infoTeamName" style="font-size:12px; font-weight:700; background:rgba(0,200,255,0.15)">-</span>
                </div>
                <div style="display:flex;align-items:center;gap:8px">
                  <div class="av-stack" id="picStack">
                    <!-- Loaded via JS -->
                  </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:5px" id="picList">
                  <!-- Loaded via JS -->
                </div>
              </div>
            </div>
          </div><!-- /info-grid -->

        </div>
      </div><!-- /col left -->

      <!-- Right: Tabs -->
      <div class="col-12 col-lg-8" data-aos="fade-up" data-aos-delay="80">
        <div class="crd" style="display:flex;flex-direction:column;height:100%">

          <!-- Tab nav -->
          <div class="tab-nav" id="tabNav">
            <button class="tab-btn active" data-tab="dokumen">
              <i class="bi bi-folder2-open"></i>
              <span>Dokumen</span>
              <span class="tb-cnt" id="tabDocCount">0</span>
            </button>
            @if(!$isUser)
            <button class="tab-btn" data-tab="catatan">
              <i class="bi bi-chat-left-text"></i>
              <span>Diskusi</span>
              <span class="tb-cnt" id="tabNoteCount">0</span>
            </button>
            <button class="tab-btn" data-tab="aktivitas">
              <i class="bi bi-activity"></i>
              <span>Aktivitas</span>
            </button>
            @endif
          </div>

          <!-- Tab: Dokumen -->
          <div class="tab-pane active" id="tab-dokumen">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:8px">
              <div style="position:relative;flex:1;max-width:280px">
                <i class="bi bi-search" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px"></i>
                <input type="text" placeholder="Cari dokumen..." style="width:100%;height:34px;border-radius:var(--rs);background:rgba(0,200,255,.05);border:1px solid var(--bd);padding:0 10px 0 32px;color:var(--txt);font-family:var(--font);font-size:12.5px;outline:none" id="docSearch"/>
              </div>
            </div>
            <div class="tbl-wrap">
              <table class="doc-tbl" id="docTbl">
                <thead>
                  <tr>
                    <th style="width:36px">#</th>
                    <th>Nama Dokumen</th>
                    <th class="col-hide-xs">Versi</th>
                    <th class="d-none d-sm-table-cell">Ukuran</th>
                    <th class="d-none d-md-table-cell">Diunggah</th>
                    <th class="d-none d-md-table-cell">Oleh</th>
                    <th style="width:80px;text-align:center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Loaded via AJAX -->
                </tbody>
              </table>
            </div>
            <div style="padding:10px 0 4px;text-align:center;font-family:var(--mono);font-size:11px;color:var(--muted)" id="docFooter">
              <!-- Loaded via AJAX -->
            </div>
            <div id="docPaginationWrap" class="pg-wrap" style="margin-top: 5px; border-top: 1px dashed var(--bd); padding-top: 12px; display:flex; justify-content:center; gap:5px;">
              <!-- Pagination buttons -->
            </div>
          </div>

          <!-- Tab: Catatan -->
          @if(!$isUser)
          <div class="tab-pane" id="tab-catatan">
            <div id="noteWrap">
              <!-- Loaded via AJAX -->
            </div>
            <div class="add-note-wrap">
              <div class="add-note-row">
                <div class="add-note-av">
                    @php
                        $user = auth()->user();
                        $avatar = $user->display_avatar;
                        $initials = $user->initials;
                    @endphp
                    @if($avatar)
                        <img src="{{ $avatar }}" alt="{{ $user->name }}" style="width:100%;height:100%;object-fit:cover;border-radius:inherit">
                    @else
                        {{ $initials }}
                    @endif
                </div>
                <div class="chat-input-group" style="flex:1; display:flex; flex-direction:column; gap:0;">
                  <!-- Reply Preview -->
                  <div id="chatReplyWrap" class="chat-reply-wrap" style="display:none">
                    <div class="reply-content">
                        <div class="reply-author" id="replyAuthor">Nama User</div>
                        <div class="reply-text" id="replyText">Isi pesan yang dibalas...</div>
                    </div>
                    <button type="button" class="btn-remove-reply" id="btnCancelReply">&times;</button>
                  </div>

                  <!-- Edit Preview -->
                  <div id="chatEditWrap" class="chat-reply-wrap" style="display:none; border-left-color: var(--warn);">
                    <div class="reply-content">
                        <div class="reply-author" style="color: var(--warn);">Sedang mengedit pesan...</div>
                        <div class="reply-text" id="editText">Isi pesan yang diedit...</div>
                    </div>
                    <button type="button" class="btn-remove-reply" id="btnCancelEdit">&times;</button>
                  </div>

                  <!-- Preview above the input row -->
                  <div id="chatPreviewWrap" class="chat-preview-wrap" style="display:none">
                    <!-- Preview will be injected here -->
                  </div>

                  <div class="add-note-container">
                    <div class="chat-box-wrap">
                      <textarea class="add-note-box" placeholder="Tulis komentar atau diskusi untuk project ini..." rows="1" id="noteInput"></textarea>
                      <div class="chat-actions">
                          <button type="button" class="chat-btn-act" title="Emoji" id="btnEmoji"><i class="bi bi-emoji-smile"></i></button>
                          <button type="button" class="chat-btn-act" title="Lampirkan File" id="btnAttach"><i class="bi bi-paperclip"></i></button>
                          <input type="file" id="chatFileInput" style="display:none" multiple>
                      </div>
                    </div>
                    <div class="add-note-footer">
                      <button class="btn-note-submit" id="btnAddNote"><span><i class="bi bi-send-fill"></i></span></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif

          <!-- Tab: Aktivitas -->
          @if(!$isUser)
          <div class="tab-pane" id="tab-aktivitas">
            <div class="timeline" id="activityTimeline">
              <!-- Loaded via AJAX -->
            </div>
            <div id="activityPaginationWrap" style="margin-top:20px; display:flex; justify-content:center; gap:5px; flex-wrap:wrap">
                <!-- Pagination numbers injected via JS -->
            </div>
          </div>
          @endif

        </div><!-- /crd tabs -->
      </div><!-- /col right -->
    </div><!-- /row -->


    <!-- Delete Project Confirm Modal (Existing) -->
    <div class="modal fade modal-dark" id="deleteModal" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="bi bi-exclamation-triangle-fill" style="color:var(--err)"></i> Hapus Project</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1) brightness(.6)"></button>
          </div>
          <div class="modal-body">
            <div class="logout-warn">
              <i class="bi bi-exclamation-triangle-fill"></i>
              <p>Apakah Anda yakin ingin <strong>menghapus project</strong> ini? Semua dokumen dan catatan terkait akan ikut terhapus secara permanen.</p>
            </div>
            <p style="font-size:13px;color:var(--muted);font-family:var(--mono)"><i class="bi bi-kanban"></i> Project: <span style="color:var(--cyan)">{{ $project->name }}</span></p>
          </div>
          <div class="modal-footer">
            <button class="btn-cancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batalkan</button>
            <button class="btn-logout"><span><i class="bi bi-trash3-fill"></i> Ya, Hapus</span></button>
          </div>
          <div class="modal-drain"><div class="modal-drain-fill" id="drainFill2"></div></div>
        </div>
      </div>
    </div>

    <!-- Delete Document Confirm Modal -->
    <div class="modal fade m-dark m-red" id="delDocModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="m-hd">
                    <h5 class="m-hd-title"><i class="bi bi-trash3-fill"></i> Hapus Dokumen</h5>
                    <button type="button" class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="m-bd">
                    <div class="warn-box">
                        <i class="bi bi-exclamation-triangle-fill"></i>
                        <p>Anda akan menghapus dokumen <strong id="delDocTitle">ini</strong> secara permanen dari project ini.</p>
                    </div>
                    <p style="font-size:12px;color:var(--muted);font-family:var(--mono)"><i class="bi bi-info-circle"></i>&nbsp;Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <div class="m-ft">
                    <button type="button" class="btn-mcancel" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i> Batalkan</button>
                    <button type="button" class="btn-mdel-doc btn-mdel"><span><i class="bi bi-trash3-fill"></i> Ya, Hapus</span></button>
                </div>
                <div class="modal-drain"><div class="drain-fill" id="drainDelDoc"></div></div>
            </div>
        </div>
    </div>

    <!-- Document Detail Modal -->
    <div class="modal m-dark m-cyan" id="modalDocDetail" tabindex="-1" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="m-hd">
                    <h5 class="m-hd-title"><i class="bi bi-info-circle-fill"></i> Detail Dokumen</h5>
                    <button type="button" class="m-close" data-bs-dismiss="modal"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="m-bd">
                    <div class="row g-4">
                        <!-- File Preview & Info -->
                        <div class="col-12 col-md-5">
                            <div class="detail-preview-card">
                                <div id="detailFileIcon" class="detail-icon-large pdf">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </div>
                                <div id="detailImagePreview" class="detail-img-box" style="display:none">
                                    <img src="" alt="Preview" style="width:100%; height:auto;">
                                </div>
                                <div class="mt-3 text-center">
                                    <h6 class="mb-1 text-white" id="detailNama">Nama Dokumen</h6>
                                    <p class="text-muted small" id="detailMeta">PDF &bull; 2.5 MB</p>
                                    <a href="" id="btnDownloadDetail" class="btn btn-sm btn-outline-cyan w-100 mt-2" download>
                                        <i class="bi bi-download"></i> Unduh Dokumen
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Metadata -->
                        <div class="col-12 col-md-7">
                            <div class="detail-info-list">
                                <div class="info-item">
                                    <span class="label">KATEGORI</span>
                                    <span class="value"><span class="cat cat-s" id="detailKategori">Spesifikasi</span></span>
                                </div>
                                <div class="info-item">
                                    <span class="label">PROJECT</span>
                                    <span class="value text-cyan" id="detailProject">Project Name</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">VERSI</span>
                                    <span class="value"><span class="vbadge" id="detailVersi">v1.0</span></span>
                                </div>
                                <div class="info-item">
                                    <span class="label">TANGGAL UPLOAD</span>
                                    <span class="value" id="detailTanggal">12 Mei 2026</span>
                                </div>
                                <div class="info-item">
                                    <span class="label">DIUNGGAH OLEH</span>
                                    <span class="value">
                                        <div class="td-usr">
                                            <div class="uav" id="detailUploaderAvatar" style="background:linear-gradient(135deg,#1e3a5f,#3d6080)">JD</div>
                                            <span id="detailUploaderName">John Doe</span>
                                        </div>
                                    </span>
                                </div>
                                <div class="info-item border-0 mt-2 vertical">
                                    <span class="label">KETERANGAN</span>
                                    <p class="value-desc" id="detailKeterangan">Tidak ada keterangan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-ft">
                    <button type="button" class="btn-mcancel w-100" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .detail-preview-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--bd);
            border-radius: 15px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
        .detail-icon-large {
            font-size: 80px;
            line-height: 1;
            margin-bottom: 15px;
            filter: drop-shadow(0 5px 15px rgba(0,0,0,0.3));
        }
        .detail-img-box {
            width: 100%;
            border-radius: 10px;
            overflow: hidden;
            border: 2px solid var(--bd);
        }
        .detail-info-list .info-item {
            padding: 12px 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .detail-info-list .info-item.vertical {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        .detail-info-list .label {
            font-family: var(--mono);
            font-size: 11px;
            color: var(--cyan);
            opacity: 0.8;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .detail-info-list .value {
            font-weight: 600;
            color: #fff;
            font-size: 14px;
        }
        .value-desc {
            color: #ccc;
            font-size: 13px;
            line-height: 1.6;
            background: rgba(255,255,255,0.03);
            padding: 12px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,0.05);
            margin: 0;
            width: 100%;
        }
        .btn-outline-cyan {
            border-color: var(--cyan);
            color: var(--cyan);
            transition: 0.3s;
        }
        .btn-outline-cyan:hover {
            background: var(--cyan);
            color: #000;
        }

        /* Premium Emoji Picker Styling */
        .premium-emoji-picker {
            --picker-background: rgba(15, 23, 42, 0.95);
            --picker-border-color: rgba(0, 200, 255, 0.2);
            --picker-header-background: rgba(0, 200, 255, 0.05);
            --picker-category-button-color-active: var(--cyan);
            border-radius: 16px !important;
            backdrop-filter: blur(12px);
            border: 1px solid var(--picker-border-color) !important;
            box-shadow: 0 15px 35px rgba(0,0,0,0.5) !important;
            overflow: hidden;
        }

        /* Description Scrollbar Styling */
        #infoDesc::-webkit-scrollbar {
            width: 4px;
        }
        #infoDesc::-webkit-scrollbar-thumb {
            background: var(--bd);
            border-radius: 10px;
        }
    </style>

</x-master-layout>
