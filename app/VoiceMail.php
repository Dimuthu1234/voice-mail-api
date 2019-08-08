<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoiceMail extends Model
{
    protected $fillable = ['telno', 'voicefile', 'maildate', 'cus_no'];
    protected $table = 'voice_mails';
}
