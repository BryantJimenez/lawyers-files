<?php

namespace App\Http\Controllers;

use App\Models\Statement;
use App\Models\Resolution\Resolution;
use App\Models\Resolution\FileResolution;
use App\Http\Requests\Resolution\ResolutionStoreRequest;
use App\Http\Requests\Resolution\ResolutionUpdateRequest;
use Illuminate\Http\Request;
use Storage;
use Auth;
use Str;

class ResolutionController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Statement $statement) {
        return view('admin.resolutions.create', compact('statement'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResolutionStoreRequest $request, Statement $statement) {
        $data=array('name' => request('name'), 'description' => request('description'), 'date' => request('date'), 'statement_id' => $statement->id);
        $resolution=Resolution::create($data);

        if ($resolution) {
            $path='/';
            $recursive=false;
            $contents=collect(Storage::disk('google')->listContents($path, $recursive));
            $directory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement['company']->slug)->first();
            Storage::disk('google')->makeDirectory($directory['path'].'/'.$statement->slug);

            $path='/'.$directory['path'].'/';
            $contents=collect(Storage::disk('google')->listContents($path, $recursive));
            $subdirectory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement->slug)->first();

            // Mover archivos a carpeta statements y extraer nombre
            if ($request->has('files')) {
                foreach (request('files') as $file) {
                    FileResolution::create(['name' => $file, 'resolution_id' => $resolution->id])->save();
                    $filePath=public_path('admins/files/statements/'.$file);
                    Storage::disk('google')->put($directory['path'].'/'.$subdirectory['path'].'/'.$file, fopen($filePath, 'r+'));
                }
            }

            return redirect()->route('statements.show', ['statement' => $statement->slug])->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Registro exitoso', 'msg' => 'La resolution ha sido registrada exitosamente.']);
        } else {
            return redirect()->route('resolutions.create', ['statement' => $statement->slug])->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Registro fallido', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.'])->withInputs();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Statement $statement, Resolution $resolution) {
        if (Auth::user()->hasRole('Cliente') && !is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        return view('admin.resolutions.show', compact('statement', 'resolution'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Statement $statement, Resolution $resolution) {
        if (Auth::user()->hasRole('Cliente') && !is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        return view('admin.resolutions.edit', compact("statement", 'resolution'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ResolutionUpdateRequest $request, Statement $statement, Resolution $resolution) {
        if (Auth::user()->hasRole('Cliente') && !is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        $data=array('name' => request('name'), 'description' => request('description'), 'date' => request('date'));
        $resolution->fill($data)->save();        

        if ($resolution) {
            return redirect()->route('resolutions.edit', ['statement' => $statement->slug, 'resolution' => $resolution->slug])->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Edición exitosa', 'msg' => 'La resolución ha sido editada exitosamente.']);
        } else {
            return redirect()->route('resolutions.edit', ['statement' => $statement->slug, 'resolution' => $resolution->slug])->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Statement  $statement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Statement $statement, Resolution $resolution)
    {
        if (Auth::user()->hasRole('Cliente') && !is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return redirect()->route('statements.index')->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Edición fallida', 'msg' => 'Acceso no permitido.']);
        }

        $resolution->delete();
        if ($resolution) {
            return redirect()->route('statements.show', ['statement' => $statement->slug])->with(['alert' => 'sweet', 'type' => 'success', 'title' => 'Eliminación exitosa', 'msg' => 'El caso ha sido eliminado exitosamente.']);
        } else {
            return redirect()->route('statements.show', ['statement' => $statement->slug])->with(['alert' => 'lobibox', 'type' => 'error', 'title' => 'Eliminación fallida', 'msg' => 'Ha ocurrido un error durante el proceso, intentelo nuevamente.']);
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

    public function fileEdit(Request $request, Statement $statement, Resolution $resolution) {
        if ($request->hasFile('file')) {
            $file=$request->file('file');
            $name=time().'_'.Str::slug(substr($file->getClientOriginalName(), 0, "-".strlen($file->getClientOriginalExtension())), "-").".".$file->getClientOriginalExtension();
            $file->move(public_path().'/admins/files/statements/', $name);

            $file=FileResolution::create(['name' => $name, 'resolution_id' => $resolution->id]);
            if ($file) {
                $path='/';
                $recursive=false;
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $directory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement['company']->slug)->first();

                $path='/'.$directory['path'].'/';
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $subdirectory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement->slug)->first();

                $filePath=public_path('admins/files/statements/'.$name);
                Storage::disk('google')->put($directory['path'].'/'.$subdirectory['path'].'/'.$name, fopen($filePath, 'r+'));

                return response()->json(['status' => true, 'name' => $name, 'url' => asset('/admins/files/statements/'.$name), 'slug' => $statement->slug]);
            }
        }

        return response()->json(['status' => false]);
    }

    public function fileDestroy(Request $request, Statement $statement, Resolution $resolution) {
        $file=FileResolution::where('resolution_id', $resolution->id)->where('name', request('url'))->first();
        if (!is_null($file)) {
            $file->delete();

            if ($file) {
                if (file_exists(public_path().'/admins/files/statements/'.request('url'))) {
                    unlink(public_path().'/admins/files/statements/'.request('url'));
                }

                $path='/';
                $recursive=false;
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $directory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement['company']->slug)->first();

                $path='/'.$directory['path'].'/';
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $subdirectory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement->slug)->first();

                $path='/'.$directory['path'].'/'.$subdirectory['path'].'/';
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $file_drive=$contents->where('type', '=', 'file')->where('filename', '=', pathinfo($file->name, PATHINFO_FILENAME))->where('extension', '=', pathinfo($file->name, PATHINFO_EXTENSION))->first();
                Storage::disk('google')->delete($directory['path'].'/'.$subdirectory['path'].'/'.$file_drive['path']);

                return response()->json(['status' => true]);
            }
        }

        return response()->json(['status' => false]);
    }
}
