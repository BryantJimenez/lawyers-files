<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
* @OA\Info(
*	title="Lawyers Files",
*	version="1.0",
*   @OA\License(
*   	name="Apache 2.0",
*       url="http://www.apache.org/licenses/LICENSE-2.0.html"
*   )
* )
*
* @OA\Server(url="http://localhost:8000")
* @OA\Server(url="http://lawyers-files.otterscompany.com")
*
* @OA\Tag(
*	name="Login",
*	description="Login users endpoints"
* )
*
* @OA\Tag(
*	name="Logout",
*	description="Logout users endpoint"
* )
*
* @OA\Tag(
*	name="Forgot Password",
*	description="Forgot password users endpoint"
* )
*
* @OA\Tag(
*	name="Reset Password",
*	description="Reset password users endpoint"
* )
*
* @OA\Tag(
*	name="Profile",
*	description="User profile endpoints"
* )
*
* @OA\Tag(
*	name="Companies",
*	description="Companies endpoints"
* )
*
* @OA\Tag(
*	name="Statements",
*	description="Statements endpoints"
* )
*
* @OA\SecurityScheme(
*	securityScheme="bearerAuth",
*   in="header",
*   name="bearerAuth",
*   type="http",
*   scheme="bearer",
*   bearerFormat="JWT"
* )
*/
class ApiController extends Controller
{
	public function dataUser($user) {
		$user->rol=roleUser($user, false);
		$user->photo=(!is_null($user->photo)) ? $user->photo : '';
		$user->phone=(!is_null($user->phone)) ? $user->phone : '';
		$data=$user->only("id", "name", "lastname", "slug", "photo", "phone", "email", "state", "rol");

		return $data;
	}

	public function dataCompany($company) {
		$company->user=(!is_null($company['user'])) ? $this->dataUser($company['user']) : [];
		$data=$company->only("id", "name", "slug", "social_reason", "address", "state", "user");

		return $data;
	}

	public function dataStatement($statement) {
		$statement->company=(!is_null($statement['company'])) ? $this->dataCompany($statement['company']) : [];
		$statement->files=$statement['files']->map(function($file) {
			$data=array('id' => $file->id, 'file' => env('APP_URL').'/admins/files/statements/'.$file->name);
			return $data;
		});
		$data=$statement->only("id", "name", "slug", "description", "type", "state", "company", "files");

		return $data;
	}
}