<?php

namespace App\Http\Controllers\Api;

use App\Models\Company;
use App\Models\Statement;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Statement\ApiStatementStoreRequest;
use App\Http\Requests\Api\Statement\ApiStatementUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Storage;
use Auth;
use Arr;
use Str;

class StatementController extends ApiController
{
    /**
    *
    * @OA\Get(
    *   path="/api/v1/statements",
    *   tags={"Statements"},
    *   summary="Get statements",
    *   description="Returns all statements",
    *   operationId="indexStatement",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="page",
    *       in="query",
    *       description="Number of page",
    *       required=false,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Show all statements.",
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
    public function index(Request $request) {
        $statements=Company::with(['statements.company.user.roles', 'statements.resolutions.files'])->whereHas('statements')->where('user_id', Auth::id())->orderBy('id', 'DESC')->get()->pluck('statements')->collapse()->unique('id')->values()->map(function($statement) {
            return $this->dataStatement($statement);
        });

    	$page=Paginator::resolveCurrentPage('page');
    	$pagination=new LengthAwarePaginator($statements, $total=count($statements), $perPage=15, $page, ['path' => Paginator::resolveCurrentPath(), 'pageName' => 'page']);
    	$pagination=Arr::collapse([$pagination->toArray(), ['code' => 200, 'status' => 'success']]);

    	return response()->json($pagination, 200);
    }

    /**
    *
    * @OA\Post(
    *   path="/api/v1/statements",
    *   tags={"Statements"},
    *   summary="Register statement",
    *   description="Create a new statement",
    *   operationId="storeStatement",
    *   security={
    *       {"bearerAuth": {}}
    *   },
    *   @OA\Parameter(
    *       name="name",
    *       in="query",
    *       description="Name of statement",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="description",
    *       in="query",
    *       description="Description of statement",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="type",
    *       in="query",
    *       description="Type of statement (1=Case, 2=Statement)",
    *       required=true,
    *       @OA\Schema(
    *           type="string",
    *           enum={"1", "2"}
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="company_id",
    *       in="query",
    *       description="Company ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Registered statement.",
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
    public function store(ApiStatementStoreRequest $request) {
        $company=Company::where('id', request('company_id'))->first();
        $data=array('name' => request('name'), 'description' => request('description'), 'type' => request('type'), 'company_id' => $company->id);
        $statement=Statement::create($data);

        if ($statement) {
            $path='/';
            $recursive=false;
            $contents=collect(Storage::disk('google')->listContents($path, $recursive));
            $directory=$contents->where('type', '=', 'dir')->where('filename', '=', $company->slug)->first();
            Storage::disk('google')->makeDirectory($directory['path'].'/'.$statement->slug);

            $statement=Statement::with(['company.user', 'resolutions.files'])->where('id', $statement->id)->first();
            $statement=$this->dataStatement($statement);
            return response()->json(['code' => 201, 'status' => 'success', 'message' => 'El caso ha sido registrado con éxito.', 'data' => $statement], 201);
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Get(
    *   path="/api/v1/statements/{id}",
    *   tags={"Statements"},
    *   summary="Get statement",
    *   description="Returns a single statement",
    *   operationId="showStatement",
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
    *       description="Show statement.",
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
    public function show(Statement $statement) {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }

    	$statement=$this->dataStatement($statement);
    	return response()->json(['code' => 200, 'status' => 'success', 'data' => $statement], 200);
    }

    /**
    *
    * @OA\Put(
    *   path="/api/v1/statements/{id}",
    *   tags={"Statements"},
    *   summary="Update statement",
    *   description="Update a single statement",
    *   operationId="updateStatement",
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
    *       description="Name of statement",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="description",
    *       in="query",
    *       description="Description of statement",
    *       required=true,
    *       @OA\Schema(
    *           type="string"
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="type",
    *       in="query",
    *       description="Type of statement (1=Case, 2=Statement)",
    *       required=true,
    *       @OA\Schema(
    *           type="string",
    *           enum={"1", "2"}
    *       )
    *   ),
    *   @OA\Parameter(
    *       name="company_id",
    *       in="query",
    *       description="Company ID",
    *       required=true,
    *       @OA\Schema(
    *           type="integer"
    *       )
    *   ),
    *   @OA\Response(
    *       response=200,
    *       description="Update statement.",
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
    public function update(ApiStatementUpdateRequest $request, Statement $statement) {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }

        $data=array('name' => request('name'), 'description' => request('description'), 'type' => request('type'), 'company_id' => request('company_id'));
        $statement->fill($data)->save();
        if ($statement) {
            $statement=Statement::with(['company.user', 'resolutions.files'])->where('id', $statement->id)->first();
            $statement=$this->dataStatement($statement);
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'El caso ha sido editado con éxito.', 'data' => $statement], 200);
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Delete(
    *   path="/api/v1/statements/{id}",
    *   tags={"Statements"},
    *   summary="Delete statement",
    *   description="Delete a single statement",
    *   operationId="destroyStatement",
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
    *       description="Delete statement.",
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
    public function destroy(Statement $statement)
    {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }

    	$statement->delete();
    	if ($statement) {
    		return response()->json(['code' => 200, 'status' => 'success', 'message' => 'El caso ha sido eliminado con éxito.'], 200);
    	}

    	return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Put(
    *   path="/api/v1/statements/{id}/deactivate",
    *   tags={"Statements"},
    *   summary="Deactivate statement",
    *   description="Deactivate a single statement",
    *   operationId="deactivateStatement",
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
    *       description="Deactivate statement.",
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
    public function deactivate(Request $request, Statement $statement) {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }

        $statement->fill(['state' => "0"])->save();
        if ($statement) {
            $statement=$this->dataStatement($statement);
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'El caso ha sido desactivado con éxito.', 'data' => $statement], 200);
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }

    /**
    *
    * @OA\Put(
    *   path="/api/v1/statements/{id}/activate",
    *   tags={"Statements"},
    *   summary="Activate statement",
    *   description="Activate a single statement",
    *   operationId="activateStatement",
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
    *       description="Activate statement.",
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
    public function activate(Request $request, Statement $statement) {
        if (!is_null($statement['company']) && $statement['company']->user_id!=Auth::id()) {
            return response()->json(['code' => 403, 'status' => 'error', 'message' => 'Este caso no pertenece a este usuario.'], 403);
        }

        $statement->fill(['state' => "1"])->save();
        if ($statement) {
            $statement=$this->dataStatement($statement);
            return response()->json(['code' => 200, 'status' => 'success', 'message' => 'El caso ha sido activado con éxito.', 'data' => $statement], 200);
        }

        return response()->json(['code' => 500, 'status' => 'error', 'message' => 'Ocurrió un error durante el proceso, intente nuevamente.'], 500);
    }
}
