<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'mobile', 'password', 'address', 'email', 'logo', 'category_id', 'active', 'created_at', 'updated_at'
    ];
    // protected $fillable = [
    //     'latitude', 'longitude', 'name', 'mobile', 'password', 'address', 'email', 'logo', 'category_id', 'active', 'created_at', 'updated_at'
    // ];

    protected $hidden = ['category_id', 'password'];


    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function getLogoAttribute($val)
    {
        return ($val !== null) ? asset('uploads/vendors/' . $val) : "";

    }


    public function scopeSelection($query)
    {
        return $query->select('id', 'category_id', 'active', 'name', 'address', 'email', 'logo', 'mobile');
    }


    public function category()
    {

        return $this->belongsTo(MainCategory::class, 'category_id', 'id');
    }

    public function getActive()
    {
        return $this->active == 1 ? 'مفعل' : 'غير مفعل';

    }


    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }
}
