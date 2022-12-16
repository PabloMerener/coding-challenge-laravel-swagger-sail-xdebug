<?php

namespace App\Http\Controllers;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(title="API Tournaments", version="1.0")
 *
 * @OA\Server(url="http://localhost")
 */
class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * @OA\Get(
     *     path="/players",
     *     summary="Show players",
     *     @OA\Response(
     *         response=200,
     *         description="Show players description"
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="Error"
     *     )
     * )
     */
    public function index()
    {
        return Player::paginate(8);
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
            'name' => 'required|unique:players',
            'gender' => 'required|in:female,male',
        ])->errors();

        return $errors->any() ? $errors->messages() : Player::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    //public function show(Player $player)
    public function show($id)
    {
        return $id;
        // return request();
        // return $player;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Player $player)
    {
        $errors = Validator::make($request->all(), [
            'name' => 'unique:players',
            'gender' => 'in:female,male',
        ])->errors();

        if ($errors->any()) {
            return $errors->messages();
        } else {
            $player->fill($request->all())->save();

            return $player;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Player  $player
     * @return \Illuminate\Http\Response
     */
    public function destroy(Player $player)
    {
        return $player->delete();
    }
}
