<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\Program\Events;
use Illuminate\Http\Request;
class EventController  extends Controller
{
    public function index()
    {
        return response()->json(Events::all());
    }

    public function show($id)
    {
        $event = Events::find($id);
        if (! $event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json($event);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'                => 'required|string|max:255',
            'category'             => 'required|string|max:100',
            'start_date'           => 'required|date',
            'end_date'             => 'required|date',
            'start_time'           => 'nullable',
            'end_time'             => 'nullable',
            'message'              => 'required|string',
            'location'             => 'required|string|max:100',
            'event_for_students'   => 'boolean',
            'event_for_teachers'   => 'boolean',
            'event_for_parents'    => 'boolean',
            'event_for_everyone'   => 'boolean',
        ]);

        $event = Events::create($validated);

        return response()->json(
           [
            'message' => 'Event created successfully',
            'event'   => $event
           ], 201
        );
    }

    public function update(Request $request, $id)
    {
        $event = Events::find($id);
        if (! $event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $validated = $request->validate([
            'title'                => 'sometimes|required|string|max:255',
            'category'             => 'sometimes|required|string|max:100',
            'start_date'           => 'sometimes|required|date',
            'end_date'             => 'sometimes|required|date',
            'start_time'           => 'nullable',
            'end_time'             => 'nullable',
            'message'              => 'sometimes|required|string',
            'location'             => 'sometimes|required|string|max:100',
            'event_for_students'   => 'boolean',
            'event_for_teachers'   => 'boolean',
            'event_for_parents'    => 'boolean',
            'event_for_everyone'   => 'boolean',
        ]);

        $event->update($validated);

        return response()->json($event);
    }

    public function destroy($id)
    {
        $event = Events::find($id);
        if (! $event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(data: ['message' => 'Event deleted successfully']);
    }
}
