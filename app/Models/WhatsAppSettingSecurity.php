<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppSettingSecurity extends Model
{
    protected $table = 'whatsapp_setting_security';

    protected $fillable = [
        'developer_passcode',
        'master_passcode_hash',
    ];

    protected $hidden = [
        'developer_passcode',
        'master_passcode_hash',
    ];
}
