<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Validator;

class BrandsController extends Controller
{
        public function index(Request $request) 
        {
        $brands = Brand::latest('id');
        
        if (!empty($request->get('keyword'))) {
            $brands = $brands->where('name', 'like', '%' . $request->get('keyword') . '%');
        }
       

       $brands = $brands->paginate(10);
        return view('admin.brands.list', compact('brands'));
    }

        public function create(){

            return view('admin.brands.create');
        }

        
        public function store(Request $request){

           $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands',
           // 'status' => 'required'

        ]);  
        
        if ($validator->passes()) {
            $brand = new Brand();
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status =$request->status;
            $brand->save();

            $request->session()->flash('success','Brand added successfully.');
            return response()->json([
                'status' => true,
                'message' =>'Brand added successfully.'
            ]);  

        }else {
            return response()->json([
            'status' => false,
            'errors' => $validator->errors() // Use 'errors' instead of 'error'
        ]);
      }    
    }
    public function edit(Request $request,$id)
    {
         
         $brand = Brand::find($id);
         if (empty($brand)) {
             $request->session()->flash('error','Record not found.');
             return redirect()->route('brands.index');

         }


         $data['brand'] =  $brand;
         return view('admin.brands.edit',$data);

    }

     public function update(Request $request,$id){

         $brand = Brand::find($id);
         if (empty($brand)) {
             $request->session()->flash('error','Record not found.');

             return response()->json([
            'status' => false,
            'notFound' => true
        ]);
              
             //return redirect()->route('brands.index');

         }

           $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,'.$brand->id.',id',
           // 'status' => 'required'

        ]);  
        
        if ($validator->passes()) {
            $brand->name = $request->name;
            $brand->slug = $request->slug;
            $brand->status =$request->status;
            $brand->save();

            $request->session()->flash('success','Brand updata successfully.');
            return response()->json([
                'status' => true,
                'message' =>'Brand updata successfully.'
            ]);  

        }else {
            return response()->json([
            'status' => false,
            'errors' => $validator->errors() // Use 'errors' instead of 'error'
        ]);
      }    
    }
     public function destroy($id,Request $request)
    {
          $brand = Brand::find($id);

         if (empty($brand)) {
             $request->session()->flash('error','Record not found.');

             return response()->json([
            'status' => false,
            'notFound' => true
        ]);
    }

        $brand->delete();
        $request->session()->flash('success', 'Brand deleted successfully');

        return response()->json([
            'status' => true,
            'message' => 'Brand deleted successfully'
        ]);

    }
}
