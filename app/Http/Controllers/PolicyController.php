<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
class PolicyController extends Controller
{
    public function index()
    {
        $policies = Policy::select('policies.*', 'users.*', 'user_roles.*', 'roles.*') 
            ->join('users', 'policies.user_id', '=', 'users.id') 
            ->join('user_roles', 'user_roles.user_id', '=', 'users.id') 
            ->join('roles', 'user_roles.role_id', '=', 'roles.id') 
            ->paginate(2); 
    
        return response()->json($policies);
    }
    
    public function store(Request $request)
    {
        try {
            $validated =$request->validate([
                'user_id' => 'required|exists:users,id',
                'policy_number' => 'required|string|unique:policies',
                'type' => 'required|string',
                'premium_amount' => 'required|numeric',
                'coverage_details' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'status' => 'required|in:active,expired,canceled',
            ]);
    
            $policy = Policy::create([
                'user_id' => $validated['user_id'],
                'policy_number' => $validated['policy_number'],
                'type' => $validated['type'],
                'premium_amount' => $validated['premium_amount'],
                'coverage_details' => $validated['coverage_details'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => $validated['status'],

            ]);
    
         
            return response()->json([
                'message' => 'Policy added successfully',
                'policy' => $policy 
            ], 201);
            
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $policy = Policy::findOrFail($id);
    
            $validated = $request->validate([
                'policy_number' => 'string|unique:policies,policy_number,' . $policy->id,
                'type' => 'string',
                'premium_amount' => 'numeric',
                'coverage_details' => 'string',
                'start_date' => 'date',
                'end_date' => 'date|after:start_date',
                'status' => 'in:active,expired,canceled',
            ]);
    
            $policy->update($validated);
    
            return response()->json([
                'message' => 'Policy updated successfully',
                'policy' => $policy
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Policy not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    public function destroy($id)
    {
        try {
            $policy = Policy::findOrFail($id);
            $policy->delete();    
            return response()->json([
                'message' => 'Policy deleted successfully'
            ], 200);
    
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Policy not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
}

