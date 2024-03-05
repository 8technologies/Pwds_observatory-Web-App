<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    /**
     * Ran by an organisation
     */
    public function organisation()
    {
        return $this->belongsTo(Organisation::class);
    }

}
