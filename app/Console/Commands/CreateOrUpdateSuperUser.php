<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\User;

class CreateOrUpdateSuperUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:createorupdate {username} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or Update super user with username vbh';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = $this->argument('username');
        $password = $this->argument('password');

        $user = User::where('name', $username)->first();
        if($user && $password) {
            $user->password = bcrypt($password);
            $user->save();

            $this->info('User updated!');
            return;
        }
        if($username && $password) {
            User::create([
                'name' => $username,
                'password' => bcrypt($password),
                'email' => $username.'@email.com',
            ]);

            $this->info('New user created!');
        } else {
            $this->error('Invalid arguments');
        }
    }
}
