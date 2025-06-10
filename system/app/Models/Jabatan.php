<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;


class Jabatan extends Model
{
    protected $table = "jabatan";

    protected $fillable = [
       'nama_jabatan'
    ];

    public static $rules = [
        'nama_jabatan' => 'required|string|max:255',
    ];
    public static $messages = [
        'nama_jabatan.required' => 'Nama jabatan tidak boleh kosong',
        'nama_jabatan.string' => 'Nama jabatan harus berupa huruf',
        'nama_jabatan.max' => 'Nama jabatan maksimal 255 karakter',
    ];

    
}
