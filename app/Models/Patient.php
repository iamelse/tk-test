<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $guarded = [ 'id' ];

    /**
     * Scope untuk mencari pasien berdasarkan nama atau phone
     */
    public function scopeSearch($query, $term)
    {
        if ($term) {
            $query->where('name', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
        }
        return $query;
    }

    /**
     * Scope untuk mengurutkan data terbaru terlebih dahulu
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope untuk memfilter pasien berdasarkan rumah sakit
     */
    public function scopeHospital($query, $hospitalId)
    {
        if (!$hospitalId) return $query;

        return $query->where('hospital_id', $hospitalId);
    }
    
    /**
     * Relasi ke model Hospital
     */
    public function hospital()
    {
        return $this->belongsTo(Hospital::class);
    }

    /**
     * Accessor untuk mendapatkan nama rumah sakit
     */
    public function getHospitalNameAttribute()
    {
        return $this->hospital->name ?? '-';
    }
}
