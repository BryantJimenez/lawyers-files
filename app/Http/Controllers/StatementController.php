<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Company;
use App\Models\Setting;
use App\Models\Statement;
use App\Http\Requests\Statement\StatementStoreRequest;
use App\Http\Requests\Statement\StatementUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Storage;
use Auth;

class StatementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $setting=$this->setting();
        if (Auth::user()->hasRole('Cliente')) {
            $statements=Company::with(['statements', 'statements.type' => function($query) {
                $query->withTrashed();
            }, 'statements.company' => function($query) {
                $query->withTrashed();
            }])->whereHas('statements')->where('user_id', Auth::id())->orderBy('id', 'DESC')->get()->pluck('statements')->collapse()->unique('id')->values();
        } else {
            $statements=Statement::with(['type' => function($query) {
                $query->withTrashed();
            }, 'company' => function($query) {
                $query->withTrashed();
            }])->orderBy('id', 'DESC')->get();
        }
        return view('admin.statements.index', compact('setting', 'statements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $setting=$this->setting();
        $types=Type::where('state', '1')->get();
        if (Auth::user()->hasRole('Cliente')) {
            $companies=Company::where([['state', '1'], ['user_id', Auth::id()]])->get();
        } else {
            $companies=Company::where('state', '1')->get();
        }
        return view('admin.statements.create', compact('setting', 'types', 'companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatementStoreRequest $request) {
        $setting=$this->setting();
        config(['filesystems.disks.google.clientId' => $setting->google_drive_client_id, 'filesystems.disks.google.clientSecret' => $setting->google_drive_client_secret, 'filesystems.disks.google.refreshToken' => $setting->google_drive_refresh_token, 'filesystems.disks.google.folderId' => $setting->google_drive_folder_id]);

        $type=Type::where('slug', request('type_id'))->first();
        $company=Company::with(['user'])->where('slug', request('company_id'))->first();
        $data=array('name' => request('name'), 'description' => request('description'), 'type_id' => $type->id, 'company_id' => $company->id);
        $statement=Statement::create($data);

        if ($statement) {
            try {
                $path='/';
                $recursive=false;
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $directory=$contents->where('type', '=', 'dir')->where('filename', '=', $company['user']->slug)->first();

                $path='/'.$directory['path'].'/';
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $subdirectory=$contents->where('type', '=', 'dir')->where('filename', '=', $company->slug)->first();
                Storage::disk('google')->makeDirectory($directory['path'].'/'.$subdirectory['path'].'/'.$statement->slug);
            } catch (Exception $e) {
                Log::error("Google API Exception: ".$e->getMessage());
            }

            return redirect()->route('statements.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Registro exitoso', 'msg' => 'El caso ha sido registrado exitosamente.']);
        } else {
            return redirect()->route('statements.create')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Registro fallido', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.'])->withInputs();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Statement $statement) {
        if (Auth::user()->hasRole('Cliente') && !is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        $setting=$this->setting();
        return view('admin.statements.show', compact('setting', 'statement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Statement $statement) {
        if (Auth::user()->hasRole('Cliente') && !is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        $setting=$this->setting();
        $types=Type::where('state', '1')->get();
        if (Auth::user()->hasRole('Cliente')) {
            $companies=Company::where([['state', '1'], ['user_id', Auth::id()]])->get();
        } else {
            $companies=Company::where('state', '1')->get();
        }
        return view('admin.statements.edit', compact('setting', "statement", 'types', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StatementUpdateRequest $request, Statement $statement) {
        if (Auth::user()->hasRole('Cliente') && !is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        $type=Type::where('slug', request('type_id'))->first();
        $company=Company::where('slug', request('company_id'))->first();
        $data=array('name' => request('name'), 'description' => request('description'), 'state' => request('state'), 'type_id' => $type->id, 'company_id' => $company->id);
        $statement->fill($data)->save();        

        if ($statement) {
            return redirect()->route('statements.edit', ['statement' => $statement->slug])->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El caso ha sido editado exitosamente.']);
        } else {
            return redirect()->route('statements.edit', ['statement' => $statement->slug])->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Statement  $statement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Statement $statement)
    {
        if (Auth::user()->hasRole('Cliente') && !is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        $statement->delete();
        if ($statement) {
            return redirect()->route('statements.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Eliminación exitosa', 'msg' => 'El caso ha sido eliminado exitosamente.']);
        } else {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Eliminación fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    public function deactivate(Request $request, Statement $statement) {
        if (Auth::user()->hasRole('Cliente') && !is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        $statement->fill(['state' => "0"])->save();
        if ($statement) {
            return redirect()->route('statements.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El caso ha sido desactivado exitosamente.']);
        } else {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    public function activate(Request $request, Statement $statement) {
        if (Auth::user()->hasRole('Cliente') && !is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }
        
        $statement->fill(['state' => "1"])->save();
        if ($statement) {
            return redirect()->route('statements.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El caso ha sido activado exitosamente.']);
        } else {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }
}
