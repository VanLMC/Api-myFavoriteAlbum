<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Image;

class UserController extends Controller
{
    public function store(Request $request){
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json($user);
    }   

    public function update($id){
        $user = auth()->user()->get();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json($user);
    }

    public function show($id) {
        $user = User::find($id)->with('images')->get();
        return response()->json($user);
    }

    public function adicionarImagem(Request $request){
        
        $name = uniqid(date('HisYmd'));
        $extension = $request->image->extension();
        $nameFile = "{$name}.{$extension}";
            

        $file = $request->file('image');
        $upload = $request->image->storeAs('images/'.auth()->user()->id.'/', $nameFile);

        if($upload){
            $user = auth()->user();
            $image = new Image();
            $image->name = $nameFile;
            $image->user_id = $user->id;
            $image->save();

            return response()->json(['success', 'upload feito com êxito'], 200);
        }
        else {
            return response()->json(['error', 'houve um erro no upload'], 500);
        }
    }


}
