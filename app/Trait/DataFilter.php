<?php

namespace App\Trait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
trait DataFilter
{
    /**
     * فلترة بسيطة + دعم like
     *
     * @param Builder $query
     * @param Request $request
     * @param array $fields
     *   مثال: ['name' => 'like', 'price' => 'exact', 'category_id' => 'exact']
     */

    /*
Builder $query دي الاي مسؤاله عن عملبة البحث اصلا
Request $request طبعا القيم الفي  فالريكوست الي ببحث عنها
array $fields = [] الحقول الي ببحث فيها
    */
    public function scopeFilter(Builder $query, Request $request, array $fields = [])
    {
        foreach ($fields as $field => $type) {  // بيلف ع الحقول
            if ($request->filled($field)) {  //بيشوف الحقل الي فيه قيمة
                $value = $request->get($field); // get value of field

                if ($type === 'like') {  // like for stiring
                    $query->where($field, 'LIKE', "%{$value}%");
                } else { // exact for number
                    $query->where($field, $value);
                }
            }
        }

        return $query;
    }



/*

    public function scopefilter2(Builder $query,Request $request,array $fields=[])
    {
        foreach($fields as $filed=>$type)
        {
            if($request->filled($filed))
            {
                $value=$request->get($filed);
                if($type =='like')
                {
                    $query->where($filed,'like',"%{$value}%");
                }
                else{
                    $query->where($filed,$value);
                }
            }
        }
        return $query;
    }


*/










}
