<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->get();
        return view('clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required','string','max:255', Rule::unique('clients')->whereNull('deleted_at'),],
        ],
        [
            'name.unique' => 'This client already exists.',
        ]);

        $validated['user_id'] = auth()->id();
        Client::create($validated);

        return redirect()->route('clients.index')->with('success', 'Client added successfully.');
    }

    public function destroy(Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            return redirect()->route('clients.index')->with('error', 'You are not authorized to delete this client.');
        }
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client deleted successfully.');
    }

    public function update(Request $request, Client $client)
    {
        if ($client->user_id !== auth()->id()) {
            return redirect()->route('clients.index')->with('error', 'Unauthorized action');
        }

        $validated = $request->validate([
            'name' => ['required','string','max:255', Rule::unique('clients')->whereNull('deleted_at'),],
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Client updated successfully.');
    }

}

