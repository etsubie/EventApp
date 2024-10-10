<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        // Check if the user has permission to view events
        if ($user->can('view events')) {
            // Retrieve events based on the user's role
            if ($user->hasRole('admin')) {
                // Admin can see all events
                $events = Event::all();
            } else if ($user->hasRole('host')) {
                // Host can see only his own events
                $events = Event::where('user_id', $user->id)->get();
            } else if ($user->hasRole('attendee')) {
                // Attendees can see only approved events
                $events = Event::where('status', 'approved')->get();
            }

            return response()->json([
                'message' => 'Events retrieved successfully',
                'events' => $events,
            ], 200);
        }

        return response()->json([
            'message' => 'Unauthorized access to events',
        ], 403);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            "user_id" => "required",
            "title" => "required|max:255",
            "description" => "required",
            "category_id" => "required|exists:categories,id",
            "location" => "required|max:255",
            "event_date" => "required",
            "start_date" => "required|date_format:H:i",
            "end_date" => "required|date_format:H:i",
            "ticket_price" => "required",
            "capacity" => "required",
            "imgUrl" => "required"
        ]);
        // Check if the user has the permission to create events
        if (!Auth::user()->can('create events')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $event = Event::create($data);
        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event,
        ], 201);
    }
    public function show($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json($event, 200);
    }

    public function update(Request $request, $id)
    {
        try {
            $event = Event::findOrFail($id); 
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Event not found'], 404);
        }
    
        // Check if the user has permission to update the event
        if (!Auth::user()->can('manage events')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $event->update($request->all());
        return response()->json($event);
    }
    
    public function destroy(Request $request, Event $event)
    {
        // Check if the user has permission to delete the event
        if (!Auth::user()->can('manage events')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $event->delete();
        return ["message" => "Event Was Deleted"];
    }
}
