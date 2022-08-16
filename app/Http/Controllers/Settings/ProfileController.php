<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Hash;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = $this->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'newsletter' => 'nullable',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);
        if ($user->provider != null) {
            return tap($user)->update($request->only('newsletter', 'firstname', 'lastname'));
        } else {
            if ($request->has('password') && $request->password != null) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return response()->json([
                        'errors' => ['current_password' => ['Invalid password']]
                    ], 422);  
                }

                return tap($user)->update($request->only('email', 'password', 'newsletter', 'firstname', 'lastname'));
            }
        }
    }
}
