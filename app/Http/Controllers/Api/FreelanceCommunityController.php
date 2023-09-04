<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FreelanceCommunity;
use App\Models\FreelanceCommunityTran;
use App\Models\FreelanceComunity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FreelanceCommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        try {
            return FreelanceCommunity::collection(FreelanceComunity::all());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {

            // add image to storage ////////////////////////////////
            $buyer_stories_image = $request->image;
            $new_buyer_stories_image = now() . '.' . $buyer_stories_image->getClientOriginalExtension();
            Storage::disk('public')->put($new_buyer_stories_image, file_get_contents($buyer_stories_image));
            //////////////////////////////////////////////////////////

            // Save to FreelanceCommunity DB /////////////////////////////////////////////
            $buyer_stories = new FreelanceComunity();
            $buyer_stories->image = $new_buyer_stories_image;
            $buyer_stories->save();
            //////////////////////////////////////////////////////////////////////////////

            // Save to FreelanceCommunityTrans DB ////////////////////////////////////////
            if (is_array($request->buyer_story)) {
                foreach ($request->buyer_story as $key => $bs) {
                    $decoded_bs = json_decode($bs);
                    FreelanceCommunityTran::create([
                        'title' => $decoded_bs->name,
                        'story' => $decoded_bs->story,
                        'locale' => $decoded_bs->locale,
                        'freelance_community_id' => $buyer_stories->id
                    ]);
                }
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'buyer story is not array'
                ], 500);
            }
            /////////////////////////////////////////////////////////

            // return FreelanceCommunity API Resource JSON Response //////////////
            return response()->json([
                'status' => true,
                'message' => 'Object Created'
            ], 201);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $buyer_story = FreelanceComunity::find($id);

        if ($buyer_story != null) {

            // Validation of $request should goes here
            ////

            // add image to storage ////////////////////////////////
            $buyer_stories_image = $request->image;
            $new_buyer_stories_image = random_int(100000, 999999) . '.' . $buyer_stories_image->getClientOriginalExtension();
            Storage::disk('public')->put($new_buyer_stories_image, file_get_contents($buyer_stories_image));

            // Save to DB ///////////////////////////////////////////

            $img_path = 'public/' . $buyer_story->image;

            Storage::delete($img_path);

            $buyer_story->image = $new_buyer_stories_image;
            $buyer_story->save();

            if (is_array($request->buyer_story)) {
                foreach ($request->buyer_story as $key => $bs) {
                    $decoded_bs = json_decode($bs);

                    FreelanceCommunityTran::where('locale', $decoded_bs->locale)->where('freelance_community_id', $id)->update(['title' => $decoded_bs->title, 'story' => $decoded_bs->story]);
                }

                return new FreelanceCommunity($buyer_story);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'categ_trans is not array'
                ], 500);
            }
        } else {
            return response()->json([
                'message' => "Object Not Found"
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $categ = FreelanceComunity::where('id', $id)->delete();
            if ($categ) {
                return response()->json([
                    'status' => true,
                    'messages' => "Delete Success",
                    "data" => []
                ], 200);
            } else {
                # code...
                return response()->json([
                    'status' => false,
                    'messages' => "Object Not Found"
                ], 404);
            }
        } catch (\Throwable $th) {
            throw $th;
            // abort(code: 500, message: 'fail to delete');
            //Logs implementation goes down herer


            ////////////////////////////////////////////
        }
    }
}
