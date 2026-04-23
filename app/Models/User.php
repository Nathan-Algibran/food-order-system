<?php
/**
 * Purpose: User / Pengguna model (also used for Admin via role column)
 * Used by: Auth, all controllers
 * Dependencies: Illuminate\Foundation\Auth\User, HasFactory
 * Main functions: pemesanans(), ulasans()
 * Side effects: None
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'nama', 'email', 'alamat', 'password', 'role',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function pemesanans()
    {
        return $this->hasMany(Pemesanan::class, 'id_pengguna', 'id_pengguna');
    }

    public function ulasans()
    {
        return $this->hasMany(Ulasan::class, 'id_pengguna', 'id_pengguna');
    }
}
