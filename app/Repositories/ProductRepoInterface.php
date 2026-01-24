<?php
 namespace App\Repositories;
 use Illuminate\Http\Request;
interface ProductRepoInterface
{
    public function index(Request $request);
    public function store(Request $request);
    public function show($id);
    public function delete($id);
}