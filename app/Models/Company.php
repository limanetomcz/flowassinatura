<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'document',
        'contact_email',
        'contact_number'
    ];

    public function users() 
    {
        return $this->hasMany(User::class); 
    }
}
