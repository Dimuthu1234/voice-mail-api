<?php namespace App\Repositories;

use Bosnadev\Repositories\Eloquent\Repository;
use Carbon\Carbon;
use Webklex\IMAP\Client;

class VoiceMailRepository extends Repository
{
    public function model()
    {
        return 'App\VoiceMail';
    }

    public function getAllVoiceMails()
    {
        return $this->model->orderBy('rno', 'desc')->where('maildate', Carbon::now()->toDateTimeString())->get();
    }

    public function storeVoiceMails($newTelno, $fileName, $date)
    {
        $voiceMail = new $this->model;
        $voiceMail->create([
            'telno' => $newTelno,
            'voicefile' => $fileName,
            'maildate' => Carbon::parse($date)->format('Y-m-d'),
            'regdate' => Carbon::now(),
            'cusrno' => null,
            'issuerno' => 0,
            'readstate' => 0,
            'comment' => 'CANT FIND',
            'filename' => $fileName,
            'maildate2' => null,
        ]);
        return $voiceMail;
    }

    public function getClient()
    {
        $oClient = new Client([
            'host' => env('IMAP_HOST'),
            'port' => env('IMAP_PORT'),
            'encryption' => env('IMAP_ENCRYPTION'),
            'validate_cert' => env('IMAP_VALIDATE_CERT'),
            'username' => env('IMAP_USERNAME'),
            'password' => env('IMAP_PASSWORD'),
            'protocol' => env('IMAP_PROTOCOL')
        ]);
        return $oClient;
    }
}
