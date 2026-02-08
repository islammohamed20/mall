<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        // Hide the events section from the public when there are no active (current/upcoming) events.
        abort_unless(
            Event::query()->active()->where(function ($q) {
                $q->current()->orWhere(function ($q2) {
                    $q2->upcoming();
                });
            })->exists(),
            404
        );

        $eventsQuery = Event::query()
            ->active()
            ->with('shop')
            ->ordered();

        if ($request->boolean('upcoming')) {
            $eventsQuery->upcoming();
        } else {
            $eventsQuery->current();
        }

        $events = $eventsQuery->paginate(12)->withQueryString();

        return view('pages.events.index', compact('events'));
    }

    public function show(Event $event)
    {
        if (! $event->is_active) {
            abort(404);
        }

        $event->load('shop');

        return view('pages.events.show', compact('event'));
    }
}
