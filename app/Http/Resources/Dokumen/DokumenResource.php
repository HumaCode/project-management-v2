<?php

namespace App\Http\Resources\Dokumen;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DokumenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'versi' => $this->versi,
            'kategori' => $this->kategori,
            'kategori_label' => $this->kategori_label,
            'tanggal_upload' => $this->tanggal_upload->format('d M Y'),
            'keterangan' => $this->keterangan,
            'type' => $this->type,
            'status' => $this->status,
            'project' => [
                'id' => $this->project->id ?? null,
                'name' => $this->project->name ?? 'N/A',
            ],
            'uploader' => [
                'id' => $this->uploader->id ?? null,
                'name' => $this->uploader->name ?? 'System',
                'avatar_url' => $this->uploader->avatar_url ?? null,
            ],
            'file_info' => [
                'extension' => 'pdf', // Placeholder
                'size' => '4.2 MB', // Placeholder
            ],
            'created_at' => $this->created_at->format('d M Y H:i'),
        ];
    }
}
