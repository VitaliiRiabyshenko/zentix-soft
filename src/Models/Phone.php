<?php

namespace Vitaliiriabyshenko\Contacts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Phone extends Model
{
    use HasFactory;

    protected $fillable = ['contact_id', 'value'];

    public $timestamps = false;

    protected static function newFactory()
    {
        return \Vitaliiriabyshenko\Contacts\Database\Factories\PhoneFactory::new();
    }


    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
