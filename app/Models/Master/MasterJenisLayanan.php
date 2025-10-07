<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterJenisLayanan extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'master_jenis_layanans';

    protected $fillable = [
        'uid',
        'name',
        'description'
    ];
}
