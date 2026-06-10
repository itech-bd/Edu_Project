<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\BatchAssignmentsAndSchedulesSeeder;
use Database\Seeders\FrontendSettingsSeeder;
use Database\Seeders\MentorsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\SampleBatchesSeeder;
use Database\Seeders\SampleCoursesSeeder;
use Database\Seeders\SampleReviewsSeeder;
use Database\Seeders\StudentsSeeder;
use Database\Seeders\FrontendContentSeeder;

/**
 * Seeds the application with demo/initial data.
 *
 * @category Database
 * @package  Database\Seeders
 * @author   Unknown <unknown@example.invalid>
 * @license  https://opensource.org/licenses/MIT MIT
 * @link     https://laravel.com
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(MentorsSeeder::class);
        $this->call(StudentsSeeder::class);
        $this->call(SampleCoursesSeeder::class);
        $this->call(SampleReviewsSeeder::class);
        $this->call(SampleBatchesSeeder::class);
        $this->call(BatchAssignmentsAndSchedulesSeeder::class);
        $this->call(FrontendContentSeeder::class);
        $this->call(FrontendSettingsSeeder::class);

        User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => '12345678',
            ]
        );
    }
}
