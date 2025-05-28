<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';
    protected   $fillable = ['nama','foto','jabatan_id', 'alamat', 'tgl_lahir', 'jenis_kelamin','status'];

   public static $rules = [
        'nama' => 'required | max:50',
        'jabatan_id' => 'required',
        'alamat' => 'required ',
        'tgl_lahir' => 'required ',
        'jenis_kelamin' => 'required',
        'foto' => 'required',
    ];

    public static $rulesUpdate = [
        'nama' => 'required | max:50',
        'jabatan_id' => 'required',
        'alamat' => 'required ',
        'tgl_lahir' => 'required ',
        'jenis_kelamin' => 'required',
        'foto' => 'nullable',
    ];

    public static $messages = [

            'nama.required' => 'nama wajib di isi ',
            'nama.max' => 'nama tidak boleh lebih dari 50 karakter',
            'jabatan_id.required' => 'jabatan wajib di isi',
            'alamat.required' => 'alamat wajib di isi',
            'tgl_lahir.required' => 'tanggal lahir wajib di isi',
            'jenis_kelamin.required' => 'jenis kelamin wajib di isi',
            'foto.required' => 'foto wajib di isi',

    ];

    public function jabatan(){
        return $this->belongsTo(Jabatan::class);
    }
}
