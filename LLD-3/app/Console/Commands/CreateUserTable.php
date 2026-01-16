<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateUserTable extends Command
{
    protected $signature = 'table:create-user';
    protected $description = 'Create users table programmatically';

    public function handle()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
            
            $this->info('Users table created successfully!');
        } else {
            $this->warn('Users table already exists!');
        }
    }
}