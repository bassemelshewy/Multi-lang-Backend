<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    public function index()
    {
        $languages = Language::selection()->paginate(PAGINATION_COUNT);
        return view('admin.languages.index', compact('languages'));
    }

    public function create()
    {
        return view('admin.languages.create');
    }

    public function store(LanguageRequest $request)
    {
        try {
            // Language::create($request->except(['_token']));
            if(!$request->has('active')){
                $request->request->add(['active' => 0]);
            }
            // dd($request->all());
            Language::create($request->all());
            return redirect()->route('admin.languages')->with(['success' => 'تم حفظ اللغة بنجاح']);
        } catch (\Exception $e) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    public function edit($lang_id)
    {
        try {
            $language = Language::selection()->findOrFail($lang_id);
            if (!$language) {
                return redirect()->route('admin.languages')->with(['error' => 'هذه اللغه غير موجوده']);
            }
            return view('admin.languages.edit', compact('language'));
        } catch (\Exception $e) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    public function update(LanguageRequest $request, $lang_id){
        try {
            $language = Language::selection()->findOrFail($lang_id);
            if (!$language) {
                return redirect()->route('admin.languages.edit', $lang_id)->with(['error' => 'هذه اللغة غير موجوده']);
            }
            // dd($request->all());
            if(!$request->has('active')){
                $request->request->add(['active' => 0]);
            }
            // dd($request->all());
            $language->update($request->all());
            return redirect()->route('admin.languages')->with(['success' => 'تم تحديث البيانات بنجاح']);
        } catch (\Exception $e) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }

    }

    public function destroy($lang_id){
        try {
            // Language::selection()->findOrFail($lang_id)->delete();
            $language = Language::findOrFail($lang_id);
            if (!$language) {
                return redirect()->route('admin.languages')->with(['error' => 'هذه اللغة غير موجوده']);
            }
            $language->delete();
            return redirect()->route('admin.languages')->with(['success' => 'تم الحذف بنجاح']);
        } catch (\Exception $e) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    public function changeStatus($id){
        try {
            $language = Language::findOrFail($id);

            if (!$language) {
                return redirect()->route('admin.languages')->with(['error' => 'هذه اللغة غير موجوده']);
            }
            $status = $language->active == 0 ? 1 : 0;

            $language->update(['active' => $status]);

            return redirect()->route('admin.languages')->with(['success' => 'تم تغيير الحاله بنجاح']);
        } catch (\Exception $e) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }

    }
}
