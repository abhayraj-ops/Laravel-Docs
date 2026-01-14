<?php

use App\Services\StaticDataService;
use App\Contracts\StaticDataServiceContract;

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return 'Welcome to LLD-2 Task';
}
);

Route::get('/welcome', function () {
    return 'Basic route for welcome...';
}
);

Route::get('/users', function (StaticDataService $staticDataService) {
    $users = $staticDataService->getUsers();

    return response()->json($users);
}

);

Route::get('/users/{id}', function (StaticDataService $staticDataService, $id) {
    $user = $staticDataService->getUserById($id);
    if ($user != null) {
        return response()->json($user);
    } else {
        return response()->json(['error' => 'User not found.'], 404);
    }
});

Route::post('/users', function (StaticDataService $staticDataService, Request $request) {
    // In a real scenario with static data, we'd return new user data
    return response()->json([
        'id' => count($staticDataService->getUsers()) + 1,
        'name' => $request->name,
        'email' => $request->email
    ]);
});
Route::get('/posts', function (StaticDataService $staticDataService)
{
    $posts = $staticDataService->getPosts();

    return response()->json($posts);
});

Route::get('/posts/{id}', function (StaticDataService $staticDataService, $id) {
    $post = $staticDataService->getPostById($id);
    if ($post != null) {
        return response()->json($post);
    } else {
        return response()->json(['error' => 'post not found.'], 404);
    }
});
