<?php

use App\Services\StaticDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AgeVerification;

Route::get('/', function () {
    return 'Welcome to LLD-2 Task';
}
);

Route::get('/welcome', function () {
    return 'Basic route for welcome...';
}
);

Route::get('/users/profile', function () {
    return response()->json(['message' => 'User profile page']);
})->name('profile');

Route::get('/redirect-profile', function () {
    return redirect()->route('profile');
});

Route::get('/profile-url', function () {
    return response()->json(['url' => route('profile')]);
});

Route::get('/users', function (StaticDataService $staticDataService) {
    $users = $staticDataService->getUsers();

    return response()->json($users);
}
);

Route::get('/users/{id}', function (StaticDataService $staticDataService, int $id) {
    $user = $staticDataService->getUserById($id);
    if ($user != null) {
        return response()->json($user);
    } else {
        return response()->json(['error' => 'User not found.'], 404);
    }
})->where('id', '[0-9]+');

Route::get('/users/{name?}', function (StaticDataService $staticDataService, ?string $name = null) {
    if ($name) {
        $user = $staticDataService->getUserByName($name);
        if ($user != null) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'name not found.'], 404);
        }
    } else {
        $users = $staticDataService->getUsers();

        return response()->json($users);
    }
})->where('name', '[A-Za-z\s]+');

Route::get('/users/{id}/{name}', function (StaticDataService $staticDataService, $id, $name) {
    $users = array_filter($staticDataService->getUsers(), function ($user) use ($id, $name) {
        return $user['id'] == $id && stripos($user['name'], $name) !== false;
    });

    if (empty($users)) {
        return response()->json(['error' => 'User not found'], 404);
    }

    return response()->json(array_values($users));
})->where(['id' => '[0-9]+', 'name' => '[A-Za-z\s]+']);

Route::post('/users', function (StaticDataService $staticDataService, Request $request) {

    $user = $staticDataService->addUser(
        [
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
        ]
    );

    return response()->json($user);
});

Route::put('/users/{id}', function (StaticDataService $staticDataService, $id, Request $request) {

    $user = $staticDataService->updateUser(
        (int) $id, [
            'name' => $request->name,
            'email' => $request->email,
            'age' => $request->age,
        ]
    );

    if ($user != null) {
        return response()->json($user);
    } else {
        respose()->json();
    }
});

Route::delete('/users/{id}', function (StaticDataService $staticDataService, $id) {
    $deletedFromDatabase = $staticDataService->deleteUser($id);

    if ($deletedFromDatabase) {
        return response()->json(['success' => 'deleted from database']);
    } else {
        return response()->json(['error' => 'user does not exist in database'], 404);
    }
});

Route::get('/posts', function (StaticDataService $staticDataService) {
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

Route::get('/posts/{post}/comments/{comment}', function (StaticDataService $staticDataService, $postId, $commentId) {
    $comment = $staticDataService->getCommentsByPostIdAndCommentId($postId, $commentId);

    if ($comment != null) {
        return response()->json($comment);
    } else {
        return response()->json(['error' => 'comment not found.'], 404);
    }
});

Route::prefix('admin')->group(function () {
    Route::get('/users', function (StaticDataService $staticDataService) {
        $users = $staticDataService->getUsers();

        return response()->json(['message' => 'admin page users', 'users' => $users]);
    }
    );
    Route::get('/posts', function (StaticDataService $staticDataService) {
        $posts = $staticDataService->getPosts();

        return response()->json(['message' => 'admin page posts', 'posts' => $posts]);
    });

});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return response()->json(['message' => 'Dashboard page']);
    });

    Route::get('/settings', function () {
        return response()->json(['message' => 'Settings page']);
    });
});

Route::prefix('api')->group(function () {

    Route::get('/users', function (StaticDataService $staticDataService) {
        $users = $staticDataService->getUsers();

        return response()->json($users);
    }
    );

    Route::get('/users/{id}', function (StaticDataService $staticDataService, int $id) {
        $user = $staticDataService->getUserById($id);
        if ($user != null) {
            return response()->json($user);
        } else {
            return response()->json(['error' => 'User not found.'], 404);
        }
    })->where('id', '[0-9]+');

    Route::get('/users/{name?}', function (StaticDataService $staticDataService, ?string $name = null) {
        if ($name) {
            $user = $staticDataService->getUserByName($name);
            if ($user != null) {
                return response()->json($user);
            } else {
                return response()->json(['error' => 'name not found.'], 404);
            }
        } else {
            $users = $staticDataService->getUsers();

            return response()->json($users);
        }
    })->where('name', '[A-Za-z\s]+');

    Route::get('/users/{id}/{name}', function (StaticDataService $staticDataService, $id, $name) {
        $users = array_filter($staticDataService->getUsers(), function ($user) use ($id, $name) {
            return $user['id'] == $id && stripos($user['name'], $name) !== false;
        });

        if (empty($users)) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json(array_values($users));
    })->where(['id' => '[0-9]+', 'name' => '[A-Za-z\s]+']);

    Route::post('/users', function (StaticDataService $staticDataService, Request $request) {

        $user = $staticDataService->addUser(
            [
                'name' => $request->name,
                'email' => $request->email,
                'age' => $request->age,
            ]
        );

        return response()->json($user);
    });

    Route::put('/users/{id}', function (StaticDataService $staticDataService, $id, Request $request) {

        $user = $staticDataService->updateUser(
            (int) $id, [
                'name' => $request->name,
                'email' => $request->email,
                'age' => $request->age,
            ]
        );

        if ($user != null) {
            return response()->json($user);
        } else {
            respose()->json();
        }
    });

    Route::delete('/users/{id}', function (StaticDataService $staticDataService, $id) {
        $deletedFromDatabase = $staticDataService->deleteUser($id);

        if ($deletedFromDatabase) {
            return response()->json(['success' => 'deleted from database']);
        } else {
            return response()->json(['error' => 'user does not exist in database'], 404);
        }
    });

});

Route::get('/adult-content', function () {
    return response()->json(['message' => 'Welcome to adult content area. Age verified.']);

})->middleware([AgeVerification::class]);
