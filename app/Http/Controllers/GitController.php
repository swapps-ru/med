<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GitController extends Controller
{
    public function gitPull(Request $request)
    {
        if($request->input('pass') !== env('GIT_PULL_SECRET', 'youshallnotpassbastard'))
        {
            return abort(404);
        }
        printf('<b>Result: </b> %s', shell_exec('cd ' . base_path() . ' && git reset HEAD --hard && git clean -i -fd && git pull'));
    }
}
