<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Http\Requests\Type\TypeStoreRequest;
use App\Http\Requests\Type\TypeUpdateRequest;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $setting=$this->setting();
    	$types=Type::orderBy('id', 'DESC')->get();
    	return view('admin.types.index', compact('setting', 'types'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $setting=$this->setting();
    	return view('admin.types.create', compact('setting'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeStoreRequest $request) {
    	$data=array('name' => request('name'));
    	$type=Type::create($data);

    	if ($type) {
    		return redirect()->route('types.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Registro exitoso', 'msg' => 'El tipo de caso ha sido registrado exitosamente.']);
    	} else {
    		return redirect()->route('types.create')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Registro fallido', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.'])->withInputs();
    	}
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Type $type) {
        $setting=$this->setting();
    	return view('admin.types.edit', compact('setting', 'type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TypeUpdateRequest $request, Type $type) {
    	$data=array('name' => request('name'), 'state' => request('state'));
    	$type->fill($data)->save();        

    	if ($type) {
    		return redirect()->route('types.edit', ['type' => $type->slug])->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El tipo de caso ha sido editado exitosamente.']);
    	} else {
    		return redirect()->route('types.edit', ['type' => $type->slug])->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
    	}
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Type  $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Type $type)
    {
    	$type->delete();
    	if ($type) {
    		return redirect()->route('types.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Eliminación exitosa', 'msg' => 'El tipo de caso ha sido eliminado exitosamente.']);
    	} else {
    		return redirect()->route('types.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Eliminación fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
    	}
    }

    public function deactivate(Request $request, Type $type) {
    	$type->fill(['state' => "0"])->save();
    	if ($type) {
    		return redirect()->route('types.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El tipo de caso ha sido desactivado exitosamente.']);
    	} else {
    		return redirect()->route('types.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
    	}
    }

    public function activate(Request $request, Type $type) {
    	$type->fill(['state' => "1"])->save();
    	if ($type) {
    		return redirect()->route('types.index')->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'El tipo de caso ha sido activado exitosamente.']);
    	} else {
    		return redirect()->route('types.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
    	}
    }
}
