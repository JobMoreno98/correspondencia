<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Oficios extends Model
{
    protected $table = 'oficios';
    protected $guarded = [];
    
    public function envia(): BelongsTo
    {
        //dd(Oficios::with('envia')->first());
        return $this->belongsTo(Destinatario::class, 'envia_id');
    }

    public function recibe(): BelongsTo
    {
        //dd(Oficios::with('envia')->first());
        return $this->belongsTo(Destinatario::class, 'turna_id');
    }
}
