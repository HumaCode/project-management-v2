<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, HasUlids, InteractsWithMedia, Notifiable;

    /**
     * Konfigurasi Spatie Media Library
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile(); // 👈 SANGAT PENTING: Agar saat upload baru, foto lama otomatis terhapus
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(368)
            ->height(232)
            ->sharpen(10)
            ->nonQueued(); // Paksa potong sekarang juga tanpa antre!
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'gender',
        'city',
        'bio',
        'google_id',
        'avatar',
        'password',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'created_at_indo',
        'updated_at_indo',
        'role_name',
        'display_avatar',
        'initials',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'google_id',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        if (! $search) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('id', $search);
        });
    }

    public function scopeRoleType($query, ?string $type)
    {
        // Jika parameter kosong, kembalikan query apa adanya (tanpa filter)
        if (! $type) {
            return $query;
        }

        // Gunakan scope bawaan Spatie untuk memfilter berdasarkan nama role
        return $query->role($type);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', '1');
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', '0');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getCreatedAtIndoAttribute(): ?string
    {
        if (! $this->created_at) {
            return null;
        }

        // Lebih aman gunakan format langsung daripada helper global
        return tgl_indo($this->created_at);
    }

    public function getUpdatedAtIndoAttribute(): ?string
    {
        // 1. Pastikan updated_at memiliki nilai
        if (! $this->updated_at) {
            return null;
        }

        // 2. Bandingkan updated_at dengan created_at
        // Kita gunakan toDateTimeString() (format Y-m-d H:i:s) untuk menghindari
        // bug perbedaan microsecond (milidetik) yang kadang terjadi saat database menyimpan data
        if ($this->created_at && $this->updated_at->toDateTimeString() === $this->created_at->toDateTimeString()) {
            return '-';
        }

        // 3. Jika nilainya berbeda (sudah pernah diupdate), tampilkan format Indo
        return tgl_indo($this->updated_at);
    }

    /**
     * Mendapatkan nama role dari Spatie Permission
     */
    public function getRoleNameAttribute(): string
    {
        // getRoleNames() adalah fungsi bawaan murni dari Spatie
        // Hasilnya berupa Collection berisi kumpulan string nama role
        $roles = $this->getRoleNames();

        // Jika tidak punya role sama sekali
        if ($roles->isEmpty()) {
            return '-';
        }

        // Jika user punya lebih dari 1 role, gabungkan dengan koma dan spasi
        // Contoh output: "Dev, Super Admin"
        // Jika hanya 1 role, otomatis hanya menampilkan nama 1 role tersebut
        return $roles->join(', ');
    }

    /**
     * Accessor untuk mendapatkan URL Avatar atau Inisial Gambar
     */
    public function getDisplayAvatarAttribute(): ?string
    {
        // 1. Jika ini user Google, biasanya avatar berisi URL full
        if (! empty($this->avatar) && filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }

        // 2. Jika bukan Google, cek Spatie Media Library
        // Mengambil konversi 'thumb' jika ada, jika tidak ambil original
        $mediaUrl = $this->getFirstMediaUrl('avatar', 'thumb');

        if (! $mediaUrl) {
            $mediaUrl = $this->getFirstMediaUrl('avatar');
        }

        if ($mediaUrl) {
            return $mediaUrl;
        }

        // 3. Jika tidak ada gambar sama sekali, kembalikan null
        // Agar frontend bisa menggunakan accessor 'initials'
        return null;
    }

    /**
     * Accessor untuk mendapatkan Inisial Nama (Maksimal 2 huruf)
     */
    public function getInitialsAttribute(): string
    {
        // Pecah nama berdasarkan spasi
        $words = explode(' ', trim($this->name));
        $initials = '';

        // Ambil huruf pertama dari tiap kata
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        // Kembalikan maksimal 2 huruf saja (Misal: Amir Zakaria Subarjo -> AZ)
        return substr($initials, 0, 2);
    }

    /**
     * Relasi many-to-many ke tim di mana user menjadi anggota
     */
    public function teams(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'team_user')->withPivot('role');
    }
}
