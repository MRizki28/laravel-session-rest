<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangModel extends Model
{
    use HasFactory;
    protected $table = 'tb_barang';
    protected $fillable = [
        'id' ,'id_customer' , 'nama_barang' , 'qty' , 'harga_satuan' , 'total_harga' , 'created_at', 'updated_at'    
    ];
}
