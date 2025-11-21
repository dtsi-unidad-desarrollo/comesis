<?php

namespace App\Http\Controllers;

use App\Models\Notificacione;
use App\Http\Requests\StoreNotificacioneRequest;
use App\Http\Requests\UpdateNotificacioneRequest;

class NotificacioneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreNotificacioneRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNotificacioneRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Notificacione  $notificacione
     * @return \Illuminate\Http\Response
     */
    public function show(Notificacione $notificacione)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notificacione  $notificacione
     * @return \Illuminate\Http\Response
     */
    public function edit(Notificacione $notificacione)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNotificacioneRequest  $request
     * @param  \App\Models\Notificacione  $notificacione
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNotificacioneRequest $request, Notificacione $notificacione)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notificacione  $notificacione
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notificacione $notificacione)
    {
        //
    }
}
