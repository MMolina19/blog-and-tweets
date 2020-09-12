<?php

namespace App\Http\Controllers;

use App\Entry;
use Illuminate\Http\Request;

class EntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Laravel nos provee de un middleware llamado auth,
    }           // que en este caso va a proteger todos los metodos de este controlador,
                // para que solo puedan acceder usuarios que hayan iniciado sesión.

    public function create()
    {
        return view('entries.create');
    }

    public function store(Request $request)
    {
        //dd($request->all());
        $validatedData = $request->validate([
            'title'     =>  'required|min:7|max:255|unique:entries',
            'content'   =>  'required|min:25|max:3000',
        ]);

        $entry = new Entry();
        $entry->title= $validatedData['title'];
        $entry->content = $validatedData['content'];
        $entry->user_id = auth()->id();
        $entry->save(); //INSERT

        $status = 'Your entry has been published successfully.';
        return back()->with(compact('status'));
    }

    public function edit(Entry $entry)
    {
        /*if( auth()->id() !== $entry->user_id ){
            return redirect('/');
        }*/
        $this->authorize( 'update', $entry);
        return view('entries.edit', compact('entry'));
    }

    public function update(Request $request, Entry $entry)
    {
        /*if( auth()->id() !== $entry->user_id ){
            return redirect('/');
        }*/
        $this->authorize( 'update', $entry);

        $validatedData = $request->validate([
            'title'     =>  'required|min:7|max:255|unique:entries,id,'.$entry->id,
            'content'   =>  'required|min:25|max:3000',
        ]);

        // TODO: Allow edit action only for the entry's Author
        // auth()->id() === $entry->id
        $entry->title= $validatedData['title'];
        $entry->content = $validatedData['content'];
        $entry->save(); //INSERT

        $status = 'Your entry has been successfully updated.';
        return back()->with(compact('status'));
    }
}
