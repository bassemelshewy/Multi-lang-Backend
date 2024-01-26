<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VendorsController extends Controller
{
    public function index(){
        $vendors = Vendor::selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        $categories = MainCategory::where('translation_of', 0)->active()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.create', compact('categories'));
    }

    public function store(VendorRequest $request)
    {
        try {
            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            DB::beginTransaction();

            $imageName = "";
            if ($request->has('logo')) {
                $imageName = upload_image('vendors', $request->logo);
            }

            Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'active' => $request->active,
                'address' => $request->address,
                'logo' => $imageName,
                'password' => $request->password,
                'category_id' => $request->category_id,
                // 'latitude' => $request->latitude,
                // 'longitude' => $request->longitude,
            ]);
            DB::commit();

            return redirect()->route('admin.vendors')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }


    public function edit($id){
        try{
        $vendor = Vendor::selection()->findOrFail($id);
        if (!$vendor)
            return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);

        $categories = MainCategory::where('translation_of', 0)->active()->paginate(PAGINATION_COUNT);

        return view('admin.vendors.edit', compact('vendor', 'categories'));
        }catch(\Exception $e){
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }

    public function update(VendorRequest $request, $id){
        try {
            $vendor = Vendor::findOrFail($id);
            if(!$vendor){
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);
            }

            DB::beginTransaction();

            if($request->has('logo')){
                $imageName = upload_image('vendors', $request->logo);
                $vendor->update([
                    'logo' => $imageName
                ]);
            }

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            $data = $request->all();

            if ($request->has('password') && !is_null($request->password)) {

                $data['password'] = $request->password;
            }
            $vendor->update($data);

            DB::commit();
            return redirect()->route('admin.vendors')->with(['success' => 'تم التحديث بنجاح']);

        } catch (\Exception $e) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }


    public function destroy($id){
        try {
            $vendor = Vendor::findOrFail($id);
            if (!$vendor) {
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);
            }

            DB::beginTransaction();
            $image = Str::after($vendor->logo, 'uploads/vendors/');
            $image = public_path('uploads'.DIRECTORY_SEPARATOR.'vendors/'.$image); // to reach to public folder

            if (file_exists($image)) {
                unlink($image); //delete from folder
            }
            $vendor->delete();
            DB::commit();

            return redirect()->route('admin.vendors')->with(['success' => 'تم الحذف بنجاح']);
        }catch(\Exception $e){
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }

    public function changestatus($id){
        try {
            $vendor = Vendor::findOrFail($id);
            if(!$vendor){
                return redirect()->route('admin.vendors')->with(['error' => 'هذا المتجر غير موجود او ربما يكون محذوفا ']);
            }

            DB::beginTransaction();

            $status = $vendor->active == 1 ? 0 : 1;

            $vendor->update([
                'active' => $status
            ]);

            DB::commit();

            return redirect()->route('admin.vendors')->with(['success' => 'تم تغيير الحاله بنجاح']);

        } catch (\Exception $e) {
            return redirect()->route('admin.vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }
    }
}
