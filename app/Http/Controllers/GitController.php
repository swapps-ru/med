<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GitController extends Controller
{
    public function gitPull(Request $request)
    {
        if($request->input('pass') !== env('GIT_PULL_SECRET', 'youshallnotpassbastard') && !empty(env('GIT_PULL_URL')))
        {
            return abort(404);
        }

        //в рабочей директории установить git config core.sharedRepository true
        printf('<b>Result: </b> %s', shell_exec('cd ' . base_path() . ' && git config core.sharedRepository true && git reset HEAD --hard && git clean -f && git pull ' . env('GIT_PULL_URL') . ' 2>&1'));
    }
}
