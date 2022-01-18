<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleWare;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CommentControllerTest extends TestCase
{
  use DatabaseMigrations;
  private $targetUser;
  private $targetPlan;
  private $targetSpot;

  // テストデータ作成
  public function setUp(): void
  {
    parent::setUp();
    $testData = $this->createBasePostTestData();
    // テスト対象取得
    $this->targetUser = $testData['user'];
    $this->targetPlan = $this->targetUser->plans[0];
    $this->targetSpot = $this->targetPlan->spots[0];
    $this->targetComment = $this->targetSpot->comments[0];
  }

  /**
   * A basic feature test example.
   *
   * @return void
   */
  public function testIndex()
  {
    $request = ['spot_id' => $this->targetSpot['id']];
    $response = $this->actingAs($this->targetUser)
                      ->call('GET', action('Main\CommentController@index'), $request);

    $response->assertStatus(200);
  }


  public function testShow()
  {
    $request = [
      'spot_id' => $this->targetSpot['id'],
      'plan_id' => $this->targetPlan['id'],
    ];

    $response = $this->actingAs($this->targetUser)
                      ->call('GET', action('Main\CommentController@show'), $request);

    $response->assertStatus(200);
  }

  public function testCreate()
  {
    $request = [
      'spot_id' => $this->targetSpot['id'],
      'plan_id' => $this->targetPlan['id'],
      'user_id' => $this->targetUser['id'],
      'comment_content' => 'test',
      'comment_title' => 'test',
    ];

    $response = $this->actingAs($this->targetUser)
                      ->call('POST', action('Main\CommentController@create'), $request);

    $response->assertStatus(200);
  }

  public function testDelete()
  {
    $request = [
      'plan_id' => $this->targetPlan['id'],
      'comment_id' => $this->targetComment['id'],
    ];

    $response = $this->actingAs($this->targetUser)
                      ->call('GET', action('Main\CommentController@delete'), $request);

    $response->assertStatus(302);

  }
}
