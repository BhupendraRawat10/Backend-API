<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function usersdetails()
    {
        return User::with('roles')->paginate(5);
    }
    
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|unique:users,email',
                'password' => 'required|string|min:6',
                'role' => 'required|exists:roles,id', 
            ]);
    
         
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
    
     
            $user->roles()->attach($request->role);  
    
            return response()->json([
                'message' => 'User registered successfully',
                'token' => $user->createToken('API Token')->plainTextToken,
                'user' => $user
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
    
    
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'The provided email is not registered.',
                'errors' => ['email' => ['The provided email is not registered.']],
            ], 422);
        }
    
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'The provided password is incorrect.',
                'errors' => ['password' => ['The provided password is incorrect.']],
            ], 422);
        }
    
        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'token' => $user->createToken('API Token')->plainTextToken,
            'user' => $user,
        ], 200);
    }

        public function logout(Request $request)
        {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Logged out']);
        }

        public function update(Request $request, $id)
{
    try {
        $user = User::findOrFail($id);

   
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|exists:roles,id', 
        ]);

     
        $user->update($validated);

       
        $user->roles()->detach();

      
        $user->roles()->attach($request->role);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user 
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'error' => 'User not found'
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
        $user = User::findOrFail($id);

       
        $user->delete();

        return response()->json([
            'message' => 'User and associated data deleted successfully'
        ], 200);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'error' => 'User not found'
        ], 404);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Something went wrong',
            'message' => $e->getMessage()
        ], 500);
    }
}

        
    }
