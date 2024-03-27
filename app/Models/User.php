<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\DesignQueue;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The Notifiable trait provides functionality to send notifications to users.
     * It defines the `notify` method, which sends a notification to the user via a channel.
     * The trait also defines the `routeNotificationFor` method, which specifies the routing information for notifications.
     */
    /**
     * FILEPATH: d:\Laravel\darigit\antree-apps\app\Models\User.php
     *
     * The User model represents a user in the application.
     * It uses the HasApiTokens, HasFactory, Notifiable, and CanResetPassword traits.
     */
    use HasApiTokens, HasFactory, Notifiable, CanResetPassword;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    public function isSales()
    {
        return $this->role_id == 11;
    }

    public function isDesigner()
    {
        return $this->can_design == 1;
    }

    public function isSpvDesain()
    {
        return $this->role_id == 17;
    }

    public function isCustomer()
    {
        return $this->role == 'customer';
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function sales()
    {
        return $this->hasOne(Sales::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }

    public function designQueue()
    {
        return $this->hasMany(DesignQueue::class, 'designer_id', 'id');
    }

    public function ambilJumlahAntrian($idUser)
    {
        $jumlahAntrian = $this->designQueue()->where('designer_id', $idUser)->where('status', 1)->count();
        return $jumlahAntrian;
    }
}
