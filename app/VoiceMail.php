<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoiceMail extends Model
{
    protected $fillable = ['rno','maildate','telno', 'voicefile', 'regdate', 'cusrno', 'issuerno', 'readstate', 'comment', 'filename', 'maildate2'];
    protected $table = 'tblmaillog';
}
