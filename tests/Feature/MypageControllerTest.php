<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleWare;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MypageControllerTest extends TestCase
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
      // $this->targetComment = $this->targetSpot->comments[0];
    }

    public function testIndex()
    {
      // マイページアクセステスト
      $response = $this->get('/mypage/' . $this->targetPlan['id']);
      $response->assertStatus(200);
    }

    public function test_index_nextMyPlan()
    {
      $request = [
        'search_key' => $this->targetUser,
        'page' => 1,
      ];

      $response = $this->actingAs($this->targetUser)
                        ->call('POST', action('Main\MypageController@index_nextMyPlan'), $request);

      $response->assertJsonStructure([
        'postuser',
        'response',
        'response_length',
      ]);
    }

    public function test_index_nextFavPlan()
    {
      $request = [
        'search_key' => $this->targetUser,
        'page' => 1,
      ];

      $response = $this->actingAs($this->targetUser)
                        ->call('POST', action('Main\MypageController@index_nextFavPlan'), $request);

      $response->assertJsonStructure([
        'postuser',
        'response',
        'response_length',
      ]);
    }

    public function test_index_nextFavSpot()
    {
      $request = [
        'search_key' => $this->targetUser,
        'page' => 1,
      ];

      $response = $this->actingAs($this->targetUser)
                        ->call('POST', action('Main\MypageController@index_nextFavSpot'), $request);

      $response->assertJsonStructure([
        'postuser',
        'response',
        'response_length',
      ]);
    }

}
