<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\Reply;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ContactMessageController extends Controller
{
    public function storemessage(Request $request)
    {
        // Validate form input
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:15',
            'subject'    => 'required|string|max:255',
            'message'    => 'required|string',
        ]);

        // Check if user is authenticated
        if (auth()->check()) {
            $user_id = auth()->id();  // Get authenticated user's ID
        } else {
            $user_id = null;  // Set to null if user is not authenticated
        }

        // Determine the message type
        $messageType = $user_id ? 'user' : 'guest'; // 'user' if authenticated, 'guest' otherwise

        // Save contact message to the database
        ContactMessage::create([
            'first_name'   => $request->input('first_name'),
            'last_name'    => $request->input('last_name'),
            'email'        => $request->input('email'),
            'phone'        => $request->input('phone'),
            'subject'      => $request->input('subject'),
            'message'      => $request->input('message'),
            'message_type' => $messageType,  // Save the message type
            'user_id'      => $user_id, // Save the user_id if authenticated, otherwise null
        ]);

        // Redirect or send a response
        return redirect()->back()->with('success', 'Your message has been sent successfully. We will get back to you as soon as possible.');
    }


    public function contactmessage()
    {
        // Fetch all contact messages with replies (eager loaded)
        $contactMessages = ContactMessage::with('replies')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.contactmessage', compact('contactMessages'));
    }


    public function markAsReadOnClick($id)
    {
        // Find the reply and mark it as read
        $reply = Reply::find($id);
        if ($reply && !$reply->read_status) {
            $reply->read_status = true;
            $reply->save();
        }

        return response()->json(['success' => true]);
    }


    public function clientMessage()
    {
        // Retrieve only messages and replies associated with the authenticated client
        $contactMessages = ContactMessage::with(['replies' => function ($query) {
            $query->where('user_id', auth()->id()); // Limit to replies for the authenticated client
        }])
            ->where('user_id', auth()->id()) // Limit messages to the authenticated client
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('client.clientmessage', compact('contactMessages'));
    }



    // public function storeReply($contactMessageId, Request $request)
    // {
    //     // Validate the incoming request data
    //     $validated = $request->validate([
    //         'reply_message' => 'required|string',
    //     ]);

    //     // Retrieve the contact message
    //     $contactMessage = ContactMessage::findOrFail($contactMessageId);

    //     // Make sure the contact message is from a registered user
    //     if ($contactMessage->message_type == 'guest') {
    //         return back()->withErrors(['error' => 'Cannot reply to a guest message.']);
    //     }

    //     // Create the reply
    //     $reply = new Reply();
    //     $reply->contact_message_id = $contactMessage->id;  // Link reply to the contact message
    //     $reply->admin_id = auth()->id();  // Associate reply with the admin
    //     $reply->user_id = $contactMessage->user_id;  // Set to the client’s user_id
    //     $reply->reply_message = $validated['reply_message'];

    //     // Save the reply
    //     $reply->save();

    //     // Mark the contact message as read
    //     $contactMessage->read_status = true; // assuming `true` means "read"
    //     $contactMessage->save();

    //     // Redirect back with a success message
    //     return back()->with('success', 'Reply sent successfully!');
    // }

    public function storeReply(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'reply_message' => 'required|string',
            'message_id' => 'required|exists:contact_messages,id'
        ]);

        // Retrieve the contact message
        $contactMessage = ContactMessage::findOrFail($validated['message_id']);

        // Ensure the contact message is from a registered user
        if ($contactMessage->message_type == 'guest') {
            return response()->json(['success' => false, 'message' => 'Cannot reply to a guest message.']);
        }

        // Create the reply
        $reply = new Reply();
        $reply->contact_message_id = $contactMessage->id;
        $reply->admin_id = auth()->id();
        $reply->user_id = $contactMessage->user_id;
        $reply->reply_message = $validated['reply_message'];

        // Save the reply
        $reply->save();

        // Mark the contact message as read
        $contactMessage->read_status = true;
        $contactMessage->save();

        // Return a JSON response to indicate success
        // return response()->json(['success' => true, 'message' => 'Reply sent successfully!']);
        return back()->with('success', 'Reply sent successfully!');
    }







    public function markAsRead($id)
    {
        // Find the contact message
        $contactMessage = ContactMessage::findOrFail($id);

        // Update the read status to true
        $contactMessage->read_status = true;
        $contactMessage->save();

        return redirect()->back()->with('success', 'Message marked as read.');
    }

    public function markAsReadReply($id)
    {
        // Find the contact message
        $contactMessage = Reply::findOrFail($id);

        // Update the read status to true
        $contactMessage->read_status = true;
        $contactMessage->save();

        return redirect()->back()->with('success', 'Reply marked as read.');
    }


    public function markAsReadOnClickAdmin($id)
    {
        $contactMessage = ContactMessage::find($id);
        if ($contactMessage) {
            $contactMessage->read_status = true;
            $contactMessage->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}
