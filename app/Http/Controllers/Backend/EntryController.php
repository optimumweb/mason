<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\EntryType;
use App\Models\Locale;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntryType  $entryType
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, EntryType $entryType)
    {
        $query = Entry::byType($entryType);

        if ($search = $request->input('search')) {
            $query->search($search);
        }

        if ($filters = $request->input('filters')) {
            foreach ($filters as $attribute => $values) {
                $query->whereIn($attribute, $values);
            }
        }

        $entries = $query->get();

        return response()->view('backend.entries.index', [
            'entries' => $entries,
            'entryType' => $entryType,
            'filters' => $filters ?? null,
            'search' => $search ?? null,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntryType  $entryType
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, EntryType $entryType)
    {
        $entry = new Entry;
        $entry->type()->associate($entryType);
        $entry->locale()->associate(Locale::default());
        $entry->author()->associate($request->user());

        return response()->view('backend.entries.create', compact('entryType', 'entry'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntryType  $entryType
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, EntryType $entryType)
    {
        $entry = new Entry;
        $entry->type()->associate($entryType);
        $entry->locale()->associate(Locale::default());
        $entry->author()->associate($request->user());
        $entry->fill($request->input('entry'));
        $entry->save();

        return response()->redirectToRoute('backend.entries.index', [$entryType]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntryType  $entryType
     * @param  \App\Models\Entry  $entry
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, EntryType $entryType, Entry $entry)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntryType  $entryType
     * @param  \App\Models\Entry  $entry
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, EntryType $entryType, Entry $entry)
    {
        return response()->view('backend.entries.edit', compact('entryType', 'entry'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntryType  $entryType
     * @param  \App\Models\Entry  $entry
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EntryType $entryType, Entry $entry)
    {
        $entry->update($request->input('entry'));

        return response()->redirectToRoute('backend.entries.edit', [$entryType, $entry]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntryType  $entryType
     * @param  \App\Models\Entry  $entry
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, EntryType $entryType, Entry $entry)
    {
        //
    }
}
