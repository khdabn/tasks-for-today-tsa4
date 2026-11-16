<?php
namespace App\Http\Controllers;
use App\Models\User;
class PublicPageController extends Controller {
 public function profile(){ $user=User::orderBy('id')->firstOrFail();return view('profile',compact('user')); }
 public function about(){return view('about');}
}
