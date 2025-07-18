<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\Program\EventCalendar;
use Illuminate\Http\Request;

class EventCalendarController extends Controller
{
    public function index()
    {
        return response()->json(EventCalendar::all());
    }

    public function show($id)
    {
        $event = EventCalendar::find($id);
        if (! $event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json($event);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'type'            => 'nullable|string|max:20',
            'color'           => 'nullable|string|max:20',
            'start_datetime'  => 'required|date',
            'end_datetime'    => 'required|date',
        ]);

        $event = EventCalendar::create($validated);

        return response()->json(
            [
                'message' => 'Event created successfully',
                'id'   => $event->id,
            ], 201
        );
    }

    public function update(Request $request, $id)
    {
        $event = EventCalendar::find($id);
        if (! $event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $validated = $request->validate([
            'title'           => 'sometimes|required|string|max:255',
            'description'     => 'nullable|string',
            'type'            => 'nullable|string|max:20',
            'color'           => 'nullable|string|max:20',
            'start_datetime'  => 'sometimes|required|date',
            'end_datetime'    => 'sometimes|required|date',
        ]);

        $event->update($validated);

        return response()->json($event);
    }

    public function destroy($id)
    {
        $event = EventCalendar::find($id);
        if (! $event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
