<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory as Faker;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(["name" => "control publisher"]);
        Permission::create(["name" => "control author"]);
        Permission::create(["name" => "control catalog"]);
        Permission::create(["name" => "control book"]);
        Permission::create(["name" => "control member"]);
        Permission::create(["name" => "control lend"]);
        Permission::create(["name" => "control staff"]);

        // create roles and assign existing permissions
        $book_guardian = Role::create(["name" => "book guardian"]);
        $book_guardian->givePermissionTo("control publisher");
        $book_guardian->givePermissionTo("control author");
        $book_guardian->givePermissionTo("control catalog");
        $book_guardian->givePermissionTo("control book");

        $member_service = Role::create(["name" => "member service"]);
        $member_service->givePermissionTo("control member");

        $library_staff = Role::create(["name" => "library staff"]);
        $library_staff->givePermissionTo("control lend");

        $head_library = Role::create(["name" => "head library"]);
        $head_library->givePermissionTo("control publisher");
        $head_library->givePermissionTo("control author");
        $head_library->givePermissionTo("control catalog");
        $head_library->givePermissionTo("control book");
        $head_library->givePermissionTo("control member");
        $head_library->givePermissionTo("control lend");

        //create staff and give existing roles
        $user = User::factory()->create([
            "name" => "Andi Wahyu",
            "email" => "a.wahyukhusnulmalik@gmail.com",
            "password" => Hash::make("1234567890")
        ]);
        $user->assignRole($head_library);

        $user = User::factory()->create([
            "name" => Faker::create()->name,
            "email" => "book@library.com",
            "password" => Hash::make("1234567890")
        ]);
        $user->assignRole($book_guardian);

        $user = User::factory()->create([
            "name" => Faker::create()->name,
            "email" => "member@library.com",
            "password" => Hash::make("1234567890")
        ]);
        $user->assignRole($member_service);

        $user = User::factory()->create([
            "name" => Faker::create()->name,
            "email" => "staff@library.com",
            "password" => Hash::make("1234567890")
        ]);
        $user->assignRole($library_staff);
    }
}
