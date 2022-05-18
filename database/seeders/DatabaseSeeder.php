<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseType;
use App\Models\DetailCourse;
use App\Models\DetailUser;
use App\Models\Diary;
use App\Models\DiaryType;
use App\Models\Education;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // DetailUser::factory(10)->create();
        // Diary::factory(10)->create();
        // Education::factory(10)->create();
        // Course::factory(10)->create();
        // DetailCourse::factory(100)->create();
        // $this->call([RoleSeeder::class]);
        // CourseType::factory(2)->create();
        // DiaryType::factory(4)->create();
    }
}
