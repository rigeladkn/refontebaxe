<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['code_validation']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response([ 'users' =>
        UserResource::collection($users),
        'message' => 'Successful'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required|max:50',
            'age' => 'required|max:50',
            'job' => 'required|max:50',
            'salary' => 'required|50'
        ]);

        if($validator->fails()){
            return response(['error' => $validator->errors(),
            'Validation Error']);
        }

        $User = User::create($data);

        return response([ 'user' => new
        UserResource($User),
        'message' => 'Success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function show(User $User)
    {
        return response([ 'user' => new
        UserResource($User), 'message' => 'Success'], 200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $User)
    {
        $User->update($request->all());

        return response([ 'user' => new
        UserResource($User), 'message' => 'Success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\User $User
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(User $User)
    {
        $User->delete();

        return response(['message' => 'User deleted']);
    }

    public function code_validation(Request $request)
    {
        if ($request->method() == 'GET')
        {
            if (auth()->user()->code_validation) return redirect()->back();

            return view('auth.code_validation');
        }
        elseif ($request->method() == 'POST')
        {
            $validator = Validator::make($request->all(), [
                'code_validation'              => ['required', 'numeric', 'integer'],
                'code_validation_confirmation' => ['required', 'numeric', 'integer', 'same:code_validation'],
            ], ['code_validation_confirmation.same' => "Le code de validation ne correspond pas."]);

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            auth()->user()->update([
                'code_validation' => Hash::make($request->code_validation)
            ]);

            return redirect()->route('dashboard')->with('message', 'Votre code de validation a été initialisé avec succès.');
        }
        else
        {
            abort(405);
        }
    }

    public function change_password(Request $request)
    {
        if ($request->method() == 'GET')
        {
            return view('auth.change_password');
        }
        elseif ($request->method() == 'POST')
        {
            $validator = Validator::make($request->all(), [
                'old_password'              => ['required', 'string', 'min:8', 'max:255'],
                'new_password'              => ['required', 'string', 'min:8', 'max:255'],
                'new_password_confirmation' => ['required', 'string', 'min:8', 'max:255', 'same:new_password'],
            ], ['password_confirmation.same' => "Le mot de passe ne correspond pas."]);

            $validator->after(function ($validator) use ($request) {
                if (!Hash::check($request->old_password, auth()->user()->password))
                {
                    $validator->errors()->add('old_password', 'Le mot de passe actuel est incorrect.');
                }
            });

            if($validator->fails())
            {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            auth()->user()->update([
                'password' => Hash::make($request->new_password)
            ]);

            return redirect()->route('dashboard')->with('message', 'Votre mot de passe a été modifié avec succès.');
        }
    }
}
