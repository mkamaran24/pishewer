<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Hero as ResourcesHero;
use App\Models\Hero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroController extends Controller
{
    //

    public function index()
    {
        try {
            return ResourcesHero::collection(Hero::all());
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function store(Request $request)
    {
        try {
            if ($request->hasFile('images')) {
                $images = $request->images;
                if (is_array($images)) {
                    foreach ($images as $key => $img) {
                        $new_img_name = random_int(100000, 999999) . $key . '.' . $img->getClientOriginalExtension();

                        // save image name into DB ///////////////////////////////////////////////////////////
                        Hero::create([
                            'name' => $new_img_name,
                        ]);
                        ////////////////////////////////////////////////////////////////////////////

                        // save image in laravel Private Storage ///////////////////////////////////
                        Storage::disk('public')->put($new_img_name, file_get_contents($img));
                        /////////////////////////////////////////////////////////////////////////////

                    } // end of foreach
                    return response()->json([
                        'messsage' => 'Object Created'
                    ], 201);
                } else {
                    return response()->json([
                        'messsage' => 'image is not array'
                    ], 400);
                }
            } else {
                return response()->json([
                    'messsage' => 'file not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateimage(Request $request, $img_id)
    {
        try {
            $image = Hero::find($img_id);

            if ($image != null) {
                // add image to storage ////////////////////////////////
                $hero_image = $request->image;
                $new_hero_image = time() . '.' . $hero_image->getClientOriginalExtension();
                Storage::disk('public')->put($new_hero_image, file_get_contents($hero_image));
                /////////////////////////////////////////////////////////

                // delete old image /////////////////////////////////////
                $img_path = 'public/' . $image->name;
                Storage::delete($img_path);
                //////////////////////////////////////////////////////////

                // Save to DB ///////////////////////////////////////////
                $image->name = $new_hero_image;
                $image->save();
                /////////////////////////////////////////////////////////////

                return response()->json([
                    'messsage' => 'Object Updated'
                ], 200);
            } else {
                return response()->json([
                    'messsage' => 'file not found'
                ], 404);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function destroy($img_id)
    {
        try {
            $img = Hero::where('id', $img_id)->delete();
            if ($img) {
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
            //throw $th;
        }
    }
}
