<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Statement\Statement;
use App\Models\Statement\FileStatement;
use App\Http\Requests\Statement\StatementStoreRequest;
use App\Http\Requests\Statement\StatementUpdateRequest;
use Illuminate\Http\Request;
use Str;

class StatementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $statements=Statement::orderBy('id', 'DESC')->get();
        return view('admin.statements.index', compact('statements'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $companies=Company::where('state', '1')->get();
        return view('admin.statements.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StatementStoreRequest $request) {
        $company=Company::where('slug', request('company_id'))->first();
        $data=array('name' => request('name'), 'description' => request('description'), 'type' => request('type'), 'company_id' => $company->id);
        $statement=Statement::create($data);

        if ($statement) {
            // Mover archivos a carpeta statements y extraer nombre
            if ($request->has('files')) {
                foreach (request('files') as $file) {
                    FileStatement::create(['name' => $file, 'statement_id' => $statement->id])->save();
                }
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
        return view('admin.statements.show', compact('statement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Statement $statement) {
        $companies=Company::where('state', '1')->get();
        return view('admin.statements.edit', compact("statement", 'companies'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StatementUpdateRequest $request, Statement $statement) {
        $company=Company::where('slug', request('company_id'))->first();
        $data=array('name' => request('name'), 'description' => request('description'), 'type' => request('type'), 'state' => request('state'), 'company_id' => $company->id);
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
        $statement->delete();
        if ($statement) {
            return redirect()->route('statements.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Eliminación exitosa', 'msg' => 'El caso ha sido eliminado exitosamente.']);
        } else {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Eliminación fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    public function deactivate(Request $request, Statement $statement) {
        $statement->fill(['state' => "0"])->save();
        if ($statement) {
            return redirect()->route('statements.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El caso ha sido desactivado exitosamente.']);
        } else {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    public function activate(Request $request, Statement $statement) {
        $statement->fill(['state' => "1"])->save();
        if ($statement) {
            return redirect()->route('statements.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El caso ha sido activado exitosamente.']);
        } else {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    public function fileStore(Request $request) {
        if ($request->hasFile('file')) {
            $file=$request->file('file');
            $name=time().'_'.Str::slug(substr($file->getClientOriginalName(), 0, "-".strlen($file->getClientOriginalExtension())), "-").".".$file->getClientOriginalExtension();
            $file->move(public_path().'/admins/files/statements/', $name);
            
            return response()->json(['status' => true, 'name' => $name]);
        }

        return response()->json(['status' => false]);
    }

    public function fileEdit(Request $request) {
        $statement=Statement::where('slug', request('slug'))->first();
        if (!is_null($statement)) {
            if ($request->hasFile('file')) {
                $file=$request->file('file');
                $name=time().'_'.Str::slug(substr($file->getClientOriginalName(), 0, "-".strlen($file->getClientOriginalExtension())), "-").".".$file->getClientOriginalExtension();
                $file->move(public_path().'/admins/files/statements/', $name);

                $file=FileStatement::create(['name' => $name, 'statement_id' => $statement->id]);
                if ($file) {
                    return response()->json(['status' => true, 'name' => $name, 'slug' => $statement->slug]);
                }
            }
        }

        return response()->json(['status' => false]);
    }

    public function fileDestroy(Request $request) {
        $statement=Statement::where('slug', request('slug'))->first();
        if (!is_null($statement)) {
            $file=FileStatement::where('statement_id', $statement->id)->where('name', request('url'))->first();
            if (!is_null($file)) {
                $file->delete();

                if ($file) {
                    if (file_exists(public_path().'/admins/files/statements/'.request('url'))) {
                        unlink(public_path().'/admins/files/statements/'.request('url'));
                    }

                    return response()->json(['status' => true]);
                }
            }
        }

        return response()->json(['status' => false]);
    }
}