<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Statement\Statement;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class AdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $users=User::role(['Super Admin', 'Administrador'])->count();
        $customers=User::role('Cliente')->count();
        if (Auth::user()->hasRole('Cliente')) {
            $companies=Company::where('user_id', Auth::id())->count();
            $cases=Company::with(['statements'])->where('user_id', Auth::id())->whereHas('statements', function (Builder $query) {
                $query->where('type', '1');
            })->get()->pluck('statements')->collapse()->unique('id')->values()->map(function($case) {
                if ($case->type!='Caso') {
                    return NULL;
                }
                return $case;
            })->reject(function($case) {
                return is_null($case);
            })->values()->count();
            $statements=Company::with(['statements'])->where('user_id', Auth::id())->whereHas('statements', function (Builder $query) {
                $query->where('type', '2');
            })->get()->pluck('statements')->collapse()->unique('id')->values()->map(function($statement) {
                if ($statement->type!='Declaración') {
                    return NULL;
                }
                return $statement;
            })->reject(function($statement) {
                return is_null($statement);
            })->values()->count();
        } else {
            $companies=Company::count();
            $cases=Statement::where('type', '1')->count();
            $statements=Statement::where('type', '2')->count();
        }
        return view('admin.home', compact('users', 'customers', 'companies', 'cases', 'statements'));
    }

    public function profile() {
        return view('admin.profile');
    }

    public function profileEdit() {
        return view('admin.edit');
    }

    public function profileUpdate(ProfileUpdateRequest $request) {
        $user=User::where('slug', Auth::user()->slug)->firstOrFail();
        $data=array('name' => request('name'), 'lastname' => request('lastname'), 'phone' => request('phone'));

        if (!is_null(request('password'))) {
            $data['password']=Hash::make(request('password'));
        }

        $user->fill($data)->save();

        if ($user) {
            // Mover imagen a carpeta users y extraer nombre
            if ($request->hasFile('photo')) {
                $file=$request->file('photo');
                $photo=store_files($file, $user->slug, '/admins/img/users/');
                $user->fill(['photo' => $photo])->save();
                Auth::user()->photo=$photo;
            }
            Auth::user()->slug=$user->slug;
            Auth::user()->name=request('name');
            Auth::user()->lastname=request('lastname');
            Auth::user()->phone=request('phone');
            if (!is_null(request('password'))) {
                Auth::user()->password=Hash::make(request('password'));
            }
            return redirect()->route('profile.edit')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El perfil ha sido editado exitosamente.']);
        } else {
            return redirect()->route('profile.edit')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.'])->withInputs();
        }
    }

    public function emailVerifyAdmin(Request $request)
    {
        $count=User::where('email', request('email'))->count();
        if ($count>0) {
            return "false";
        } else {
            return "true";
        }
    }
}
