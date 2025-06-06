<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Optional, but good for authorization policies
use Illuminate\Foundation\Validation\ValidatesRequests; // Optional, but good for validation
use Illuminate\Routing\Controller as BaseController;    // Import the base controller

abstract class Controller extends BaseController // Extend BaseController
{
    // If you plan to use authorization policies and form request validation extensively,
    // you might also include these traits, though for just the middleware() method,
    // extending BaseController is the key.
    // use AuthorizesRequests, ValidatesRequests;
}