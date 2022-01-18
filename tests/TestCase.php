<?php

namespace Tests;
use App\User;
use App\Plan;
use App\Spot;
use App\Tag;
use App\Image;
use App\Link;
use App\PlanTag;
use App\SpotTag;
use App\SpotComment;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function createBasePostTestData()
    {

      $user = factory(User::class)->create();

      $plan = factory(Plan::class)->create([
        'user_id' => $user->id,
      ]);

      $spot = factory(Spot::class)->create([
        'user_id' => $user->id,
        'plan_id' => $plan->id,
      ]);

      $image = factory(Image::class)->create([
        'spot_id' => $spot->id,
      ]);

      $link = factory(Link::class)->create([
        'spot_id' => $spot->id,
      ]);

      $tag = factory(Tag::class)->create();

      $spot_tag = factory(SpotTag::class)->create([
        'spot_id' => $spot->id,
        'tag_id' => $tag->id,
      ]);

      $plan_tag = factory(PlanTag::class)->create([
        'plan_id' => $plan->id,
        'tag_id' => $tag->id,
      ]);

      $spot_comment = factory(SpotComment::class)->create([
        'spot_id' => $spot->id,
        'user_id' => $user->id,
      ]);

      $user->refresh();
      $plan->refresh();
      $spot->refresh();

      $response = [
        'user' => $user,
        'plan' => $plan,
        'spot' => $spot,
        'image' => $image,
        'link' => $link,
        'tag' => $tag,
        'comment' => $spot_comment,
      ];

      return $response;
    }


    // public function createBasePostTestData($count_array = [])
    // {
    //
    //   if($count_array == []){
    //       $count_array = [
    //           'plan_count' => 1,
    //           'spot_count' => 1,
    //           'tag_count' => 1,
    //           'image_count' => 1,
    //           'link_count' => 1,
    //           'comment_count' => 1,
    //         ];
    //   }
    //
    //   $user = factory(User::class, 1)->create();
    //
    //   foreach ($user as $eachUser) {
    //
    //     $plan = factory(Plan::class, $count_array['plan_count'])->create([
    //       'user_id' => $eachUser->id,
    //     ]);
    //
    //     foreach($plan as $eachPlan){
    //       $tag = factory(Tag::class,$count_array['tag_count'])->create();
    //
    //       foreach ($tag as $eachTag) {
    //         $plan_tag = factory(PlanTag::class)->create([
    //           'plan_id' => $eachPlan->id,
    //           'tag_id' => $eachTag->id,
    //         ]);
    //       }
    //
    //       $spot = factory(Spot::class, $count_array['spot_count'])->create([
    //         'user_id' => $eachUser->id,
    //         'plan_id' => $eachPlan->id,
    //       ]);
    //
    //
    //       foreach ($spot as $eachSpot) {
    //         $image = factory(Image::class, $count_array['image_count'])->create([
    //           'spot_id' => $eachSpot->id,
    //         ]);
    //
    //         $link = factory(Link::class, $count_array['link_count'])->create([
    //           'spot_id' => $eachSpot->id,
    //         ]);
    //
    //         $spot_comment = factory(SpotComment::class, $count_array['comment_count'])->create([
    //           'spot_id' => $eachSpot->id,
    //           'user_id' => $eachUser->id,
    //         ]);
    //
    //         $tag = factory(Tag::class,$count_array['tag_count'])->create();
    //
    //         foreach ($tag as $eachTag) {
    //           $spot_tag = factory(SpotTag::class, $count_array['tag_count'])->create([
    //             'spot_id' => $eachSpot->id,
    //             'tag_id' => $eachTag->id,
    //           ]);
    //         }
    //
    //         $eachSpot->refresh();
    //       }
    //
    //       $eachPlan->refresh();
    //     }
    //
    //     $eachUser->refresh();
    //   }
    //
    //   // dd($user[0]->plans);
    //   // dd($user[0]->plans[1]->spots[0]->tags);
    //   //
    //   //
    //   // $plan = factory(Plan::class)->create([
    //   //   'user_id' => $user->id,
    //   // ]);
    //   //
    //   // $spot = factory(Spot::class)->create([
    //   //   'user_id' => $user->id,
    //   //   'plan_id' => $plan->id,
    //   // ]);
    //   //
    //   // $image = factory(Image::class)->create([
    //   //   'spot_id' => $spot->id,
    //   // ]);
    //   //
    //   // $link = factory(Link::class)->create([
    //   //   'spot_id' => $spot->id,
    //   // ]);
    //   //
    //   // $tag = factory(Tag::class)->create();
    //   //
    //   // $spot_tag = factory(SpotTag::class)->create([
    //   //   'spot_id' => $spot->id,
    //   //   'tag_id' => $tag->id,
    //   // ]);
    //   //
    //   // $plan_tag = factory(PlanTag::class)->create([
    //   //   'plan_id' => $plan->id,
    //   //   'tag_id' => $tag->id,
    //   // ]);
    //   //
    //   // $spot_comment = factory(SpotComment::class)->create([
    //   //   'spot_id' => $spot->id,
    //   //   'user_id' => $user->id,
    //   // ]);
    //   //
    //   // dd($spot->refresh()->images);
    //
    //   return $user;
    // }

    public function createBaseUserTestData()
    {
      $user = factory(User::class, 1)->create();
    }

}
