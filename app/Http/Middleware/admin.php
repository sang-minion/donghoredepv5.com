<?php

namespace App\Http\Middleware;

use App\model\Module;
use App\model\Role;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->session()->has('user') ? $request->session()->get('user') : '';
        if (empty($user) || $user == '' || $user['user_role_id'] == \CGlobal::role_customer) {
            return redirect()->route('login');
        }else{
            if(!isset($_SESSION['user'])){
                session_start();
                $_SESSION['user'] = $user;
            }
        }
        if ($user['user_role_id'] == \CGlobal::role_admin) {
            $this->addModuleAction();
        } else {
            $this->checkMenuPermissionAction();
        }
        return $next($request);
    }

    public function addModuleAction()
    {
        $actionName = Route::current()->getActionName();
        if ($actionName != '') {
            $arrMisc = explode('Controller@', $actionName);
            $controller = isset($arrMisc[0]) ? $arrMisc[0] : '';
            $action = isset($arrMisc[1]) ? $arrMisc[1] : '';
            $result = Module::getModuleAction($controller);
            if (sizeof($result) > 0) {//Update
                $module_id = $result->module_id;
                $module_action = unserialize($result->module_action);
                if (!in_array($action, $module_action)) {
                    $module_action[] = $action;
                    $data = array(
                        'module_title' => $result->module_title,
                        'module_controller' =>  $controller,
                        'module_action' => serialize($module_action),
                        'module_status' => \CGlobal::status_show,
                    );
                    DB::table('module')->where('module_id', $module_id)->update($data);
                    Module::removeCache($module_id);
                }
            } else {//Add
                $module_action[] = $action;
                $data = array(
                    'module_title' => $controller,
                    'module_controller' => $controller,
                    'module_action' => serialize($module_action),
                    'module_status' => \CGlobal::status_show,
                );
                $id = DB::table('module')->insertGetId($data);
                Module::removeCache($id);
            }
        }
    }

    public function checkMenuPermissionAction()
    {
        if (Session::get('user')['user_role_id'] > 0) {
            $arrRole = Role::getById(Session::get('user')['user_role_id']);

            if (sizeof($arrRole) > 0) {
                if (isset($arrRole['role_permission']) && $arrRole['role_permission'] != '') {
                    $role_permission = unserialize($arrRole['role_permission']);
                    $actionName = Route::current()->getActionName();
                    $arrMisc = explode('Controller@', $actionName);
                    $controller = isset($arrMisc[0]) ? $arrMisc[0] : '';
                    $action = isset($arrMisc[1]) ? $arrMisc[1] : '';
                    if ($controller != '' && $action != '') {
                        if (isset($role_permission[$controller])) {
                            if (!isset($role_permission[$controller][$action])) {
                                echo '<div class="access" style="color: #ff0000; text-align: center;">' . \CGlobal::txt403 . ' <a href="javascript: history.go(-1)" >Click để quay lại</a></div>';
                                die;
                            }
                        } else {
                            echo '<div class="access" style="color: #ff0000; text-align: center;">' . \CGlobal::txt403 . ' <a href="javascript: history.go(-1)">Click để quay lại</a></div>';
                            die;
                        }
                    } else {
                        echo '<div class="access" style="color: #ff0000; text-align: center;">' . \CGlobal::txt403 . '</div>';
                        die;
                    }
                } else {
                    echo '<div class="access" style="color: #ff0000; text-align: center;">' . \CGlobal::txt403 . '</div>';
                    die;
                }
            } else {
                echo '<div class="access" style="color: #ff0000; text-align: center;">' . \CGlobal::txt403 . '</div>';
                die;
            }
        } else {
            echo '<div class="access" style="color: #ff0000; text-align: center;">' . \CGlobal::txt403 . '</div>';
            die;
        }
    }
}
