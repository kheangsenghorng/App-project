<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TempImage;
use Intervention\Image\Facades\Image;


class TempImagesController extends Controller
{
    public function create(Request $request)
    {
     

        if ($request->image) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            $image->move(public_path('/temp'),$newName);


            $sourcePath = public_path().'/temp/'.$newName;
            $destPath = public_path().'/temp/thumb/'.$newName;
            $image = Image::make($sourcePath);
            $image->fit(300,275);
            $image->save($destPath);
            // Return response with image path
            
            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('temp/thumb/' . $newName), // Fixed missing slash
                'message' => 'Image uploaded successfully'
            ]);
        }
    }
}
