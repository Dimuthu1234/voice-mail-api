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
        return $this->model->orderBy('id', 'desc')->get();
    }
    public function storeVoiceMails($newTelno, $fileName, $date)
    {
        $voiceMail = new $this->model;
        $voiceMail->create([
            'telno' => $newTelno,
            'voicefile' => $fileName,
            'maildate' => Carbon::parse($date)->format('Y-m-d'),
        ]);
        return $voiceMail;
    }

    public function getClient(){
        $oClient = new Client([
            'host' => env('IMAP_HOST', 'mail.iposg.net'),
            'port' => env('IMAP_PORT', 993),
            'encryption' => env('IMAP_ENCRYPTION', 'ssl'),
            'validate_cert' => env('IMAP_VALIDATE_CERT', true),
            'username' => env('IMAP_USERNAME', 'dimuthu@iposg.com'),
            'password' => env('IMAP_PASSWORD', '123456'),
            'protocol' => env('IMAP_PROTOCOL', 'imap')
        ]);
        return $oClient;
    }
}
