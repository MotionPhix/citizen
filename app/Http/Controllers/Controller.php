<?php

namespace App\Http\Controllers;

abstract class Controller
{
  protected function hybridRender($view, $data = [])
  {
    if (request()->header('X-Inertia')) {
      return Inertia($view, $data);
    }

    return view($view, $data);
  }
}
