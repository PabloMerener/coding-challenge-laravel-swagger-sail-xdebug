<?php

namespace App\Http\Controllers;

use App\Models\Player;
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

        $tournament = new Tournament;
        $tournament->gender = $request->gender;

        $tournament->players = collect();
        foreach ($request->players as $key => $value) {
            $player = new Player;
            $player->name = $value['name'];
            $player->pivot = (object) [
                'skill_level' => $value['skill_level'],
                'strength' => $value['strength'],
                'speed' => $value['speed'],
            ];
            $tournament->players->push($player);
        }

        try {
            $results = collect($tournament->service->run());

            return [
                'winner' => $tournament->winner,
                'games' => $results,
            ];
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 422);
        }
    }

    public function results()
    {
        $year = request()->year;
        $gender = request()->gender;

        $query = Tournament::whereNotNull('winner') // successfully completed tournaments
            ->with([
                'games.player1',
                'games.player2',
            ]);

        if ($year) {
            $query->whereYear('date', $year);
        }
        if ($gender) {
            $query->where('gender', $gender);
        }

        return $query->paginate(8);
    }
}
