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
* @OA\Tag(
*	name="Resolutions",
*	description="Resolutions endpoints"
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
		$data=$company->only("id", "name", "slug", "social_reason", "rfc", "address", "state", "user");

		return $data;
	}

	public function dataStatement($statement) {
		$statement->type=(!is_null($statement['type'])) ? $this->dataType($statement['type']) : [];
		$statement->company=(!is_null($statement['company'])) ? $this->dataCompany($statement['company']) : [];
		$statement->resolutions=$statement['resolutions']->map(function($resolution) {
			return $this->dataResolution($resolution);
		});
		$data=$statement->only("id", "name", "slug", "description", "type", "state", "company", "resolutions");

		return $data;
	}

	public function dataResolution($resolution) {
		$resolution->files=$resolution['files']->map(function($file) use ($resolution) {
			$data=array('id' => $file->id, 'file' => route('resolutions.show.files', ['statement' => $resolution['statement']->slug, 'resolution' => $resolution->slug, 'file' => $file->id]));
			return $data;
		});
		$data=$resolution->only("id", "name", "slug", "description", "date", "files");
		$data['date']=$data['date']->format('d-m-Y');

		return $data;
	}

	public function dataType($type) {
		$data=$type->only("id", "name", "slug", "state");

		return $data;
	}
}