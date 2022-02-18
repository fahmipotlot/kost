<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Kost;
use App\Http\Middleware\IsOwner;

class KostController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware([IsOwner::class])->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $kost = Kost::where('user_id', $user->id)->paginate(10);

        return $kost;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $this->validate(request(), [
            'name' => 'required|max:191',
            'location' => 'required|string|max:191',
            'price' => 'required|numeric|min:0'
        ]);

        Kost::create([
            'name' => $request->name,
            'location' => $request->location,
            'price' => $request->price,
            'user_id' => $user->id
        ]);

        return response()->json([
            'message' => 'You have successfully create kost'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $kost = Kost::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        return $kost;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $this->validate(request(), [
            'name' => 'required|max:191',
            'location' => 'required|string|max:191',
            'price' => 'required|numeric|min:0'
        ]);

        $kost = Kost::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $kost->update([
            'name' => $request->name,
            'location' => $request->location,
            'price' => $request->price
        ]);

        return response()->json([
            'message' => 'You have successfully update kost'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $kost = Kost::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $kost->delete();

        return response()->json([
            'message' => 'You have successfully delete kost'
        ], 200);
    }
}