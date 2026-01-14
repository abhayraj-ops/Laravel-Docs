<?php

namespace app\Services;

class StaticDataService
{
    private array $users = [
        ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'age' => 25],
        ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'age' => 30],
        ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'age' => 35],
    ];

    private array $posts = [
        ['id' => 1, 'title' => 'First Post', 'content' => 'Content of first post'],
        ['id' => 2, 'title' => 'Second Post', 'content' => 'Content of second post'],
    ];

    private static array $comments = [
        ['id' => 1, 'post_id' => 1, 'content' => 'Great post!', 'author' => 'Alice'],
        ['id' => 2, 'post_id' => 1, 'content' => 'Thanks for sharing', 'author' => 'Bob'],
        ['id' => 3, 'post_id' => 2, 'content' => 'Very informative', 'author' => 'Charlie'],
        ['id' => 4, 'post_id' => 3, 'content' => 'Looking forward to more', 'author' => 'Diana']
    ];

    public function getUsers()
    {

        return $this->users;

    }

    public function getUserById($id)
    {
        $users = $this->getUsers();

        foreach ($users as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }

        return null;
    }
    public function addUser(array $userData): array
    {
        $id = max(array_column($this->users, 'id'), 0) + 1;
        $user = array_merge(['id' => $id], $userData);
        $this->users[] = $user;
        return $user;
    }

    public function updateUser(int $id, array $userData): ?array
    {
        foreach (self::$users as &$user) {
            if ($user['id'] == $id) {
                $user = array_merge($user, $userData);
                return $user;
            }
        }
        return null;
    }


    public function getPosts()
    {
        return $this->posts;
    }

    public function getPostById($id)
    {
        $posts = $this->getPosts();

        foreach ($posts as $post) {
            if ($post['id'] == $id) {
                return $post;
            }
        }

        return null;

    }


    public function deletePost(int $id): bool
    {
        // Also delete associated comments
        self::$comments = array_filter(self::$comments, function ($comment) use ($id) {
            return $comment['post_id'] != $id;
        });
        
        $initialCount = count(self::$posts);
        self::$posts = array_filter(self::$posts, function ($post) use ($id) {
            return $post['id'] != $id;
        });
        self::$posts = array_values(self::$posts); // Re-index array
        return $initialCount !== count(self::$posts);
    }

    public function getComments(): array
    {
        return self::$comments;
    }

    public function getCommentById($id): ?array
    {
        foreach (self::$comments as $comment) {
            if ($comment['id'] == $id) {
                return $comment;
            }
        }
        return null;
    }

    public function getCommentsByPostId($postId): array
    {
        return array_filter(self::$comments, function ($comment) use ($postId) {
            return $comment['post_id'] == $postId;
        });
    }

    public function addComment(array $commentData): array
    {
        $id = max(array_column(self::$comments, 'id'), 0) + 1;
        $comment = array_merge(['id' => $id], $commentData);
        self::$comments[] = $comment;
        return $comment;
    }

    public function updateComment(int $id, array $commentData): ?array
    {
        foreach (self::$comments as &$comment) {
            if ($comment['id'] == $id) {
                $comment = array_merge($comment, $commentData);
                return $comment;
            }
        }
        return null;
    }

    public function deleteComment(int $id): bool
    {
        $initialCount = count(self::$comments);
        self::$comments = array_filter(self::$comments, function ($comment) use ($id) {
            return $comment['id'] != $id;
        });
        self::$comments = array_values(self::$comments); // Re-index array
        return $initialCount !== count(self::$comments);
    }
}
