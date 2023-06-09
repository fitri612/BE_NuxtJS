<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function responvibilities()
    {
        return $this->hasMany(Responsibility::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
    use HasFactory, SoftDeletes;
}
