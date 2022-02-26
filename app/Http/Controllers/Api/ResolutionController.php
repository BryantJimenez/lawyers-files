<?php

namespace App\Http\Controllers\Api;

use App\Models\Statement;
use App\Models\Resolution\Resolution;
use App\Models\Resolution\FileResolution;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Resolution\ApiResolutionStoreRequest;
use App\Http\Requests\Api\Resolution\ApiResolutionUpdateRequest;
use App\Http\Requests\Api\Resolution\ApiUploadFileResolutionStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Exception;
use Storage;
use Auth;
use Arr;
use Str;

class ResolutionController extends ApiController
{
    /**
    *
    * @OA\Get(
    *   path="/api/v1/statements/{id}/resolutions",
    *   tags={"Resolutions"},
    *   summary="Get resolutions of statement",
    *   description="Returns all resolutions of a single statement",
    *   operationId="indexResolution",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="id",
    *       in="path",
    *       description="Search for ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Show all resolutions of a single statement.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   )
    * )
    */
    public function index(Statement $statement) {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }

        $resolutions=$statement['resolutions']->map(function($resolution) {
            return $this->dataResolution($resolution);
        });

    	return response()->json(['code' => 200, 'status' => 'success', 'data' => $resolutions], 200);
    }

    /**
    *
    * @OA\Post(
    *   path="/api/v1/statements/{id}/resolutions",
    *   tags={"Resolutions"},
    *   summary="Register resolution",
    *   description="Create a new resolution",
    *   operationId="storeResolution",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="id",
    *       in="path",
    *       description="Search for ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="name",
    *       in="query",
    *       description="Name of resolution",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="description",
    *       in="query",
    *       description="Description of resolution",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="date",
    *       in="query",
    *       description="Date of resolution (format=d-m-Y)",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Registered resolution.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=422,
    *       description="Data not valid."
    *   ),
    *   @OA\Response(
    *       response=500,
    *       description="An error occurred during the process."
    *   )
    * )
    */
    public function store(ApiResolutionStoreRequest $request, Statement $statement) {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }
        
        $data=array('name' => request('name'), 'description' => request('description'), 'date' => request('date'), 'statement_id' => $statement->id);
        $resolution=Resolution::create($data);

        if ($resolution) {
            $resolution=Resolution::with(['statement.company.user', 'files'])->where('id', $resolution->id)->first();
            $resolution=$this->dataResolution($resolution);
            return response()->json(['code' => 201, 'status' => 'success', 'message' => 'La resolución ha sido registrada con éxito.', 'data' => $resolution], 201);
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Get(
    *   path="/api/v1/statements/{statement_id}/resolutions/{resolution_id}",
    *   tags={"Resolutions"},
    *   summary="Get a resolution",
    *   description="Returns a single resolution",
    *   operationId="showResolution",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="statement_id",
    *       in="path",
    *       description="Statement ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="resolution_id",
    *       in="path",
    *       description="Resolution ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Show resolution.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=404,
    *       description="No results found."
    *   )
    * )
    */
    public function show(Statement $statement, Resolution $resolution) {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }

        if ($statement->id!=$resolution->statement_id) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Esta resolución no pertenece a este caso.'], 403);
        }

    	$resolution=$this->dataResolution($resolution);
    	return response()->json(['code' => 200, 'status' => 'success', 'data' => $resolution], 200);
    }

    /**
    *
    * @OA\Put(
    *   path="/api/v1/statements/{statement_id}/resolutions/{resolution_id}",
    *   tags={"Resolutions"},
    *   summary="Update resolution",
    *   description="Update a single resolution",
    *   operationId="updateResolution",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="statement_id",
    *       in="path",
    *       description="Statement ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="resolution_id",
    *       in="path",
    *       description="Resolution ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="name",
    *       in="query",
    *       description="Name of resolution",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="description",
    *       in="query",
    *       description="Description of resolution",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="date",
    *       in="query",
    *       description="Date of resolution (format=d-m-Y)",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Update resolution.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=422,
    *       description="Data not valid."
    *   ),
    *   @OA\Response(
    *       response=500,
    *       description="An error occurred during the process."
    *   )
    * )
    */
    public function update(ApiResolutionUpdateRequest $request, Statement $statement, Resolution $resolution) {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }

        if ($statement->id!=$resolution->statement_id) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Esta resolución no pertenece a este caso.'], 403);
        }

        $data=array('name' => request('name'), 'description' => request('description'), 'date' => request('date'));
        $resolution->fill($data)->save();
        if ($resolution) {
            $resolution=Resolution::with(['statement.company.user', 'files'])->where('id', $resolution->id)->first();
            $resolution=$this->dataResolution($resolution);
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'La resolución ha sido editada con éxito.', 'data' => $resolution], 200);
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Post(
    *   path="/api/v1/statements/{statement_id}/resolutions/{resolution_id}/files",
    *   tags={"Resolutions"},
    *   summary="Upload file a resolution",
    *   description="Upload file a single resolution",
    *   operationId="uploadFileResolution",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="statement_id",
    *       in="path",
    *       description="Statement ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="resolution_id",
    *       in="path",
    *       description="Resolution ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\RequestBody(
    *       required=true,
    *       @OA\MediaType(
    *           mediaType="multipart/form-data",
    *           @OA\Schema(
    *               @OA\Property(
    *                   property="file",
    *                   description="File (PDF) of resolution",
    *                   type="file"
    *               ),
    *               required={"file"}
    *           )
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Upload file a resolution.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=404,
    *       description="No results found."
    *   ),
    *   @OA\Response(
    *       response=500,
    *       description="An error occurred during the process."
    *   )
    * )
     */
    public function uploadFile(ApiUploadFileResolutionStoreRequest $request, Statement $statement, Resolution $resolution) {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }

        if ($statement->id!=$resolution->statement_id) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Esta resolución no pertenece a este caso.'], 403);
        }

        if ($request->hasFile('file')) {
            $file=$request->file('file');
            $name=time().'_'.Str::slug(substr($file->getClientOriginalName(), 0, "-".strlen($file->getClientOriginalExtension())), "-").".".$file->getClientOriginalExtension();
            $file->move(public_path().'/admins/files/statements/', $name);

            try {
                $path='/';
                $recursive=false;
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $directory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement['company']['user']->slug)->first();

                $path='/'.$directory['path'].'/';
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $subdirectory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement['company']->slug)->first();

                $path='/'.$directory['path'].'/'.$subdirectory['path'].'/';
                $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                $subsubdirectory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement->slug)->first();

                $filePath=public_path('admins/files/statements/'.$name);
                Storage::disk('google')->put($directory['path'].'/'.$subdirectory['path'].'/'.$subsubdirectory['path'].'/'.$name, fopen($filePath, 'r+'));

                if (file_exists(public_path().'/admins/files/statements/'.$name)) {
                    unlink(public_path().'/admins/files/statements/'.$name);
                }
            } catch (Exception $e) {
                Log::error("Google API Exception: ".$e->getMessage());
                return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
            }

            $file=FileResolution::create(['name' => $name, 'resolution_id' => $resolution->id]);
            if ($file) {
                $resolution=Resolution::with(['statement.company.user', 'files'])->where('id', $resolution->id)->first();
                $resolution=$this->dataResolution($resolution);
                return response()->json(['code' => 200, 'status' => 'success', 'message' => 'El archivo ha sido agregado con éxito.', 'data' => $resolution], 200);
            }
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Post(
    *   path="/api/v1/statements/{statement_id}/resolutions/{resolution_id}/files/{file_id}",
    *   tags={"Resolutions"},
    *   summary="Destroy file a resolution",
    *   description="Destroy file a single resolution",
    *   operationId="destroyFileResolution",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="statement_id",
    *       in="path",
    *       description="Statement ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="resolution_id",
    *       in="path",
    *       description="Resolution ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="file_id",
    *       in="path",
    *       description="File ID of resolution",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Destroy file a resolution.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=404,
    *       description="No results found."
    *   ),
    *   @OA\Response(
    *       response=500,
    *       description="An error occurred during the process."
    *   )
    * )
     */
    public function destroyFile(Request $request, Statement $statement, Resolution $resolution, FileResolution $file) {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }

        if ($statement->id!=$resolution->statement_id) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Esta resolución no pertenece a este caso.'], 403);
        }

        $file=FileResolution::where([['id', $file->id], ['resolution_id', $resolution->id]])->first();
        if (!is_null($file)) {
            $file->delete();

            if ($file) {
                if (file_exists(public_path().'/admins/files/statements/'.$file->name)) {
                    unlink(public_path().'/admins/files/statements/'.$file->name);
                }

                try {
                    $path='/';
                    $recursive=false;
                    $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                    $directory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement['company']['user']->slug)->first();

                    $path='/'.$directory['path'].'/';
                    $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                    $subdirectory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement['company']->slug)->first();

                    $path='/'.$directory['path'].'/'.$subdirectory['path'].'/';
                    $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                    $subsubdirectory=$contents->where('type', '=', 'dir')->where('filename', '=', $statement->slug)->first();

                    $path='/'.$directory['path'].'/'.$subdirectory['path'].'/'.$subsubdirectory['path'].'/';
                    $contents=collect(Storage::disk('google')->listContents($path, $recursive));
                    $file_drive=$contents->where('type', '=', 'file')->where('filename', '=', pathinfo($file->name, PATHINFO_FILENAME))->where('extension', '=', pathinfo($file->name, PATHINFO_EXTENSION))->first();
                    Storage::disk('google')->delete($directory['path'].'/'.$subdirectory['path'].'/'.$subsubdirectory['path'].'/'.$file_drive['path']);
                } catch (Exception $e) {
                    Log::error("Google API Exception: ".$e->getMessage());
                }

                $resolution=Resolution::with(['statement.company.user', 'files'])->where('id', $resolution->id)->first();
                $resolution=$this->dataResolution($resolution);
                return response()->json(['code' => 200, 'status' => 'success', 'message' => 'El archivo ha sido eliminado con éxito.', 'data' => $resolution], 200);
            }
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Delete(
    *   path="/api/v1/statements/{statement_id}/resolutions/{resolution_id}",
    *   tags={"Resolutions"},
    *   summary="Delete resolution",
    *   description="Delete a single resolution",
    *   operationId="destroyResolution",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="statement_id",
    *       in="path",
    *       description="Statement ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="resolution_id",
    *       in="path",
    *       description="Resolution ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Delete resolution.",
    *       @OA\MediaType(
    *           mediaType="application/json"
    *       )
    *   ),
    *   @OA\Response(
    *       response=401,
    *       description="Not authenticated."
    *   ),
    *   @OA\Response(
    *       response=403,
    *       description="Forbidden."
    *   ),
    *   @OA\Response(
    *       response=404,
    *       description="No results found."
    *   ),
    *   @OA\Response(
    *       response=500,
    *       description="An error occurred during the process."
    *   )
    * )
     */
    public function destroy(Statement $statement, Resolution $resolution)
    {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }

        if ($statement->id!=$resolution->statement_id) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Esta resolución no pertenece a este caso.'], 403);
        }

    	$resolution->delete();
    	if ($resolution) {
    		return response()->json(['code' => 200, 'status' => 'success', 'message' => 'La resolución ha sido eliminada con éxito.'], 200);
    	}

    	return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }
}
