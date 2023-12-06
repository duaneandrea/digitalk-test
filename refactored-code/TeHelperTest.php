<?php

use Carbon\Carbon;
use DTApi\Helpers\TeHelper;
use DTApi\Models\Job;
use DTApi\Models\Language;
use DTApi\Models\UserMeta;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeHelperTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test fetching language from job ID.
     *
     * @return void
     */
    public function testFetchLanguageFromJobId()
    {
        // Create a language
        $language = Language::factory()->create(['language' => 'English']);

        // Call the fetchLanguageFromJobId method
        $fetchedLanguage = TeHelper::fetchLanguageFromJobId($language->id);

        // Assert that the fetched language matches the created language
        $this->assertEquals('English', $fetchedLanguage);
    }

    /**
     * Test getting user meta data.
     *
     * @return void
     */
    public function testGetUsermeta()
    {
        // Create a user with meta data
        $user = UserMeta::factory()->create(['user_id' => 1, 'key' => 'name', 'value' => 'John Doe']);

        // Call the getUsermeta method
        $userMeta = TeHelper::getUsermeta(1, 'name');

        // Assert that the fetched user meta matches the created user meta value
        $this->assertEquals('John Doe', $userMeta);
    }

    /**
     * Test converting job IDs into job objects.
     *
     * @return void
     */
    public function testConvertJobIdsInObjs()
    {
        // Create two jobs
        $job1 = Job::factory()->create();
        $job2 = Job::factory()->create();

        // Create an array of job IDs
        $jobIds = [$job1->id, $job2->id];

        // Call the convertJobIdsInObjs method
        $jobs = TeHelper::convertJobIdsInObjs($jobIds);

        // Assert that the number of jobs fetched matches the number of job IDs provided
        $this->assertCount(2, $jobs);
        // Assert that the fetched jobs are instances of Job model
        $this->assertInstanceOf(Job::class, $jobs[0]);
        $this->assertInstanceOf(Job::class, $jobs[1]);
    }

    /**
     * Test calculating the expiration time based on due time and created time.
     *
     * @return void
     */
    public function testWillExpireAt()
    {
        // Set the due time to 2 hours from now
        $dueTime = Carbon::now()->addHours(2);
        // Set the created time to 1 hour ago
        $createdAt = Carbon::now()->subHours(1);

        // Call the willExpireAt method
        $expirationTime = TeHelper::willExpireAt($dueTime, $createdAt);

        // Assert that the calculated expiration time matches the expected time (90 minutes from created time)
        $this->assertEquals($createdAt->addMinutes(90)->format('Y-m-d H:i:s'), $expirationTime);
    }
}