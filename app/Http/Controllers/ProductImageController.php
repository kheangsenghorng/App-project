<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductImage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class ProductImageController extends Controller
{
    public function update(Request $request)
    {
        // Validate the image input
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_id' => 'required|integer|exists:products,id'
        ]);

        $image = $request->file('image');
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();

        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id;

        $imageName = uniqid() . '-' . $productImage->product_id . '-' . time() . '.' . $ext;
        $productImage->image = $imageName;

        try {
            $productImage->save();

            // Save Large Image
            $largeDestPath = public_path('uploads/product/large/' . $imageName);
            $largeImage = Image::make($sourcePath);
            $largeImage->resize(1400, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($largeDestPath);

            // Save Small Image
            $smallDestPath = public_path('uploads/product/small/' . $imageName);
            $smallImage = Image::make($sourcePath);
            $smallImage->fit(300, 300)->save($smallDestPath);

            return response()->json([
                'status' => true,
                'image_id' => $productImage->id,
                'ImagePath' => asset('uploads/product/small/' . $productImage->image),
                'message' => 'Image saved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to save image: ' . $e->getMessage(),
            ]);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:product_images,id'
        ]);

        $productImage = ProductImage::find($request->id);

        if (empty($productImage)) {
            return response()->json([
                'status' => false,
                'message' => 'Image not found',
            ]);
        }

        // Delete image from folder
        File::delete(public_path('uploads/product/large/' . $productImage->image));
        File::delete(public_path('uploads/product/small/' . $productImage->image));

        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image deleted successfully',
        ]);
    }
}
