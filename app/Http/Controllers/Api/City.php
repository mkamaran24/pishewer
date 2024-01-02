<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckRole;
use App\Http\Middleware\TestMid;
use App\Http\Resources\City as ResourcesCity;
use App\Models\City as ModelsCity;
use App\Models\CityTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class City extends Controller
{
    public function __construct()
    {
        $this->middleware(CheckRole::class); // First Middleware
        // $this->middleware(TestMid::class); // Second Middleware
    }

    public function index()
    {
        //
        try {
            //code...
            return ResourcesCity::collection(ModelsCity::paginate(10));
        } catch (\Throwable $th) {
            //throw $th;
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
        # put data to DB after Succes Validation
        try {

            // save $req to DB //////////////////////////////
            $city = new ModelsCity();
            $city->save();
            /////////////////////////////////////////////////

            if (is_array($request->city_trans)) {
                foreach ($request->city_trans as $key => $ct) {

                    CityTranslation::create([
                        'cityname' => $ct['name'],
                        'locale' => $ct['locale'],
                        'city_id' => $city->id
                    ]);
                }
            } else {
                return response()->json([
                    'status' => true,
                    'message' => 'city_trans is not array'
                ], 500);
            }

            // return Job API Resource JSON Response //////////////
            return response()->json([
                'status' => true,
                'messages' => "Object Created"
            ], 201);
            ///////////////////////////////////////////////////////


        } catch (\Throwable $th) {
            // abort(code: 500, message: 'fail to create');
            //throw $th;
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
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
        try {

            $city = ModelsCity::find($id);
            if ($city != null) {
                if (is_array($request->city_trans)) {
                    foreach ($request->city_trans as $key => $ct) {
                        // $decoded_ct = json_decode($ct);

                        CityTranslation::where('locale', $ct['locale'])->where('city_id', $id)->update(['cityname' => $ct['name']]);
                    }
                    return new ResourcesCity($city);
                } else {
                    return response()->json([
                        'status' => true,
                        'message' => 'city_trans is not array'
                    ], 500);
                }
            } else {
                return response()->json([
                    'message' => "Object Not Found"
                ], 404);
            }
        } catch (\Throwable $th) {
            throw $th;
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
        //
        try {
            $city = ModelsCity::where('id', $id)->delete();
            if ($city) {
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
