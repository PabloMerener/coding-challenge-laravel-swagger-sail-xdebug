<?php

namespace App\Http\Controllers;

use App\Models\PlayerTournament;
use Illuminate\Http\Request;

class PlayerTournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return PlayerTournament
            ::with(['player', 'tournament'])
            ->paginate(8);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return PlayerTournament
            ::whereId($id)
            ->firstOrFail()
            ->load(['player', 'tournament']);
    }
}
