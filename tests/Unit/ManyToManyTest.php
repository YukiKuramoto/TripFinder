<?php

namespace Tests\Unit;

use App\User;
use App\Follow;
use App\Plan;
use App\Spot;
use App\Tag;
use App\PlanTag;
use App\SpotTag;
use App\FavPlan;
use App\FavSpot;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleWare;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ManyToManyTest extends TestCase
{
  use DatabaseMigrations;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testUserRelations()
    {
      $followedUser = factory(User::class)->create();
      $followerUser = factory(User::class)->create();

      $followData = factory(Follow::class)->create([
        'followed_user_id' => $followedUser['id'],
        'follower_user_id' => $followerUser['id']
      ]);

      $followedUser->refresh();
      $followerUser->refresh();

      // リレーションを用いて取得したフォロワーのIDが、作成したダミーユーザーIDと一致しているか確認
      $this->assertEquals($followedUser->followers[0]['id'], $followerUser['id']);
      // リレーションを用いて取得したフォローしているユーザーのIDが、作成したダミーユーザーIDと一致しているか確認
      $this->assertEquals($followerUser->follows[0]['id'], $followedUser['id']);

    }

    public function testFavRelations()
    {
      $user = factory(User::class)->create();
      $plan = factory(Plan::class)->create(['user_id' => $user['id']]);

      $FavData = factory(FavPlan::class)->create([
        'user_id' => $user['id'],
        'plan_id' => $plan['id'],
      ]);

      $user->refresh();

      // リレーションを用いて取得したプランのIDが、作成したダミープランのIDと一致しているか確認
      $this->assertEquals($user->favPlans[0]['id'], $plan['id']);

    }

    public function testSpotFavRelations()
    {
      $user = factory(User::class)->create();
      $plan = factory(Plan::class)->create(['user_id' => $user['id']]);
      $spot = factory(Spot::class)->create(['user_id' => $user['id'], 'plan_id' => $plan['id']]);

      $FavData = factory(FavSpot::class)->create([
        'user_id' => $user['id'],
        'spot_id' => $spot['id'],
      ]);

      $user->refresh();

      // リレーションを用いて取得したスポットのIDが、作成したダミースポットのIDと一致しているか確認
      $this->assertEquals($user->favSpots[0]['id'], $spot['id']);

    }


    public function testTagRelations()
    {
      $user = factory(User::class)->create();
      $plan = factory(Plan::class)->create(['user_id' => $user['id']]);
      $spot = factory(Spot::class)->create(['user_id' => $user['id'], 'plan_id' => $plan['id']]);
      $tag = factory(Tag::class)->create();

      $SpotTag = factory(SpotTag::class)->create([
        'spot_id' => $spot['id'],
        'tag_id' => $tag['id'],
      ]);

      $PlanTag = factory(PlanTag::class)->create([
        'plan_id' => $plan['id'],
        'tag_id' => $tag['id']
      ]);

      $plan->refresh();
      $spot->refresh();

      // リレーションを用いて取得したスポットのIDが、作成したダミースポットのIDと一致しているか確認
      $this->assertEquals($plan->tags[0]['name'], $tag['name']);
      $this->assertEquals($spot->tags[0]['name'], $tag['name']);

    }

}
