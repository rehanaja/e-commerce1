<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    public $timestamps = true;
    protected $table = "produk";
    protected $guarded = ['id'];

    protected $fillable = [
    'kategori_id',
    'nama_produk',
    'harga',
    'stok',
    'deskripsi',
    'foto',
    'user_id',
    'status',
    'detail',
    'berat'
];
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fotoProduk()
    {
    return $this->hasMany(FotoProduk::class);
    }

    public function gambar()
    {
        return $this->hasMany(FotoProduk::class, 'produk_id');
    }

}
