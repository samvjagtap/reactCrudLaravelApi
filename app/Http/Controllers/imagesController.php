<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\image;

class imagesController extends Controller
{
    public function insertImage(Request $request) {
        // print_r($request->all());
        // $name = time() . '.' . pathinfo($image)['extension'];
        if ($request->has('image_name')) {
            $image = $request->image_name;
            echo '=> '.$image;
            // $name = time() . '.' . $image->getClientOriginalExtension();
            // $name = time() . '.' . pathinfo($image)['extension'];
            // echo '=> '.$name;
            // $image->move('images/',$name);

            // $image = image::create(['image_name' => $name]);
            // return response()->json([
            //     'code' => 200,
            //     'message' => 'Uploaded Successfully',
            //     'image_id' => $image->id
            // ], 200);
        } else {
            return response()->json([
                'code' => 400,
                'message' => 'Somethis went wrong'
            ], 401);
        }
    }
}
