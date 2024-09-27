<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateApiTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token:create {userId} {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new API token for a user';


    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Retrieve user by ID
        $user = User::find($this->argument('userId'));

        if (!$user) {
            $this->error('User not found.');
            return;
        }

        // Create token
        $token = $user->createToken($this->argument('name'));

        $this->info('Token created: ' . $token->plainTextToken);
    }
}
