<?php

namespace Tests\Unit;

// use App\User;
// use App\Follow;
// use App\Plan;
// use App\Spot;
// use App\Tag;
// use App\PlanTag;
// use App\SpotTag;
// use App\FavPlan;
// use App\FavSpot;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleWare;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OneToManyTest extends TestCase
{
  use DatabaseMigrations;
  private $testData;
  // const planCount = 2;
  // const spotCount = 3;
  // const tagCount = 3;
  // const imageCount = 2;
  // const linkCount = 2;
  // const commentCount = 2;

  // テストデータ作成
  public function setUp(): void
  {
    parent::setUp();
    $this->testData = $this->createBasePostTestData();
    // $count_array = [
    //   'plan_count' => self::planCount,
    //   'spot_count' => self::spotCount,
    //   'tag_count' => self::tagCount,
    //   'image_count' => self::imageCount,
    //   'link_count' => self::linkCount,
    //   'comment_count' => self::commentCount,
    // ];
  }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testUserPlanRelations()
    {
      $targetPlan = $this->testData['user']->plans[0];
      $fakePlan = $this->testData['plan'];

      $this->assertEquals($targetPlan['plan_title'], $fakePlan['plan_title']);
    }

    public function testPlanSpotRelations()
    {
      $targetSpot = $this->testData['user']->plans[0]->spots[0];
      $fakeSpot = $this->testData['spot'];

      $this->assertEquals($targetSpot['spot_title'], $fakeSpot['spot_title']);
    }

    public function testSpotImageRelations()
    {
      $targetImage = $this->testData['user']->plans[0]->spots[0]->images[0];
      $fakeImage = $this->testData['image'];

      $this->assertEquals($targetImage['image_path'], $fakeImage['image_path']);
    }

    public function testSpotLinkRelations()
    {
      $targetLink = $this->testData['user']->plans[0]->spots[0]->links[0];
      $fakeLink = $this->testData['link'];

      $this->assertEquals($targetLink['link_title'], $fakeLink['link_title']);
    }

    public function testSpotCommentRelations()
    {
      $targetComment = $this->testData['user']->plans[0]->spots[0]->comments[0];
      $fakeComment = $this->testData['comment'];

      $this->assertEquals($targetComment['comment_title'], $fakeComment['comment_title']);
    }

}
