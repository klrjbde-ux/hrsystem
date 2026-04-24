<?php
namespace App\Http\Controllers;

use App\Models\ChecklistItem;
use App\Models\Task;
use Illuminate\Http\Request;

class ChecklistItemController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'task_id' => 'required|exists:tasks,id',
    ]);

    $item = ChecklistItem::create([
        'task_id' => $request->task_id,
        'name' => $request->name,
        'completed' => false
    ]);

    return response()->json([
        'success' => true,
        'id' => $item->id,
        'name' => $item->name,
        'completed' => $item->completed
    ]);
}

    public function updateStatus(ChecklistItem $checklistItem)
{
    $checklistItem->completed = !$checklistItem->completed;
    $checklistItem->save();

    return response()->json([
        'success' => true
    ]);
}

    public function update(Request $request, ChecklistItem $checklistItem)
    {
        $checklistItem->update([
            'completed' => $request->has('completed'),
            'name' => $request->name,
        ]);
        return back()->with('success', 'Checklist item updated successfully.');
    }

    public function destroy(ChecklistItem $checklistItem)
    {
        $checklistItem->delete();
        return response()->json([
            'success' => true,
        ]);
    }
}
