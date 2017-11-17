<?php

/**
 * @todo this controller will handle admin authentication
 */

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;


class MainController extends Controller
{
    /**
     * @todo login admin
     *
     * Validate request body
     * @rules:
     * required
     * authentication rules
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if ($this->isPost()) {
            /**
             * @todo validate form request
             */
            $rules = array(
                'adminid' => 'required',
                'password' => 'required|min:6',
            );

            if (null !== $this->validate_v2($request, $rules)) {
                $this->response_json->message = $this->validate_V2($request, $rules);
                return $this->__json();
            }

            /**
             * @todo attempt credential data
             */
            $data = $request->all();

            if (Auth::attempt($data)) {
                $this->response_json->status = true;
            } else {
                $this->response_json->message = 'Check your credentials. Admin Id or password might be wrong.';
            }
            return $this->__json();
        }
        $styles = array();
        $styles[] = 'style.css';
        $styles[] = 'auth.css';

        $scripts = array();
        $scripts[] = 'auth.js';

        $this->data['styles'] = $styles;
        $this->data['scripts'] = $scripts;

        return view('admin.login')->with('data', $this->data);

    }

    /**
     * @todo register admin
     *
     * File ini harus dihapus jika yang menggunakan sistem adalah admin Satuan Kerja
     *
     * Validate request body
     * @rules:
     * required
     * adminid: unique
     * password and repassword must be same
     *
     * admin: insert
     *
     * @param Request $request
     * @return $this
     */
    public function register(Request $request)
    {
        if ($this->isPost()) {
            /**
             * @todo validate request body
             */
            $rules = array(
                'name' => 'required',
                'adminid' => 'required|unique:admin,adminid',
                'password' => 'required|min:6',
                'repassword' => 'required|min:6|same:password',
            );

            if (null !== $this->validate_v2($request, $rules)) {
                $this->response_json->message = $this->validate_V2($request, $rules);
                return $this->__json();
            }

            /**
             * @todo admin: insert
             */
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);

            try {
                Admin::create($data);
                $this->response_json->status = true;
            } catch (Exception $e) {
            }
            return $this->__json();
        }
        $styles = array();
        $styles[] = 'style.css';
        $styles[] = 'auth.css';

        $scripts = array();
        $scripts[] = 'auth.js';

        $this->data['styles'] = $styles;
        $this->data['scripts'] = $scripts;

        return view('admin.register')->with('data', $this->data);
    }

    /**
     * @todo logout
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        try {
            Auth::logout();
            $this->response_json->status = true;
        } catch (Exception $e) {
            $this->response_json->message = $e->getMessage();
        }
        return $this->__json();
    }
}
