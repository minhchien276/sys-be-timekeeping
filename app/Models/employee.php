<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class employee extends Model implements Authenticatable, JWTSubject, CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'employee';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'employeeCode',
        'image',
        'fullname',
        'birthday',
        'identification',
        'salary',
        'dayOff',
        'email',
        'phone',
        'password',
        'status',
        'departmentId',
        'roleId',
        'leaderId',
        'device_token',
        'keySearch',
        'logged',
        'expired',
        'createdAt',
        'updatedAt',
    ];

    public static function createEmployee(array $data)
    {
        $now = Carbon::now()->timestamp * 1000;

        $data['employeeCode'] = Str::uuid();
        $data['status'] = 1;
        $data['password'] = bcrypt(123456);
        $data['createdAt'] = $now;

        return self::create($data);
    }

    public $timestamps = false;

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getAuthIdentifierName()
    {
        // Phương thức trả về tên cột chứa ID người dùng
        return 'id';
    }

    public function getAuthIdentifier()
    {
        // Phương thức trả về giá trị ID người dùng
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        // Phương thức trả về mật khẩu người dùng
        return $this->password;
    }

    public function getRememberToken()
    {
        // Phương thức trả về giá trị Remember Token người dùng
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        // Phương thức thiết lập giá trị Remember Token người dùng
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        // Phương thức trả về tên cột chứa Remember Token
        return 'remember_token';
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function sendPasswordResetNotification($token)
    {
        // Gửi email đặt lại mật khẩu tới người dùng
        // Code gửi email ở đây
    }
}
