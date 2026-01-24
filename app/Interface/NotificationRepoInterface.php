<?php
 namespace App\Interface;
  use Illuminate\Http\Request;

interface NotificationRepoInterface
{
     public function store(Request $request);
     public function show($usr_id);

}
