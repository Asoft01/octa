<?php

namespace App\Http\Controllers\Frontend\AC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
	public function challenge(){
        
        return view('frontend.challenge');
    }	   
}
