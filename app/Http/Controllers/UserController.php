<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendEmailRegister;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $users=User::role(['Super Admin', 'Administrador'])->with(['roles'])->orderBy('id', 'DESC')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        if (Auth::user()->hasRole('Super Admin')) {
            $roles=Role::where('name', '!=', 'Cliente')->get()->pluck('name');
        } else {
            $roles=Role::where([['name', '!=', 'Super Admin'], ['name', '!=', 'Cliente']])->get()->pluck('name');
        }
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserStoreRequest $request) {
        $data=array('name' => request('name'), 'lastname' => request('lastname'), 'phone' => request('phone'), 'email' => request('email'), 'password' => Hash::make(request('password')));
        $user=User::create($data);

        if ($user) {
            $user->assignRole(request('type'));

            // Mover imagen a carpeta users y extraer nombre
            if ($request->hasFile('photo')) {
                $file=$request->file('photo');
                $photo=store_files($file, $user->slug, '/admins/img/users/');
                $user->fill(['photo' => $photo])->save();
            }

            SendEmailRegister::dispatch($user->slug);
            return redirect()->route('users.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Registro exitoso', 'msg' => 'El usuario ha sido registrado exitosamente.']);
        } else {
            return redirect()->route('users.create')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Registro fallido', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.'])->withInputs();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user) {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user) {
        if (Auth::user()->id==$user->id) {
            return redirect()->route('profile.edit');
        }

        if (Auth::user()->hasRole('Super Admin')) {
            $roles=Role::where('name', '!=', 'Cliente')->get()->pluck('name');
        } else {
            $roles=Role::where([['name', '!=', 'Super Admin'], ['name', '!=', 'Cliente']])->get()->pluck('name');
        }
        return view('admin.users.edit', compact("user", "roles"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, User $user) {
        $data=array('name' => request('name'), 'lastname' => request('lastname'), 'state' => request('state'), 'phone' => request('phone'));
        $user->fill($data)->save();        

        if ($user) {
            $user->syncRoles([request('type')]);

            // Mover imagen a carpeta users y extraer nombre
            if ($request->hasFile('photo')) {
                $file=$request->file('photo');
                $photo=store_files($file, $user->slug, '/admins/img/users/');
                $user->fill(['photo' => $photo])->save();
            }

            return redirect()->route('users.edit', ['user' => $user->slug])->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El usuario ha sido editado exitosamente.']);
        } else {
            return redirect()->route('users.edit', ['user' => $user->slug])->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        if ($user) {
            return redirect()->route('users.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Eliminación exitosa', 'msg' => 'El usuario ha sido eliminado exitosamente.']);
        } else {
            return redirect()->route('users.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Eliminación fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    public function deactivate(Request $request, User $user) {
        $user->fill(['state' => "0"])->save();
        if ($user) {
            return redirect()->route('users.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El usuario ha sido desactivado exitosamente.']);
        } else {
            return redirect()->route('users.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    public function activate(Request $request, User $user) {
        $user->fill(['state' => "1"])->save();
        if ($user) {
            return redirect()->route('users.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El usuario ha sido activado exitosamente.']);
        } else {
            return redirect()->route('users.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }
}
