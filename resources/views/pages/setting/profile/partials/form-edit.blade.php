<form id="form-edit" data-url="{{ route('profil.update', $profile->id) }}">

    <div class="pane active" id="pane-edit">
        <div class="row g-4">


            <!-- Info Pribadi -->
            <div class="col-12 col-lg-8" data-aos="fade-up" data-aos-delay="60">
                <div class="pcard">
                    <div class="pc-hd">
                        <div class="pc-hd-left">
                            <div class="pc-hd-ico pci-c"><i class="bi bi-person-lines-fill"></i></div>
                            <div>
                                <div class="pc-hd-title">Informasi Pribadi</div>
                                <div class="pc-hd-sub">Data diri dan kontak pengguna</div>
                            </div>
                        </div>
                    </div>
                    <div class="pc-body">
                        <div class="fsec">Identitas</div>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">Nama <span class="req">*</span></label>
                                <input type="text" name="name" class="fmi" value="{{ $profile->name }}" />
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">Username <span class="req">*</span></label>
                                <div class="field-ico-wrap">
                                    <i class="bi bi-at fi"></i>
                                    <input type="text" name="username" class="fmi"
                                        value="{{ $profile->username }}" />
                                </div>
                                <div class="form-note">Huruf kecil, angka, dan underscore saja.</div>
                            </div>
                            <div class="col-12">
                                <label class="fm-lbl">Bio / Tentang Saya</label>
                                <textarea class="fmta" name="bio">{{ $profile->bio }}</textarea>
                            </div>
                        </div>
                        <div class="fsec" style="margin-top:20px">Kontak</div>
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">Email <span class="req">*</span></label>
                                <div class="field-ico-wrap">
                                    <i class="bi bi-envelope-fill fi"></i>
                                    <input type="email" name="email" class="fmi" value="{{ $profile->email }}" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">No. Telepon</label>
                                <div class="field-ico-wrap">
                                    <i class="bi bi-telephone-fill fi"></i>
                                    <input type="text" name="phone" class="fmi" value="{{ $profile->phone }}" />
                                </div>
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="fm-lbl">Kota / Lokasi</label>
                                <div class="field-ico-wrap">
                                    <i class="bi bi-geo-alt-fill fi"></i>
                                    <input type="text" name="city" class="fmi" value="{{ $profile->city }}" />
                                </div>
                            </div>
                        </div>
                        <div class="save-row">
                            <button type="submit" class="btn-save"><span><i class="bi bi-floppy-fill"></i>
                                    Simpan Perubahan</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Sidebar info -->
            <div class="col-12 col-lg-4">
                <!-- Foto profil -->
                <div class="pcard" data-aos="fade-up" data-aos-delay="80">
                    <div class="pc-hd">
                        <div class="pc-hd-left">
                            <div class="pc-hd-ico pci-c"><i class="bi bi-image-fill"></i></div>
                            <div>
                                <div class="pc-hd-title">Foto Profil</div>
                            </div>
                        </div>
                    </div>
                    <div class="pc-body" style="text-align:center">
                        <div style="display:flex;justify-content:center;margin-bottom:16px">
                            <div class="av-ring" style="width:90px;height:90px;animation:avring 8s linear infinite">

                                {{-- 1. UBAH PENGECEKANNYA MENJADI HASMEDIA --}}
                                @if ($profile->hasMedia('avatar'))
                                    <img src="{{ $profile->getFirstMediaUrl('avatar', 'thumb') }}"
                                        alt="{{ $profile->name }}" class="av-inner" id="avPreview"
                                        style="width:84px;height:84px;object-fit:cover;border-radius:50%;display:block;margin:0 auto;">
                                @else
                                    <div class="av-inner"
                                        style="width:84px;height:84px;font-size:28px;display:flex;align-items:center;justify-content:center;"
                                        id="avPreview">
                                        {{ $profile->initials }}
                                    </div>
                                @endif

                            </div>
                        </div>

                        <div
                            style="text-align:center;font-size:12px;color:var(--muted);font-family:var(--mono);margin-bottom:14px">
                            JPG, PNG atau GIF &middot; Maks. 2MB
                        </div>

                        <label for="avUpload" class="btn-p"
                            style="cursor:pointer;display:inline-flex;justify-content:center;width:100%">
                            <span><i class="bi bi-cloud-upload-fill"></i> Upload Foto</span>
                        </label>

                        <input type="file" id="avUpload" name="avatar" accept=".jpg,.jpeg,.png,.gif"
                            style="display: none;">

                    </div>
                </div>

            </div>


        </div>
    </div>
</form>
