<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEventRequest;
use App\Http\Requests\Admin\UpdateEventRequest;
use App\Models\Event;
use App\Models\Shop;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::query()
            ->with('shop')
            ->latest()
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $shops = Shop::query()->ordered()->get();

        return view('admin.events.create', compact('shops'));
    }

    public function store(StoreEventRequest $request)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title_en']);

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('events', 'public');
        }

        Event::create($data);

        return redirect()->route('admin.events.index')->with('status', 'Saved.');
    }

    public function edit(Event $event)
    {
        $shops = Shop::query()->ordered()->get();

        return view('admin.events.edit', compact('event', 'shops'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['title_en']);

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('events', 'public');
        }

        $event->update($data);

        return redirect()->route('admin.events.index')->with('status', 'Saved.');
    }

    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('admin.events.index')->with('status', 'Deleted.');
    }
}
