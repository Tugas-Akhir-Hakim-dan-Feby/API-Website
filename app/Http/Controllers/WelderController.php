<?php

namespace App\Http\Controllers;

use App\Models\Welder;
use Illuminate\Http\Request;

class WelderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $welders = app(Pipeline::class)
        ->send($this->)
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Welder  $welder
     * @return \Illuminate\Http\Response
     */
    public function show(Welder $welder)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Welder  $welder
     * @return \Illuminate\Http\Response
     */
    public function edit(Welder $welder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Welder  $welder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Welder $welder)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Welder  $welder
     * @return \Illuminate\Http\Response
     */
    public function destroy(Welder $welder)
    {
        //
    }
}
