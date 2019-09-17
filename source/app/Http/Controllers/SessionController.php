<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Session;
use App\Group;
use App\Draw;

class SessionController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index() {
        $sessions = Session::all();

        return view('session.index', ['sessions' => $sessions]);
    }

    public function create(Request $request) {
        $groups = Group::all();
        
        if($groups->isEmpty()){
            $request->session()->flash('message', 'É necessário ter algum grupo criado para criar uma sessão.');
            return redirect()->route('session.index');
        }

        return view('session.create', ['groups' => $groups]);
    }

    public function store(Request $request) {
        Session::create(collect($request->all())->except('_token')->toArray());
        return redirect()->route('session.index');
    }

    public function show($id) {

        $session = Session::find($id);
        $draw = Draw::where('session_id', $id)->get()->first();
        $draw = date('Y-m-d', strtotime($draw->created_at));
        
        return view('session.show', ['session' => $session, 'draw' => $draw]);
    }

    public function edit($id) {

        $session = Session::find($id);
        $groups = Group::all();

        return view('session.edit', ['session' => $session, 'groups' => $groups]);
    }

    public function update(Request $request, $id) {

        $session = Session::find($id);
        $session->update(collect($request->all())->except('_token')->toArray());
        return view('session.show', ['session' => $session]);
    }

    public function destroy($id) {

        $session = Session::find($id);
        $session->delete();
        return redirect()->route('session.index');
        
    }

}
