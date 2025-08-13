<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Program\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(Events $event): array
    {
        return [
            'id'               => $event->id,
            'title'            => $event->title,
            'category'         => $event->category,
            'startDate'        => $event->start_date? \Carbon\Carbon::parse($event->start_date) ->toDateString(): null,
            'endDate'          => $event->end_date? \Carbon\Carbon::parse($event->end_date)->toDateString():null,
            'startTime'        => $event->start_time,
            'endTime'          => $event->end_time,
            'message'          => $event->message,
            'location'         => $event->location,
            'eventForStudents' => (bool)$event->event_for_students,
            'eventForTeachers' => (bool)$event->event_for_teachers,
            'eventForParents'  => (bool)$event->event_for_parents,
            'eventForEveryone' => (bool)$event->event_for_everyone,
            'createdAt'        => $event->created_at?  \Carbon\carbon::parse($event->created_at)->toIso8601String():null,
            'updatedAt'        => $event->updated_at?  \Carbon\carbon::parse($event->updated_at)->toIso8601String():null,
        ];
    }

    public function index()
    {
        $events = Events::all()->map(fn($event) => $this->formatResponse($event));
        return response()->json($events);
    }

    public function show($id)
    {
        $event = Events::find($id);
        if (! $event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json($this->formatResponse($event));
    }

    public function store(Request $request)
    {
        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'title'              => 'required|string|max:255',
            'category'           => 'required|string|max:100',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date',
            'start_time'         => 'nullable',
            'end_time'           => 'nullable',
            'message'            => 'required|string',
            'location'           => 'required|string|max:100',
            'event_for_students' => 'boolean',
            'event_for_teachers' => 'boolean',
            'event_for_parents'  => 'boolean',
            'event_for_everyone' => 'boolean',
        ])->validate();

        $event = Events::create($validated);

        return response()->json([
            'message' => 'Event created successfully',
            'event'   => $this->formatResponse($event),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $event = Events::find($id);
        if (! $event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'title'              => 'sometimes|required|string|max:255',
            'category'           => 'sometimes|required|string|max:100',
            'start_date'         => 'sometimes|required|date',
            'end_date'           => 'sometimes|required|date',
            'start_time'         => 'nullable',
            'end_time'           => 'nullable',
            'message'            => 'sometimes|required|string',
            'location'           => 'sometimes|required|string|max:100',
            'event_for_students' => 'boolean',
            'event_for_teachers' => 'boolean',
            'event_for_parents'  => 'boolean',
            'event_for_everyone' => 'boolean',
        ])->validate();

        $event->update($validated);

        return response()->json($this->formatResponse($event));
    }

    public function destroy($id)
    {
        $event = Events::find($id);
        if (! $event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
