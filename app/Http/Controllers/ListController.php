<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class ListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request) {
        $status = $request->input('status');
        $date = $request->input('date');
        $sort = $request->input('sort');
    
        $tasksQuery = Task::query();
        
        if ($status) {
            $tasksQuery->where('status', $status);
        }
    
        if ($date) {
            $tasksQuery->whereDate('deadline', $date);
        }
    
        if ($sort) {
            $tasksQuery->orderBy('name', $sort);
        }
    
        $tasks = $tasksQuery->get();
    
        return view('home', compact('tasks'));
    }

    public function statuses() {

        $tasks = Task::where('status', 'активно')->get();
        
        return view('home', compact('tasks'));
    }

    public function edit(Request $request, $id) {
        $findTask = Task::findOrFail($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:9999',
            'status' => 'required|string|max:255',
            'deadline' => 'required|date',
        ]);
        $findTask->update($request->all());

        return redirect()->back()->with('success', 'Задача успешно обновлена!');
    }

    public function create(Request $request) {
        $val = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:9999',
            'status' => 'required|string|max:255',
            'deadline' => 'required|date',
        ]);
        Task::create($val);

        return redirect()->back()->with('success', 'Задача успешно создана!');
    }

    public function destroy($id) {
        $task = Task::findOrFail($id);
        $task->delete();
    
        return redirect()->back()->with('success', 'Задача успешно удалена!');
    }
}
