<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a paginated list of users.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve users in descending order by ID and paginate the results
        $users = User::orderBy('id', 'desc')->paginate(10);
        
        // Pass the users to the 'users.dashboard' view
        return view('users.dashboard', [
            'users' => $users
        ]);
    }

    /**
     * Display the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        
        return view('users.create');
    }

    /**
     * Store a newly created user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation rules for incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        // If validation fails, return JSON response with errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // Create a new User 
        $user = new User();
        $user->name = $request->name;
        $user->email = strtolower($request->email);
        $user->phone_number = $request->phone_number;
        $user->password = bcrypt($request->password);

        
        $user->save();

        // Redirect to the 'admin.index' route with success message
        return redirect()->route('admin.user.index')->with('success', 'User was created successfully');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::find($id);

        // Pass the user to the 'users.edit' view
        return view('users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified user in the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validation rules for updating user data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:15'
        ]);

        // If validation fails, return JSON response with errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        
        $user = User::find($id);

       
        $user->name = $request->name;

        
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }

        $user->phone_number = $request->phone;
        $user->email = strtolower($request->email);

        
        $user->save();

        // Redirect to the 'admin.index' route with success message
        return redirect()->route('admin.user.index')->with('success', 'User was updated successfully');
    }

    /**
     * Remove the specified user from the database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        
        $user = User::find($id);

        
        $user->delete();

        // Redirect to the 'admin.index' route with success message
        return redirect()->route('admin.user.index')->with('success', 'User was deleted successfully');
    }
}