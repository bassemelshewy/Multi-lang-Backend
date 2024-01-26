<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use \App\Http\Requests\Admin\MainCatRequest;
use App\Models\MainCategory;
use \Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MainCategoryController extends Controller
{
    public function index()
    {
        try {
            $def_lang = get_default_lang();
            $categories = MainCategory::where('translation_lang', $def_lang)
                ->selection()
                ->paginate(PAGINATION_COUNT);

            return view("admin.maincategories.index", compact("categories"));
        } catch (Exception $e) {
            return view("admin.dashboard")->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    public function create()
    {
        return view('admin.maincategories.create');
    }

    public function store(MainCatRequest $request){
        try {
            // dd($request->all());

            $mainCat = collect($request->category);

            $filter = $mainCat->filter(function ($value, $key) {
                return $value['abbr'] == get_default_lang();
            });

            $default_cat = array_values($filter->all())[0]; //$filter->all() =====> convert from object ($filter) to array

            DB::beginTransaction();

            $image_name = '';
            if ($request->hasFile('photo')) {
                $image_name = upload_image('maincategory', $request->file('photo'));
            }


            if (!isset($default_cat['active'])) {
                // Set the 'active' field to 0 if it doesn't exist or is falsy
                $default_cat['active'] = 0;
            }
            // return $default_cat;
            $default_cat_id = MainCategory::insertGetId([
                'translation_lang' => $default_cat['abbr'],
                'translation_of' => 0,
                'name' => $default_cat['name'],
                'slug' => $default_cat['name'],
                'photo' => $image_name,
                'active' => $default_cat['active'],
            ]);

            $categories = $mainCat->filter(function ($value, $key) {
                return $value['abbr'] != get_default_lang();
            });

            if (isset($categories) && $categories->count()) {

                $categories_arr = [];
                foreach ($categories as $category) {

                    if (!isset($category['active'])) {
                        // Set the 'active' field to 0 if it doesn't exist or is falsy
                        $category['active'] = 0;
                    }

                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_cat_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $image_name,
                        'active' => $category['active']
                    ];
                }
                MainCategory::insert($categories_arr);
            }

            DB::commit();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم الحفظ بنجاح']);
        } catch (Exception $e) {
            return redirect()->route('admin.maincategories')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    public function edit($id){
        //get specific categories and its translations
        $mainCategory = MainCategory::with('categories')
            ->selection()
            ->find($id);
        if (!$mainCategory)
            return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

        return view('admin.maincategories.edit', compact('mainCategory'));
    }


    public function update(mainCatRequest $request, $id){
        try {
            $main_category = MainCategory::find($id);

            if (!$main_category)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

            // update data

            $category = array_values($request->category)[0];

            if (!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            DB::beginTransaction();

            MainCategory::where('id', $id)
                ->update([
                    'name' => $category['name'],
                    'active' => $request->active,
                ]);

            // save image
            if ($request->has('photo')) {
                $image_name = upload_image('maincategories', $request->file('photo'));
                MainCategory::where('id', $id)
                    ->update([
                        'photo' => $image_name,
                    ]);
            }

            DB::commit();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم ألتحديث بنجاح']);
        } catch (Exception $e) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function destroy($id){
        try{
            $maincategory = MainCategory::findOrFail($id);

            if (!$maincategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);
            DB::beginTransaction();
            $vendors = $maincategory->vendors();
            if (isset($vendors) && $vendors->count() > 0) {
                return redirect()->route('admin.maincategories')->with(['error' => 'لا يمكن حذف هذا القسم  ']);
            }

            $image = Str::after($maincategory->photo, 'uploads/maincategory/');
            // $image = base_path('uploads'.DIRECTORY_SEPARATOR .'maincategory/'.$image);
            $image = public_path('uploads'.DIRECTORY_SEPARATOR .'maincategory/'.$image); // to reach to public folder

            if (file_exists($image)) {
                unlink($image); //delete from folder
            }

            $maincategory->categories()->delete();

            $maincategory->delete();

            DB::commit();

            return redirect()->route('admin.maincategories')->with(['success' => 'تم حذف القسم بنجاح']);

        } catch (Exception $e) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function changeStatus($id){
        try{
            $maincategory = MainCategory::findOrFail($id);
            if (!$maincategory)
                return redirect()->route('admin.maincategories')->with(['error' => 'هذا القسم غير موجود ']);

            DB::beginTransaction();

            $status =  $maincategory -> active  == 0 ? 1 : 0;

            $maincategory -> update(['active' =>$status ]);

            DB::commit();

            return redirect()->route('admin.maincategories')->with(['success' => ' تم تغيير الحالة بنجاح ']);

        } catch (Exception $e) {
            return redirect()->route('admin.maincategories')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
}
