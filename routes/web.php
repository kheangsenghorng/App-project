<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TempImagesController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSubCategoryController;
use App\Http\Controllers\ProductImageController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


// Route::get('/', function () {
//     return view('welcome');
// });


Route::get('/', [FrontController::class,'index'])->name('front.home');
Route::get('/shop/{categorySlug?}/{subCategorySlug?}', [ShopController::class,'index'])->name('front.shop');





Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/admin/dashboard', [HomeController::class,'index'])->
     middleware('auth','admin');
  //Category route   
 Route::get('/admin/categories', [CategoryController::class,'index'])->name('categories.index');
 Route::get('/admin/categories/create', [CategoryController::class, 'create'])->name('categories.create');
 Route::post('/categories', [CategoryController::class,'store'])->name('categories.store');
 Route::get('/categories/{category}/edit', [CategoryController::class,'edit'])->name('categories.edit');
 Route::put('/categories/{category}', [CategoryController::class,'update'])->name('categories.update');
 Route::delete('/categories/{category}', [CategoryController::class,'destroy'])->name('categories.delete');
 
 //sub-category route
 Route::get('/admin/sub-categories', [SubCategoryController::class,'index'])->name('sub-categories.index');
 Route::get('/sub-categories/create', [SubCategoryController::class, 'create'])->name('sub-categories.create');
 Route::post('/sub-categories', [SubCategoryController::class,'store'])->name('sub-categories.store');
 Route::get('/sub-categories/{subCategory}/edit', [SubCategoryController::class,'edit'])->name('sub-categories.edit');
 Route::put('/sub-categories/{subCategory}', [SubCategoryController::class,'update'])->name('sub-categories.update');
 Route::delete('/sub-categories/{subCategory}', [SubCategoryController::class,'destroy'])->name('sub-categories.delete');
 
 //Brands route
 Route::get('/admin/brands', [BrandsController::class,'index'])->name('brands.index');
 Route::get('/brands/create', [BrandsController::class, 'create'])->name('brands.create');
 Route::post('/brands', [BrandsController::class,'store'])->name('brands.store');
 Route::get('/brands/{brand}/edit', [BrandsController::class,'edit'])->name('brands.edit');
 Route::put('/brands/{brand}', [BrandsController::class,'update'])->name('brands.update');
 Route::delete('/brands/{brand}', [BrandsController::class,'destroy'])->name('brands.delete');
 
 //product routes
 Route::get('/admin/products', [ProductController::class,'index'])->name('products.index');
 Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
 Route::post('/products', [ProductController::class,'store'])->name('products.store');
 Route::get('/products/{product}/edit', [ProductController::class,'edit'])->name('products.edit');
 Route::put('/products/{product}', [ProductController::class,'update'])->name('products.update');
 Route::delete('/products/{product}', [ProductController::class,'destroy'])->name('products.delete');
 
 
 Route::get('/admin/product-subcategories', [ProductSubCategoryController::class,'index'])->name('product-subcategories.index');

 //

 Route::post('/product-images/update', [ProductImageController::class,'update'])->name('product-images.update');
 Route::delete('/product-images', [ProductImageController::class,'destroy'])->name('product-images.destroy');
 
 //temp-images.create
 Route::post('/upload-temp-images', [TempImagesController::class,'create'])->name('temp-images.create');


 Route::get('/getSlug', function(Request $request){

    $slug = '';
    if(!empty($request->title)){
        $slug = Str::slug($request->title);
    }

    return response()->json([
        'status' => true,
        'slug' =>$slug
    ]);
 })->name('getSlug');
