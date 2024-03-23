<?php
namespace Database\Seeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    public function run(){
        $users = $this->getUsers();
        foreach($users as $user){
            User::updateOrCreate(['email' => $user['email']],$user);
        }
    }
    private function getUsers(){
        return [
            ['role' => 'admin','first_name' => 'kimia','last_name' => 'Admin','email' => 'kimia@gmail.com','phone' => null,'password' => Hash::make("123456"),'created_at' => date("Y-m-d H:i:s"),'updated_at' => date("Y-m-d H:i:s")]
        ];
    }
}