<?php

namespace App\Http\Controllers;

use App\Models\Tournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TournamentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tournament::paginate(8);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'name' => 'required',
            'gender' => 'required|in:female,male',
            'date' => 'required|date',
        ])->errors();

        return $errors->any() ? $errors->messages() : Tournament::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function show(Tournament $tournament)
    {
        return $tournament;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tournament $tournament)
    {
        $errors = Validator::make($request->all(), [
            'gender' => 'in:female,male',
            'date' => 'date',
        ])->errors();

        if ($errors->any()) {
            return $errors->messages();
        } else {
            $tournament->fill($request->all())->save();
            return $tournament;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tournament  $tournament
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tournament $tournament)
    {
        return $tournament->delete();
    }

    public function test(Request $request)
    {
        $errors = Validator::make($request->all(), [
            'gender' => 'required|in:female,male',
            'players' => 'required|array',
        ])->errors();

        if ($errors->any()) {
            return $errors->messages();
        }

        $players = collect();
        foreach ($request->players as $key => $player) {
            $players->push((object)[
                "name" => $player['name'],
                "pivot" => (object) [
                    "skill_level" => $player['skill_level'],
                    "strength" => $player['strength'],
                    "speed" => $player['speed'],
                ]
            ]);
        }

        try {
            return (new Tournament)->service->run($players);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage(),], 422);
        }
    }
}
