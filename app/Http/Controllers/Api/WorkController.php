<?php

namespace App\Http\Controllers\Api;

use App\Work;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WorkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Work::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $work = new Work();
        $work->name = $request->name;
        $work->location= $request->location;
        $work->lat = $request->lat;
        $work->long = $request->long;
        $work->worker = json_encode($request->worker);
        $work->save();

        return response()->json($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $work = Work::findOrFail($id);
        $work->worker = json_decode($work->worker);

        return response()->json($work);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function edit(Work $work)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $work = Work::findOrFail($id);
        $work->name= $request->name;
        $work->location= $request->location;
        $work->lat = $request->lat;
        $work->long = $request->long;
        $work->worker = json_encode($request->worker);
        $work->save();

        return response()->json($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Work  $work
     * @return \Illuminate\Http\Response
     */
    public function destroy(Work $work)
    {
        Work::destroy($work->id);

        return '';
    }
}
