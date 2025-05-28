<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * 
 *
 * @property int $id
 * @property string $nama_jabatan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereNamaJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
