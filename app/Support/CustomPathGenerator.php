<?php

namespace App\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    /*
     * Mengatur path untuk file asli
     */
    public function getPath(Media $media): string
    {
        // class_basename akan mengambil nama model (misal: 'User')
        // strtolower akan mengubahnya jadi huruf kecil (misal: 'user')
        $prefix = strtolower(class_basename($media->model_type));

        // Hasilnya: user/2/
        return $prefix.'/'.$media->getKey().'/';
    }

    /*
     * Mengatur path untuk file konversi (seperti thumbnail)
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media).'conversions/';
    }

    /*
     * Mengatur path untuk gambar responsif (jika kamu memakainya)
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media).'responsive-images/';
    }
}
