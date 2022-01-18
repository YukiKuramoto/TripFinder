<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleWare;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexTest extends TestCase
{
    use DatabaseMigrations;
    private $testData;

    // テストデータ作成
    public function setUp(): void
    {
      parent::setUp();
      $this->testData = $this->createBasePostTestData();
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGuestUserAccess()
    {
      // テスト対象取得
      $targetUser = $this->testData['user'];
      $targetPlan = $targetUser->plans[0];
      $targetSpot = $targetPlan->spots[0];

      // ************** ゲストユーザーアクセス確認 ******************

      // ログインページアクセステスト
      $response = $this->get('/login');
      $response->assertStatus(200);

      // ユーザー登録ページアクセステスト
      $response = $this->get('/register');
      $response->assertStatus(200);

      // ホームページアクセステスト
      $response = $this->get('/home');
      $response->assertStatus(200);

      // 検索ページアクセステスト
      $response = $this->get('/search');
      $response->assertStatus(200);


      // マイページアクセステスト
      $response = $this->get('/mypage/' . $targetPlan['id']);
      $response->assertStatus(200);

      // プラン表示ページアクセステスト
      $response = $this->get('/index/' . $targetPlan['id']);
      $response->assertStatus(200);

      // スポット表示ページアクセステスト
      $response = $this->get('/index/spot/' . $targetSpot['id']);
      $response->assertStatus(200);

      // コメント一覧ページアクセステスト
      $response = $this->get('comment/show/?plan_id=' . $targetPlan['id'] . '&spot_id=' . $targetSpot['id']);
      $response->assertStatus(200);


      // ************** ゲストユーザーアクセス不可確認（認証必要ページ） ******************

      // 投稿用ページアクセス不可確認
      $response = $this->get('post');
      $response->assertStatus(302);

      // ユーザー一覧ページアクセス不可確認
      $response = $this->get('/users/index');
      $response->assertStatus(302);

      // プロフィール編集画面アクセス確認
      $response = $this->get('/profile/edit');
      $response->assertStatus(302);

      // コメント投稿ページアクセステスト
      $response = $this->get('/comment/create?spotId=' . $targetSpot['id']);
      $response->assertStatus(302);

    }


    public function testAuthUserAccess()
    {
      $targetUser = $this->testData['user'];
      $targetPlan = $targetUser->plans[0];
      $targetSpot = $targetPlan->spots[0];
      // ************** 認証ユーザーアクセス確認 ******************

      // 投稿用ページアクセス確認
      $response = $this->actingAs($targetUser)->get('/post');
      $response->assertStatus(200);

      // プロフィール編集画面アクセス確認
      $response = $this->actingAs($targetUser)->get('/profile/edit');
      $response->assertStatus(200);

      // コメント投稿ページアクセステスト
      $response = $this->actingAs($targetUser)->get('/comment/create?spot_id=' . $targetSpot['id']);
      $response->assertStatus(200);

    }

}
