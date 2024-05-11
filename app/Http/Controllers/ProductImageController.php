<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductImage;
use Intervention\Image\Facades\Image; // Assuming you're using Intervention Image
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        // Validate image (size, type, etc.) - Consider adding validation rules

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $productImage = new ProductImage();

        // Handle product_id based on your logic (e.g., pass in request or fetch from database)
        if (isset($request->product_id)) {
            $productImage->product_id = $request->product_id;
        } else {
            // Handle case where product_id is missing
            return response()->json([
                'status' => false,
                'message' => 'Missing product ID',
            ]);
        }

        $productImage->save();

        $imageName = uniqid() . '-' . (isset($product->id) ? $product->id : 0) . '-' . time() . '.' . $ext; // Use a random hash for security
        $productImage->image = $imageName;

        try {
            $productImage->save();

            // Large Image
            $destPath = public_path('uploads/product/large/' . $imageName);
            $image = Image::make($sourcePath);
            $image->resize(1400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destPath);

            // Small Image
            $destPath = public_path('uploads/product/small/' . $imageName);
            $image = Image::make($sourcePath);
            $image->fit(300, 300)->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $productImage->id,
                'ImagePath' => asset('uploads/product/small/' . $productImage->image),
                'message' => 'Image saved successfully',
            ]);
        } catch (Exception $e) {
            // Handle image saving or resizing errors
            return response()->json([
                'status' => false,
                'message' => 'Failed to save image: ' . $e->getMessage(),
            ]);
        }
    }
    public function destroy(Request $request){
        $productImage = ProductImage::find($request->id);

        if (empty($productImage)) {
               return response()->json([
                'status' => false,
                'message' => 'Image not found',
        ]);
        }
        //delete image from folder
        File::delete(public_path('uploads/product/large/'.$productImage->image));
        File::delete(public_path('uploads/product/small/'.$productImage->image));

        $productImage->delete();

         return response()->json([
                'status' => true,
                'message' => 'Image deleted successfully',
        ]);
    }
}
