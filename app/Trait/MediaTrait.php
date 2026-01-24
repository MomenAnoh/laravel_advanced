<?php
namespace App\Trait;

use Illuminate\Support\Facades\Storage;
Trait MediaTrait
{
    public function upload($file, $path)
    {
        $vidName = rand(1,100000). now()->format('YmdHis') .$file->getClientOriginalName();
        return $file->storeAs($path, $vidName, 'public');
    }
    public function updateMedia($oldPath, $newPath,$file)
    {
        if(Storage::disk('public')->exists($oldPath))
        {
            Storage::disk('public')->delete($oldPath);
        }
        return $this->upload($file, $newPath);
    }
    public function delete($path)
    {
        Storage::disk('public')->delete($path);
    }

}
