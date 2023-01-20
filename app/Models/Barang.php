<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;
    protected $table = "barang";
    protected $fillable = [
        'qr_code','nama','lokasi','kategori','stok','keterangan',
    ];

    public function barang_keluar(){
        return $this->hasMany(BarangKeluar::class);
    }

    public function barang_masuk(){
        return $this->hasMany(BarangMasuk::class);
    }
}