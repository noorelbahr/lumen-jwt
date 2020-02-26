<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use App\Http\Resources\User as UserResource;
use App\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get all users
     * - - -
     * @return UserCollection
     */
    public function index()
    {
        $users = User::all();

        return new UserCollection($users);
    }

    /**
     * Get user detail
     * - - -
     * @param $id
     * @return UserResource|JsonResponse
     */
    public function show($id)
    {
        try {
            $user = User::find($id);
            if (!$user)
                throw new Exception('User not found.', 400);

            return new UserResource($user);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Create a user
     * - - -
     * @param Request $request
     * @return UserResource|JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users',
                'password' => 'required|min:6|confirmed',
                'fullname' => 'required|string|max:70',
                'picture' => 'required|image|max:2048'
            ]);

            if ($validator->fails())
                throw new Exception($validator->errors()->first(), 422);

            // Set user data
            $user = new User();
            $user->username = $request->input('username');
            $user->password = Hash::make($request->input('password'));
            $user->fullname = $request->input('fullname');

            // Set profile picture if exists
            if ($request->hasFile('picture')) {
                $uploadedFile = $request->file('picture');
                $filename = time() . $uploadedFile->getClientOriginalName();

                Storage::disk('public')->putFileAs('profile', $uploadedFile, $filename);

                // Set filename to user picture
                $user->picture = $filename;
            }

            // Save user
            if (!$user->save())
                throw new Exception('Failed to save user data.', 500);

            return new UserResource($user);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Update a user data
     * - - -
     * @param Request $request
     * @param $id
     * @return UserResource|JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Validate request
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users,username,' . $id,
                'password' => 'nullable|min:6|confirmed',
                'fullname' => 'required|string|max:70',
                'picture' => 'nullable|image|max:2048'
            ]);

            if ($validator->fails())
                throw new Exception($validator->errors()->first(), 422);

            // Check user data
            $user = User::find($id);
            if (!$user)
                throw new Exception('User not found.', 400);

            // Set user data
            $user->username = $request->input('username');
            $user->fullname = $request->input('fullname');

            // Set user password if exists
            if ($request->has('password'))
                $user->password = Hash::make($request->input('password'));

            // Set profile picture if exists
            if ($request->hasFile('picture')) {
                $uploadedFile = $request->file('picture');
                $filename = time() . $uploadedFile->getClientOriginalName();

                Storage::disk('public')->putFileAs('profile', $uploadedFile, $filename);

                // Set filename to user picture
                $user->picture = $filename;
            }

            // Save user
            if (!$user->save())
                throw new Exception('Failed to save user data.', 500);

            return new UserResource($user);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a user
     * - - -
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Check user data
            $user = User::find($id);
            if (!$user)
                throw new Exception('User not found.', 400);

            if (!$user->delete())
                throw new Exception('Failed to remove user data.', 500);

            return $this->success('User data has been removed successfully.', 200, true);

        } catch (Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
