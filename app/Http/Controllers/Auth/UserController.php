<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\JoinAgent;
use App\Mail\ContactUs;
use Mail;
use Auth;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Get authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function current(Request $request)
    {   
        $user = Auth::user();
        return response()->json($user);
    }

    public function index()
    {
        return User::get();
    }

    public function contactUs(Request $request)
    {
        $to = $request->to;

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'message' => 'required',
            'subject' => 'required',
        ]);

        $details = [
            'title' => $request->subject,
            'url'   => 'https://www.anshell.com',
            'data'  => $request->all()
        ];
  
        Mail::to($to)->send(new ContactUs($details, 'anshell@anshell.com'));
   
        return response()->json(['message' => 'Message sent successfully.']);
    }

    public function addUser(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'role' => 'required',
            'email' => 'required|email:filter|max:255|unique:users',
            'password' => 'required|confirmed',
        ]);

        $inputs = $request->all();
        $inputs['name'] = $request->firstname . ' ' . $request->lastname;
        
        if (Auth::user()->role == 'admin') {
            if ($request->role == 'super admin') {
                $inputs['role'] = 'admin';
            }
        }

        if (Auth::user()->role != 'user') {
            $user = User::create($inputs);

            return response()->json(['message' => 'User created.']);
        }
    }

    public function changePassword(Request $request, $id)
    {
        $this->validate($request, [
            'password' => 'required|confirmed'
        ]);

        if (Auth::user()->role != 'user') {
            $user = User::find($id);
            $user->password = $request->password;

            if ($user->save()) {
                return response()->json(['message' => 'Password changed successfully.']);
            }
        }
    }

    public function deleteUser($id)
    {
        if (Auth::user()->role != 'user') {
            $user = User::find($id);

            if ($user->delete()) {
                return response()->json(['message' => 'User deleted.']);
            }
        }
    }

    public function joinAgent(Request $request)
    {
        $to = 'anshell@anshell.com';

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'license_number' => 'required',
            'license_rank' => 'required',
        ]);

        $details = [
            'title' => 'Agent Application',
            'url'   => 'https://www.anshell.com',
            'data'  => $request->all()
        ];

        Mail::to($to)->send(new JoinAgent($details, 'anshell@anshell.com'));
    
        return response()->json(['message' => 'Message sent successfully.']);
    }

    public function changeRole(Request $request)
    {
        $this->validate($request, [
            'role' => 'required'
        ]);

        if (Auth::user()->role != 'user') {
            $user = User::find($request->id);
            $user->role = $request->role;

            if ($user->save()) {
                return response()->json(['message' => 'Role has been updated.']);
            }
        }
    }

    public function updateUser(Request $request)
    {
        $user = User::find($request->id);

        $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'role' => 'required',
            'email' => 'required|unique:users,email,'.$user->id,
            'image' => 'nullable|mimes:jpeg,jpg,png,gif|max:2048'
        ]);

        $file = null;

        if (Auth::user()->role == 'admin') {
            if ($request->role == 'super admin') {
                $request->role = 'admin';
            }
        }

        if (Auth::user()->role != 'user') {

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $file = $image->store('images/users', 'public');
                $user->image = $file;
                $user->save();
            }

            $user->firstname = $request->firstname;
            $user->lastname = $request->lastname;
            $user->name = $request->firstname . ' ' . $request->lastname;
            $user->role = $request->role;
            $user->email = $request->email;
            
            if ($user->save()) {
                return response()->json(['message' => 'User updated.']);
            }

        }
    }
}
