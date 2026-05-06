<x-form.modal title="User" :type="'show'">
    <div class="user-detail-content" style="color: var(--txt);">
        <!-- Header Profile -->
        <div class="d-flex align-items-center gap-4 mb-4 p-4"
            style="background: rgba(0, 200, 255, 0.05); border-radius: 20px; border: 1px solid var(--bd);">
            <div class="detail-avatar"
                style="width: 80px; height: 80px; border-radius: 22px; background: linear-gradient(135deg, var(--cyan), var(--blue)); display: flex; align-items: center; justify-content: center; font-size: 32px; color: white; font-weight: 700; box-shadow: 0 10px 25px -5px rgba(0, 114, 198, 0.5); position: relative; overflow: hidden;">
                @if ($data->hasMedia('avatar'))
                    <img src="{{ $data->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                @elseif($data->avatar)
                     <img src="{{ asset('storage/avatar/' . $data->avatar) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    {{ $data->initials }}
                @endif
                <div
                    style="position: absolute; inset: 0; background: linear-gradient(135deg, rgba(255,255,255,0.2), transparent);">
                </div>
            </div>
            <div>
                <h4 class="mb-1" style="font-weight: 800; letter-spacing: -0.5px;">{{ $data->name }}</h4>
                <div class="d-flex align-items-center gap-2">
                    <span class="role-badge ru-{{ $data->role_name }}"
                        style="font-size: 11px; padding: 3px 10px; border-radius: 8px;">{{ $data->role_name }}</span>
                    @if ($data->is_active)
                        <span class="badge"
                            style="background: rgba(0, 229, 160, 0.1); color: var(--ok); border: 1px solid rgba(0, 229, 160, 0.2); font-size: 10px; padding: 4px 8px; border-radius: 8px;">
                            <i class="bi bi-patch-check-fill me-1"></i> Aktif
                        </span>
                    @else
                        <span class="badge"
                            style="background: rgba(255, 77, 109, 0.1); color: var(--err); border: 1px solid rgba(255, 77, 109, 0.2); font-size: 10px; padding: 4px 8px; border-radius: 8px;">
                            <i class="bi bi-patch-exclamation-fill me-1"></i> Nonaktif
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Information Grid -->
        <div class="row g-3">
            <div class="col-md-6">
                <div class="p-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--bd); border-radius: 12px;">
                    <label style="font-family: var(--mono); font-size: 10px; color: var(--dim); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px;">Username</label>
                    <div style="font-weight: 500; font-size: 14px;"><i class="bi bi-person-circle me-2" style="color: var(--cyan);"></i>{{ $data->username }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--bd); border-radius: 12px;">
                    <label style="font-family: var(--mono); font-size: 10px; color: var(--dim); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px;">Email</label>
                    <div style="font-weight: 500; font-size: 14px;"><i class="bi bi-envelope-at me-2" style="color: var(--cyan);"></i>{{ $data->email }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--bd); border-radius: 12px;">
                    <label style="font-family: var(--mono); font-size: 10px; color: var(--dim); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px;">Nomor Telepon</label>
                    <div style="font-weight: 500; font-size: 14px;"><i class="bi bi-telephone me-2" style="color: var(--cyan);"></i>{{ $data->phone ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3" style="background: rgba(255,255,255,0.02); border: 1px solid var(--bd); border-radius: 12px;">
                    <label style="font-family: var(--mono); font-size: 10px; color: var(--dim); text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 5px;">Terakhir Login</label>
                    <div style="font-weight: 500; font-size: 14px;"><i class="bi bi-box-arrow-in-right me-2" style="color: var(--cyan);"></i>{{ $data->last_login_at ?? 'Belum pernah' }}</div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="p-3" style="background: rgba(0, 200, 255, 0.03); border: 1px dashed var(--bdh); border-radius: 12px;">
                    <div class="row g-3 text-center">
                        <div class="col-6" style="border-right: 1px solid var(--bd);">
                            <label style="font-family: var(--mono); font-size: 9px; color: var(--dim); text-transform: uppercase; display: block;">Tgl. Daftar</label>
                            <span style="font-size: 13px; font-weight: 600;">{{ $data->created_at_indo }}</span>
                        </div>
                        <div class="col-6">
                            <label style="font-family: var(--mono); font-size: 9px; color: var(--dim); text-transform: uppercase; display: block;">Tgl. Update</label>
                            <span style="font-size: 13px; font-weight: 600;">{{ $data->updated_at_indo }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Close Button Only Footer -->
    <div class="m-ft mt-4" style="border-top: 1px solid var(--bd); padding-top: 15px;">
        <button type="button" class="btn-mcancel w-100" data-bs-dismiss="modal" style="justify-content: center;">
            <i class="bi bi-x-lg"></i> Tutup Detail
        </button>
    </div>
</x-form.modal>
