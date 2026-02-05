<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
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
