<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TaskController extends Controller
{
    /**
     * To send the JSON data for the tasks.
     */
    public function json()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    /**
     * Display the tabulator view.
     */
    public function tabulator()
    {
        $tasks = Task::all();
        return view('tasks.tabulator', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::all();
        return view('tasks.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client' => 'required|exists:clients,name',
            'task_bug_name' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'dev_status' => 'required|in:New,In-progress,Completed,On-hold,NA',
            'unit_test_status' => 'required|in:New,In-progress,Completed,On-hold,NA',
            'staging_status' => 'required|in:New,In-progress,Completed,On-hold,NA',
            'prod_status' => 'required|in:New,In-progress,Completed,On-hold,NA',
            'comment' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        Task::create($validated);

        return redirect()->route('tasks.tabulator')->with('success', 'Task created successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::find($id);
        if(!$task){
            return redirect()->route('tasks.tabulator')->with('error', 'No such task exist for task id '.$id);
        }
        $clients = Client::all();
        if ($task->user_id !== auth()->id()) {
            return redirect()->route('tasks.tabulator')->with('error', 'You are not authorized to edit this task.');
        }
        return view('tasks.edit', compact('task','clients'));
    }

    /**
     * To Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::find($id);
        if(!$task){
            return redirect()->route('tasks.tabulator')->with('error', 'No such task exist for task id '.$id);
        }

        $validated = $request->validate([
            'client' => 'required|exists:clients,name',
            'task_bug_name' => 'required|string|max:255',
            'owner' => 'required|string|max:255',
            'priority' => 'required|in:Low,Medium,High',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'dev_status' => 'required|in:New,In-progress,Completed,On-hold,NA',
            'unit_test_status' => 'required|in:New,In-progress,Completed,On-hold,NA',
            'staging_status' => 'required|in:New,In-progress,Completed,On-hold,NA',
            'prod_status' => 'required|in:New,In-progress,Completed,On-hold,NA',
            'comment' => 'nullable|string',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.tabulator')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::find($id);
        if(!$task){
            return redirect()->route('tasks.tabulator')->with('error', 'No such task exist for task id '.$id);
        }

        if ($task->user_id !== auth()->id()) {
            return redirect()->route('tasks.tabulator')->with('error', 'You are not authorized to delete this task.');
        }
        $task->delete();

        return redirect()->route('tasks.tabulator')->with('success', 'Task deleted successfully.');
    }

    /**
     * autoSuggest task/bug name
     */
    public function autoSuggest(Request $request)
    {
        $query = $request->input('query');

        $suggestions = Task::where('task_bug_name', 'LIKE', '%' . $query . '%')
            ->distinct()
            ->limit(5)
            ->pluck('task_bug_name');

        return response()->json($suggestions);
    }


}
