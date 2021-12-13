<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Groups;
use App\Models\Subscribe;
class GroupController extends Controller
{
    public function index(Request $request)
    {
        $test = '';
        return response([
            'groups' => Groups::all()->toArray(),
        ], 200);
    }
    
    public function store(Request $request)
    {
        $model = Groups::create([
            'name' => $request->name_group,
        ]);
        return response([
            'status' => true,
        ], 200);
    }

    public function setSubscriber(Request $request)
    {
        $group_id = $request->id_group;
        $count_subscriber = $request->count_subscriber;
        $model = Subscribe::create([
            'group_id' => $group_id,
            'count_subscriber' => $count_subscriber,
        ]);
        //group_id
        //count_subscriber

        return response([
            'status' => true,
        ], 200);
    }
}
