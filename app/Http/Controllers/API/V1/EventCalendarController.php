<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Program\EventCalendar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventCalendarController extends Controller
{
    private function convertCamelToSnake(array $input): array
    {
        return collect($input)
            ->mapWithKeys(fn($value, $key) => [Str::snake($key) => $value])
            ->toArray();
    }

    private function formatResponse(EventCalendar $event): array
    {
        return [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'type' => $event->type,
            'color' => $event->color,
            'startDatetime' => $event->start_datetime ? \Carbon\Carbon::parse($event->start_datetime)->toIso8601String() : null,
            'endDatetime' => $event->end_datetime ? \Carbon\Carbon::parse($event->end_datetime)->toIso8601String() : null,
            'createdAt' => $event->created_at ? \Carbon\Carbon::parse($event->created_at)->toIso8601String() : null,
            'updatedAt' => $event->updated_at ? \Carbon\Carbon::parse($event->updated_at)->toIso8601String() : null,
        ];
    }

    public function index()
    {
        $events = EventCalendar::all()->map(fn($event) => $this->formatResponse($event));
        return response()->json($events);
    }

    public function show($id)
    {
        $event = EventCalendar::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }
        return response()->json($this->formatResponse($event));
    }

    public function store(Request $request)
    {
        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:20',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
        ])->validate();

        $event = EventCalendar::create($validated);

        return response()->json([
            'message' => 'Event created successfully',
            'data' => $this->formatResponse($event),
        ], 201);

    }

    public function update(Request $request, $id)
    {
        $event = EventCalendar::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $data = $this->convertCamelToSnake($request->all());

        $validated = validator($data, [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'nullable|string|max:20',
            'color' => 'nullable|string|max:20',
            'start_datetime' => 'sometimes|required|date',
            'end_datetime' => 'sometimes|required|date',
        ])->validate();

        $event->update($validated);

        return response()->json($this->formatResponse($event));
    }

    public function destroy($id)
    {
        $event = EventCalendar::find($id);
        if (!$event) {
            return response()->json(['message' => 'Event not found'], 404);
        }

        $event->delete();

        return response()->json(['message' => 'Event deleted successfully']);
    }
}
