<?php

use App\Models\Language;
use Illuminate\Support\Facades\Config;

function get_languages(){

    return Language::active() -> Selection() -> get();
}

function get_default_lang(){
    return   Config::get('app.locale');
}


function upload_image($folder, $image){
        // $image->file('product_image');
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $image->move('uploads/'.$folder.'/', $image_name);
        return $image_name;
}
