<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;


class InviteController extends Controller
{

    public function adminInviteUser(Request $request)
    {
        $data = $request->all();
        $data['invitation_link'] = "http://localhost/invite?email=". $request->email;

        Mail::send('emails.inviteUser', ['data' => $data], function ($m) use ($data) {
            $m->from('vaibhav@7technosoftsolutions.com', 'Your Application');
            $m->to($data['email'])->subject('Invitation Mail');
        });

        return response()->json([
            'message' => 'email sent Successfully',
        ]);
    }
}
