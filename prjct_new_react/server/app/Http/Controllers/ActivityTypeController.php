<?php

namespace App\Http\Controllers;

use App\Models\ActivityType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ActivityTypeController extends Controller
{
    

    public function addActivityType(Request $request)
    {

        try {
            $user = Auth::user();
            if (!$request->name) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'fill all required fields',
                ]);
            }

            if ($request->icon) {

                $messages = [
                    'required' => 'Please select a file to upload.',
                    'mimes' => 'The uploaded file is not a supported format.',
                    'max' => 'The file size exceeds the maximum limit of 2 mb.',
                ];

                $validator = Validator::make(
                    $request->all(),
                    [
                        'icon' => 'required|mimes:jpeg,jpg,png|max:2048',
                    ],
                    $messages
                );

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }

                $icon = $request->file('icon');

                $activity_type = ActivityType::create([

                    'name' => $request->name,
                    
                ]);
                $file_name =  $file_name = uniqid('media_') . '.' . $icon->getClientOriginalExtension();

                $directory = "public/activity_type/$activity_type->id/icon";

                $path = Storage::putFileAs($directory, $icon, $file_name);


                $activity_type->update([
                    'icon' => $file_name,
                ]);

                return response()->json([

                    "status" => 'success',
                    "message" => 'Activity type added successfully',
                    "activity_type" => $activity_type,


                ]);
            } else {

                $activity_type = ActivityType::create([

                    'name' => $request->name,



                ]);
                return response()->json([

                    "status" => 'success',
                    "message" => 'Activity type added successfully',
                    "activity_type" => $activity_type,

                ]);
            }
        } catch (\Exception $ex) {


            return response()->json([
                'status' => 'error',
                'message' => 'an exception occured while adding an activity type',
            ]);
        }
    }



    public function editActivityType(Request $request, $activity_Type_id)
    {


        try {
            $activity_type = ActivityType::findOrFail($activity_Type_id);

            $user = Auth::user();

            if ($user->role_id !== 1) {

                return response()->json([

                    'status' => 'failed',
                    'message' => 'only admin can edit',


                ]);
            }

            // if (!$request->filled('name')) {
            //     return response()->json([
            //         'status' => 'failed',
            //         'message' => 'Please fill in required fields!',
            //     ]);
            // }

            $activity_type->update([
                'name' => $request->input('name', $activity_type->name),


            ]);

            if ($request->icon) {


                $messages = [
                    'required' => 'Please select a file to upload.',
                    'mimes' => 'The uploaded file is not a supported format.',
                    'max' => 'The file size exceeds the maximum limit of 2 mb.',
                ];

                $validator = Validator::make(
                    $request->all(),
                    [
                        'icon' => 'required|mimes:jpeg,jpg,png|max:2048',
                    ],
                    $messages
                );

                if ($validator->fails()) {
                    return response()->json($validator->errors(), 400);
                }

                $icon = $request->file('icon');


                $file_name =  $file_name = uniqid('media_') . '.' . $icon->getClientOriginalExtension();

                $directory = "public/activity_type/$activity_type->id/icon";

                $path = Storage::putFileAs($directory, $icon, $file_name);

                //$full_path = "C:/xampp/htdocs/prjct_new_3/server/storage/app/$directory/$file_name";

                if (Storage::exists("$directory/$activity_type->icon")) {
                    Storage::delete("$directory/$activity_type->icon");
                }



                $activity_type->update([
                    'icon' => $file_name,
                ]);
            }





            return response()->json([
                'status' => 'Success',
                'message' => 'activity type edited successfully',
                'activity_type' => $activity_type,
            ]);
        } catch (\Exception $ex) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to edit activity type',
            ]);
        }
    }
    
    public function getActivityType($activityType_id)
    {
        try {
            $activityType = ActivityType::with('activity')->findorfail($activityType_id);
            return response()->json([
                'status' => 'success',
                'message' => 'activity type retrieved successfully',
                'activity' => $activityType,
            ]);
        } catch (Exception $ex) {
             return response()->json([
                'status' => 'error',
                'message' => 'an error occured while trying to retrieve activity type',
                
            ]);
        }
    }
    
    public function getAllActivityTypes(){
        try{
            
            $acts = ActivityType::with('activity')->get();
            return response()->json([
                'status' => 'Success',
                'message' => 'activity Types retrieved successfully',
                'acts' => $acts,
                
                ]);
            
            
        }catch(Exception $ex){
            
             return response()->json([
                'status' => 'error',
                'message' =>"an error occured while trying to retrieve activityTypes ",]);
            
            
        }
        
    }

    public function deleteActivityType($activity_type_id){
            try {
          
                $activityType = ActivityType::findorfail($activity_type_id);

                $directory = "public/activity_type/$activity_type_id/icon";

             

                //$full_path = "C:/xampp/htdocs/prjct_new_3/server/storage/app/$directory/$file_name";

       
        $activityType->delete();

                if (Storage::exists("$directory/$activityType->icon")) {
                    Storage::delete("$directory/$activityType->icon");
                }

        return response()->json([

            'status' => 'success',
            'message' => 'activityType successfully deleted',


        ]); 
    } catch (Exception $ex) {


        return response()->json([

            'status' => 'error',
            'message' => " an error occured while trying to delete activityType ",


        ]);
    }
    }

}
