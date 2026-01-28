<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class FixUserPasswords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:fix-passwords {--password=password : The password to set for all users}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix incorrectly hashed user passwords by resetting them to a plain password. The model will automatically hash them correctly.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $password = $this->option('password');
        
        $this->info('Fixing user passwords...');
        
        $users = User::all();
        $count = 0;
        
        foreach ($users as $user) {
            // Set plain password - model's 'hashed' cast will automatically hash it
            $user->password = $password;
            $user->save();
            $count++;
            $this->line("Fixed password for user: {$user->email}");
        }
        
        $this->info("Successfully fixed passwords for {$count} user(s).");
        $this->warn("All users now have the password: '{$password}'");
        $this->warn("Please change passwords after logging in!");
        
        return Command::SUCCESS;
    }
}
