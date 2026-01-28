<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Contact;
use App\Models\Account;
use App\Models\Deal;
use App\Models\Task;

class BulkActionController extends Controller
{
    public function bulkAction(Request $request)
    {
        $request->validate([
            'resource' => 'required|string',
            'action' => 'required|string',
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);
        
        $resource = $request->resource;
        $action = $request->action;
        $ids = $request->ids;
        
        $model = $this->getModel($resource);
        
        if (!$model) {
            return response()->json(['error' => 'Invalid resource'], 400);
        }
        
        $records = $model::whereIn('id', $ids)->get();
        
        switch ($action) {
            case 'delete':
                foreach ($records as $record) {
                    $record->delete();
                }
                return response()->json(['message' => count($ids) . ' records deleted successfully']);
                
            case 'update_status':
                $request->validate(['status' => 'required|string']);
                foreach ($records as $record) {
                    if (isset($record->status)) {
                        $record->update(['status' => $request->status]);
                    }
                }
                return response()->json(['message' => count($ids) . ' records updated successfully']);
                
            case 'assign':
                $request->validate(['user_id' => 'required|exists:users,id']);
                $field = $this->getAssignField($resource);
                if ($field) {
                    foreach ($records as $record) {
                        $record->update([$field => $request->user_id]);
                    }
                    return response()->json(['message' => count($ids) . ' records assigned successfully']);
                }
                return response()->json(['error' => 'Assignment not available for this resource'], 400);
                
            case 'add_tag':
                $request->validate(['tag_id' => 'required|exists:tags,id']);
                $tag = \App\Models\Tag::find($request->tag_id);
                foreach ($records as $record) {
                    if (method_exists($record, 'tags')) {
                        $record->tags()->syncWithoutDetaching([$request->tag_id]);
                    }
                }
                return response()->json(['message' => 'Tag added to ' . count($ids) . ' records']);
                
            case 'remove_tag':
                $request->validate(['tag_id' => 'required|exists:tags,id']);
                foreach ($records as $record) {
                    if (method_exists($record, 'tags')) {
                        $record->tags()->detach($request->tag_id);
                    }
                }
                return response()->json(['message' => 'Tag removed from ' . count($ids) . ' records']);
                
            case 'export':
                // Export selected records
                return response()->json(['message' => 'Export functionality will be implemented']);
                
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }
    }
    
    private function getModel(string $resource)
    {
        $models = [
            'leads' => Lead::class,
            'contacts' => Contact::class,
            'accounts' => Account::class,
            'deals' => Deal::class,
            'tasks' => Task::class,
        ];
        
        return $models[$resource] ?? null;
    }
    
    private function getAssignField(string $resource): ?string
    {
        $fields = [
            'leads' => 'user_id',
            'contacts' => 'user_id',
            'accounts' => 'user_id',
            'deals' => 'user_id',
            'tasks' => 'assigned_to',
        ];
        
        return $fields[$resource] ?? null;
    }
}
