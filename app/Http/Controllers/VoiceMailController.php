<?php

namespace App\Http\Controllers;

use App\Repositories\VoiceMailRepository;
use Carbon\Carbon;
use Webklex\IMAP\Client;

use App\VoiceMail;
use Illuminate\Http\Request;

class VoiceMailController extends Controller
{
    protected $voiceMailRepository;

    public function __construct(VoiceMailRepository $voiceMailRepository)
    {
        $this->voiceMailRepository = $voiceMailRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllVoiceMail()
    {
        try {
            $allVoiceMails = $this->voiceMailRepository->getAllVoiceMails();
            return response()->json(['data' => $allVoiceMails], 200);
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception], 500);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            if (function_exists('imap_open')) {
                $oClient = $this->voiceMailRepository->getClient();
                $mbox = imap_open("{".env('IMAP_HOST')."}INBOX", env('IMAP_USERNAME'), env('IMAP_PASSWORD'))
                or die("Can't connect: " . imap_last_error());
                $oClient->connect();
                $aFolder = $oClient->getFolders();
                foreach ($aFolder as $oFolder) {
                    $aMessage = $oFolder->messages()->all()->get();
                    foreach ($aMessage as $oMessage) {
                        $subject = $oMessage->getSubject();
                        $date = $oMessage->getDate();
                        $from = $oMessage->getFrom();
                        $subjectExplode = explode(' ', $subject);
                        $telno = $subjectExplode[sizeof($subjectExplode) - 1];
                        $telnoExplode = explode('+44', $telno);
                        if (sizeof($telnoExplode) == 2) {
                            $newTelno = '0' . $telnoExplode[1];
                        } else {
                            $newTelno = 0;
                        }
                        if ($from[0]->personal === env('IMAP_EMAIL_RECEIVER', 'Dimuthu Jayalath')) {
                            $aAttachment = $oMessage->getAttachments();

                            foreach ($aAttachment as $oAttachment) {
                                if ('' . $oAttachment->getExtension() == 'mpga') {
                                    $fileName = 'attachment' . '-' . (string)rand(00000, 99999) . '.' . $oAttachment->getExtension();
                                    $oAttachment->save(public_path() . '/attachment', $fileName);
                                    $this->voiceMailRepository->storeVoiceMails($newTelno, $fileName, $date);
//                                    for ($x = 1; $x <= sizeof($aMessage); $x++) {
//                                        imap_delete($mbox, $x);
//                                    }
                                }
                            }
                        }
                        $oMessage->moveToFolder('INBOX.read');
                    }
                }
                return response()->json(['success' => 'Voice Mails created Successfully'], 200);
            } else {
                return response()->json(['error' => 'IMAP functions are not available.'], 404);
            }
        } catch (\Exception $exception) {
            return response()->json(['error' => $exception], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\VoiceMail $voiceMail
     * @return \Illuminate\Http\Response
     */
    public function show(VoiceMail $voiceMail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\VoiceMail $voiceMail
     * @return \Illuminate\Http\Response
     */
    public function edit(VoiceMail $voiceMail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\VoiceMail $voiceMail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VoiceMail $voiceMail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\VoiceMail $voiceMail
     * @return \Illuminate\Http\Response
     */
    public function destroy(VoiceMail $voiceMail)
    {
        //
    }
}
