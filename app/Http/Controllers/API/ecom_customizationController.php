<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ecom_customization;
use Illuminate\Http\Request;

class ecom_customizationController extends Controller
{
    public function createCustomization(Request $request) {
        $customizationData = ecom_customization::where('div_name', $request->div_name)->first();
        if (!empty($customizationData)) {
            $customizationData->div_name = $request->div_name;
            $customizationData->div_value = $request->div_value;
            $customizationData->font_color = $request->font_color;
            $customizationData->font_type = $request->font_type;
            $customizationData->font_size = $request->font_size;
            $customizationData->bg_color = $request->bg_color;
            if ($customizationData->save()) {
                $response = array(
                    'status' => true,
                    'code' => 200,
                    'message' => 'Customization Updated Successfully',
                    'user_id' => $customizationData->id
                );
                $responseCode = 200;
            } else {
                $response = array(
                    'status' => false,
                    'code' => 400,
                    'message' => 'Customization Not Updated'
                );
                $responseCode = 400;
            }
            
        } else {
            $customization = ecom_customization::create([
                'div_name' => $request->div_name,
                'div_value' => $request->div_value,
                'font_color' => $request->font_color,
                'font_type' => $request->font_type,
                'font_size' => $request->font_size,
                'bg_color' => $request->bg_color
            ]);
            if ($customization->id) {
                $response = array(
                    'status' => true,
                    'code' => 200,
                    'message' => 'Customization Created Successfuly',
                    'user_id' => $customization->id
                );
                $responseCode = 200;
            } else {
                $response = array(
                    'status' => false,
                    'code' => 400,
                    'message' => 'Customization Not Created'
                );
                $responseCode = 400;
            }
        }
        return response()->json($response, $responseCode);
    }

    public function getCustomization($divName, Request $request) {
        $customizationData = ecom_customization::where('div_name', $divName)->first();
        if (!empty($customizationData)) {
            return array(
                'status' => true,
                'code' => 200,
                'message' => 'Customization Data Fetched Successfuly',
                'data' => $customizationData
            );
        } else {
            return array(
                'status' => false,
                'code' => 400,
                'message' => 'Customization Data Not Found'
            );
        }
    }
}
