<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            'data' => $users
        ]);
    }

    public function create(StoreUserRequest $request)
    {
        $validateData = $request->validated();

        $validateData['password'] = bcrypt($validateData['password']);

        $user = User::create($validateData);

        return response()->json([
            'message' => 'User Successfully Created!',
            'data' => $user
        ]);
    }


    public function store()
    {


    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::find($id);

        $validateData = $request->validated();

        $validateData['password'] = bcrypt($validateData['password']);

        $user->name = $validateData['name'];
        $user->email = $validateData['email'];
        $user->password = $validateData['password'];

        $user->save();

        return response()->json([
            'message' => 'User Updated Successfully',
            'data' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        User::destroy($id);
        return response()->json([
            'message' => 'User Deleted Successfully'
        ]);

    }
}
