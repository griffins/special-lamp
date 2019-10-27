<?php

namespace App\Http\Controllers;

use App\SupportTicket;

class SupportTicketController extends Controller
{
    use RepliesTicket;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:client');
    }

    public function index($action = null)
    {
        switch ($action) {
            case 'listing':
                return $this->listing();
            case 'open':
                return $this->openTicket();
            case 'view':
                return $this->viewTicket();
            case 'response':
                return $this->replyTicket();
            default:
                return redirect(route('support.ticket', ['action' => 'listing']));
        }
    }

    public function listing()
    {
        return view('support.listing');
    }

    public function viewTicket()
    {
        $ticket = SupportTicket::query()->findOrFail(\request('ticket'));
        return view('support.view', compact('ticket'));
    }

    public function openTicket()
    {
        $types = ['General', 'Dispute', 'Financial'];

        if (\request()->isMethod('post')) {
            \request()->validate([
                'type' => 'required|in:' . implode(',', $types),
                'subject' => 'required',
                'narration' => 'required',
            ]);
            $ticket = new SupportTicket(\request()->only('type', 'subject', 'narration'));
            auth()->user()->tickets()->save($ticket);
            return redirect(route('support.ticket'))->withSuccess("Ticket Opened");
        } else {
            return view('support.open', compact('types'));
        }
    }
}
