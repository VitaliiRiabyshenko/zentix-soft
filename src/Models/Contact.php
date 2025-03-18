<?php

namespace Vitaliiriabyshenko\Contacts\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name'];

    public $timestamps = false;

    protected static function newFactory()
    {
        return \Vitaliiriabyshenko\Contacts\Database\Factories\ContactFactory::new();
    }

    public function phones(): HasMany
    {
        return $this->hasMany(Phone::class, 'contact_id');
    }
}
