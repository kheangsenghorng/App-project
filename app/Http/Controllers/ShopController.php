<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Brand;

class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = '';
        $subCategorySelected = '';
        $brandsArray = [];

        $categories = Category::orderBy('name', 'ASC')
            ->with('sub_category')
            ->where('status', 1)
            ->get();

        $brands = Brand::orderBy('name', 'ASC')
            ->where('status', 1)
            ->get();

        $products = Product::where('status', 1);

        // Apply filters here
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $products->where('category_id', $category->id);
                $categorySelected = $category->id;
            }
        }

        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            if ($subCategory) {
                $products->where('sub_category_id', $subCategory->id);
                $subCategorySelected = $subCategory->id;
            }
        }

        $brandInput = $request->get('brand');
        if (!empty($brandInput)) {
            $brandsArray = explode(',', $brandInput);
            $products->whereIn('brand_id', $brandsArray);
        }

        $priceMin = $request->get('price_min');
        $priceMax = $request->get('price_max');

        if (!empty($priceMin) && !empty($priceMax)) {
            $priceMax = $priceMax == 1000 ? 1000000 : intval($priceMax);
            $products->whereBetween('price', [intval($priceMin), $priceMax]);
        }

        $sort = $request->get('sort');
        if (!empty($sort)) {
            switch ($sort) {
                case 'latest':
                    $products->orderBy('id', 'DESC');
                    break;
                case 'price_asc':
                    $products->orderBy('price', 'ASC');
                    break;
                case 'price_desc':
                    $products->orderBy('price', 'DESC');
                    break;
                default:
                    $products->orderBy('id', 'DESC');
                    break;
            }
        } else {
            $products->orderBy('id', 'DESC');
        }

        $products = $products->paginate(6);

        $data = [
            'categories' => $categories,
            'brands' => $brands,
            'products' => $products,
            'categorySelected' => $categorySelected,
            'subCategorySelected' => $subCategorySelected,
            'brandsArray' => $brandsArray,
            'priceMax' => $priceMax ?? 1000,
            'priceMin' => intval($priceMin),
            'sort' => $sort,
        ];

        return view('front.shop', $data);
    }
}
