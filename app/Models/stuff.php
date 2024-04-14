<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class stuff extends Model
{
    //jika di migrationnya  menggunakan $table->softdeletes(opsional)
    use SoftDeletes;

    //fillable / guard
    //menentukan column wajib diisi (column yang wajib diisi dari luar)
    protected $fillable = ["name", "category"];
    // protected $guarded = ['id']

    //property opsional :
    //kalau primary key bukan id : public $primaryKey = 'no'
    //kalau misal gapake timestamps di migration : public $timestamps = FALSE

    //relasi
    //nama function : samain kaya model, kata pertama huruf kecil
    //model PK : hasOn / hasMany
    //panggil namaModelRelasi::class
    public function StuffStock(){
        return $this->hasOne(StuffStock::class);
    }

    //relasi hasMany : nama func jamak -> huruf depannya kecil terus akhirannya menggunakan s
    public function inboundStuffs()
    {
        return $this->hasMany(InboundStuff::class);
    }

    public function lendings()
    {
        return $this->hasMany(Lending::class);
    }
}
