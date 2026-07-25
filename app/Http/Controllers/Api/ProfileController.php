<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function profile()
    {
        return apiResponse(UserResource::make(Auth::user()));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validator = validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg|max:4096',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = $validator->validated();

        if ($user->name !== $request->name) {
            $data['username'] = str_slug('users', 'username', $request->name);
        } else {
            $data['username'] = $user->username;
        }

        $data['phone'] = $request->phone;

        if ($request->hasFile('image')) {
            if (! empty($user->image)) {
                delete_file($user->image);
            }

            $filePath = 'images/user/avatar';
            $data['image'] = upload_file($request->file('image'), $filePath);
        } else {
            $data['image'] = $user->image;
        }

        $user->update($data);

        return successResponse('Profile Update Successfully');
    }
}
