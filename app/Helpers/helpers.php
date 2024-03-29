<?php

function state($state, $theme=NULL) {
	if (!is_null($theme) && $theme=='invoice') {
		if ($state=='Inactivo') {
			return '<span class="badge badge-danger inv-status">'.$state.'</span>';
		} elseif ($state=='Activo') {
			return '<span class="badge badge-success inv-status">'.$state.'</span>';
		}
		return '<span class="badge badge-dark inv-status">'.$state.'</span>';
	}

	if ($state=='Inactivo') {
		return '<span class="badge badge-danger">'.$state.'</span>';
	} elseif ($state=='Activo') {
		return '<span class="badge badge-success">'.$state.'</span>';
	}
	return '<span class="badge badge-dark">'.$state.'</span>';
}

function type($type, $theme=NULL) {
	if (!is_null($theme) && $theme=='invoice') {
		if (!is_null($type)) {
			return '<span class="badge badge-primary inv-status">'.$type->name.'</span>';
		}
		return '<span class="badge badge-dark inv-status">Desconocido</span>';
	}

	if (!is_null($type)) {
		return '<span class="badge badge-primary">'.$type->name.'</span>';
	}
	return '<span class="badge badge-dark">Desconocido</span>';
}

function roleUser($user, $badge=true, $theme=NULL) {
	$num=1;
	$roles="";
	foreach ($user['roles'] as $rol) {
		if ($user->hasRole($rol->name)) {
			$roles.=($user['roles']->count()==$num) ? $rol->name : $rol->name."<br>";
			$num++;
		}
	}

	if (!is_null($user['roles']) && !empty($roles)) {
		if ($badge) {
			if (!is_null($theme) && $theme=='invoice') {
				return '<span class="badge badge-primary inv-status">'.$roles.'</span>';
			}

			return '<span class="badge badge-primary">'.$roles.'</span>';
		} else {
			return $roles;
		}
	} else {
		if ($badge) {
			if (!is_null($theme) && $theme=='invoice') {
				return '<span class="badge badge-dark inv-status">'.$roles.'</span>';
			}
			
			return '<span class="badge badge-dark">Desconocido</span>';
		} else {
			return 'Desconocido';
		}
	}
}

function active($path, $group=null) {
	if (is_array($path)) {
		foreach ($path as $url) {
			if (is_null($group)) {
				if (request()->is($url)) {
					return 'active';
				}
			} else {
				if (is_int(strpos(request()->path(), $url))) {
					return 'active';
				}
			}
		}
		return '';
	} else {
		if (is_null($group)) {
			return request()->is($path) ? 'active' : '';
		} else {
			return is_int(strpos(request()->path(), $path)) ? 'active' : '';
		}
	}
}

function menu_expanded($path, $group=null) {
	if (is_array($path)) {
		foreach ($path as $url) {
			if (is_null($group)) {
				if (request()->is($url)) {
					return 'true';
				}
			} else {
				if (is_int(strpos(request()->path(), $url))) {
					return 'true';
				}
			}
		}
		return 'false';
	} else {
		if (is_null($group)) {
			return request()->is($path) ? 'true' : 'false';
		} else {
			return is_int(strpos(request()->path(), $path)) ? 'true' : 'false';
		}
	}
}

function submenu($path, $action=null) {
	if (is_array($path)) {
		foreach ($path as $url) {
			if (is_null($action)) {
				if (request()->is($url)) {
					return 'class=active';
				}
			} else {
				if (is_int(strpos(request()->path(), $url))) {
					return 'show';
				}
			}
		}
		return '';
	} else {
		if (is_null($action)) {
			return request()->is($path) ? 'class=active' : '';
		} else {
			return is_int(strpos(request()->path(), $path)) ? 'show' : '';
		}
	}
}

function selectArray($arrays, $selectedItems) {
	$selects="";
	foreach ($arrays as $array) {
		$select="";
		if (count($selectedItems)>0) {
			foreach ($selectedItems as $selected) {
				if (is_object($selected) && $selected->slug==$array->slug) {
					$select="selected";
					break;
				} elseif ($selected==$array->slug) {
					$select="selected";
					break;
				}
			}
		}
		$selects.='<option value="'.$array->slug.'" '.$select.'>'.$array->name.'</option>';
	}
	return $selects;
}

function store_files($file, $file_name, $route) {
	$image=$file_name.".".$file->getClientOriginalExtension();
	if (file_exists(public_path().$route.$image)) {
		unlink(public_path().$route.$image);
	}
	$file->move(public_path().$route, $image);
	return $image;
}

function image_exist($file_route, $image, $user_image=false, $large=true) {
	if (file_exists(public_path().$file_route.$image)) {
		$img=asset($file_route.$image);
	} else {
		if ($user_image) {
			$img=asset("/admins/img/template/usuario.png");
		} else {
			if ($large) {
				$img=asset("/admins/img/template/imagen.jpg");
			} else {
				$img=asset("/admins/img/template/image.jpg");
			}
		}
	}

	return $img;
}