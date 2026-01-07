<?php

namespace App\Http\Controllers;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


use Illuminate\Support\Facades\Notification;
use App\Notifications\OrderShippedSMS;


class FAQ extends Controller
{
    public function index()
    {
        return view('faq');
    }
}