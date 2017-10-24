<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $primaryKey = 'member_id';

    // 操作対象フィールド
    protected $fillable = ['status','email', 'name', 'sex'];

    public function orders()
    {
        return $this->hasMany('\App\Models\Order', 'member_id');
    }
}
