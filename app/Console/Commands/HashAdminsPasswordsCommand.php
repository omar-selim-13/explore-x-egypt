<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash; // Import Hash facade

class HashAdminsPasswordsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admins:hash-passwords';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hash existing passwords for Admin users';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
{
    $this->info('Hashing admin passwords...');

    Admin::all()->each(function ($admin) {
        if (!Hash::needsRehash($admin->password)) {
            $admin->password = Hash::make($admin->password);
            $admin->save();
        }
    });

    $this->info('Admin passwords hashed successfully!');
    return 0;
}
}
