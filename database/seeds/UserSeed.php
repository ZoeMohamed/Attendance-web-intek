<?php

use App\MProf;
use Illuminate\Database\Seeder;
use App\User;
class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
	User::create([
        'id' => 99999,
	    'email' => 'admin@admin.com',
	    'name' => 'Administrator',
	    'password' => bcrypt('adminidi'),
        'api_token' => 'sadqw212'
	]);

    User::create([
        'id'=>1,
	    'email' => 'user@user.com',
	    'name' => 'neina',
	    'password' => bcrypt('adminidi'),
        'api_token' => 'sadqw2112'
	]);


    User::create([
        'id'=>2,
	    'email' => 'zoe@zoe.com',
	    'name' => 'zoe',
	    'password' => bcrypt('adminidi'),
        'api_token' => 'sadqw2113'
	]);


    User::create([
        'id'=>3,
	    'email' => 'aldy@aldy.com',
	    'name' => 'aldy',
	    'password' => bcrypt('adminidi'),
        'api_token' => 'sadqw2114',
        'team_id' => 1
	]);


    }


}
