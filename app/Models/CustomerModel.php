<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    use HasFactory;
    protected $table = 'tb_customer';
    protected $fillable = [
        'id' , 'nama_customer' , 'created_at' , 'updated_at'
    ];
}
