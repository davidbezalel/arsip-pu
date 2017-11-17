<?php
/**
 * @author David Bezalel Laoli <davidbezalel94@gmail.com>
 *
 * @since 8/29/17
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\PPK;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PPKController extends Controller
{

    /**
     * @todo display a index view and return a json response of ppk data
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($this->isPost()) {
            $ppkModel = new PPK();
            $adminid = Auth::user()->id;
            $admin = Admin::find($adminid);

            $columns = ['no', 'ppkid', 'ppk.name', 'ppk.year', 'ppk.created_at'];
            $where = array(
                ['isactive', '=', '1',],
                ['admin.satker_id', '=', $admin->satker_id],
                ['ppkid', 'LIKE', '%' . $request['search']['value'] . '%', 'AND ('],
                ['ppk.name', 'LIKE', '%' . $request['search']['value'] . '%', 'OR'],
                ['ppk.id', '>', '0', ') AND']
            );

            $join = array(
                ['admin', 'admin.id', '=', 'ppk.admin_id']
            );

            if ($request['year'] > 0) {
                $where[] = ['year', '=', $request['year']];
            }

            $ppks = $ppkModel->find_v2($where, true, ['ppk.*', 'admin.satker_id', 'admin.name as adminname'], intval($request['length']), intval($request['start']), $columns[intval($request['order'][0]['column'])], $request['order'][0]['dir'], $join);
            $number = intval($request['start']) + 1;
            foreach ($ppks as &$item) {
                $item['no'] = $number;
                $number++;
            }
            $response_json = array();
            $response_json['draw'] = $request['draw'];
            $response_json['data'] = $ppks;
            $response_json['recordsTotal'] = $ppkModel->getTableCount($where, $join);
            $response_json['recordsFiltered'] = $ppkModel->getTableCount($where, $join);
            return $this->__json($response_json);
        }
        $styles = array();
        $scripts = array();
        $scripts[] = 'ppk.js';
        $this->data['styles'] = $styles;
        $this->data['scripts'] = $scripts;
        $this->data['controller'] = 'ppk';
        $this->data['title'] = 'PPK';
        return view('admin.ppk.index')->with('data', $this->data);
    }

    /**
     * @todo insert ppk
     *
     * validate request
     * @rules: all required
     *
     * ppk: insert
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        if ($this->isPost()) {

            $ppkModel = new PPK();

            /**
             * @todo validate request
             */
            $rules = array(
                'ppkid' => 'required|unique:ppk',
                'name' => 'required|unique:ppk'
            );

            if (null !== $this->validate_v2($request, $rules)) {
                $this->response_json->message = $this->validate_V2($request, $rules);
                return $this->__json();
            }

            /**
             * @todo ppk: insert
             */
            try {
                $data = $request->all();
                $data['admin_id'] = Auth::user()->id;
                $ppkModel::create($data);
                $this->response_json->status = true;
                $this->response_json->message = 'PPK added.';
            } catch (\Exception $e) {
                $this->response_json->message = $e->getMessage();
            }
            return $this->__json();
        }
    }

    /**
     * @todo update specific ppk
     *
     * validate request body
     * @rules: all required
     *
     * ppk: update
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if ($this->isPost()) {

            $ppkModel = new PPK();

            /**
             * @todo validate request
             */
            $rules = array(
                'ppkid' => 'required',
                'name' => 'required'
            );

            if (null !== $this->validate_v2($request, $rules)) {
                $this->response_json->message = $this->validate_V2($request, $rules);
                return $this->__json();
            }

            $where = array(
                ['ppkid', '=', $request['ppkid']],
                ['id', '<>', $request['id']]
            );
            if ($ppkModel->find_v2($where)) {
                $this->response_json->message = 'PPK Name already taken';
                return $this->__json();
            }

            /**
             * @todo ppk: update
             */
            try {
                $ppk = $ppkModel->find($request['id']);
                foreach ($ppkModel->getFillable() as $field) {
                    $ppk[$field] = $request[$field];
                }   
                $ppk['year'] = $request['yearupdate'];
                $ppk['admin_id'] = Auth::user()->id;
                $ppk->update();
                $this->response_json->status = true;
                $this->response_json->message = 'PPK updated.';
            } catch (\Exception $e) {
                $this->response_json->message = $this->getServerError();
            }
            return $this->__json();
        }
    }

    /**
     * @todo delete specific ppk
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        if ($this->isPost()) {
            try {
                PPK::find($request['id'])->delete();
                $this->response_json->status = true;
                $this->response_json->message = 'PPK deleted.';
            } catch (\Exception $e) {
                $this->response_json->message = $this->getServerError();
            }
            return $this->__json();
        }
    }


    /**
     * @todo return all ppk
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get() {
        if ($this->isPost()) {
            $ppks = PPK::all();
            $this->response_json->data = $ppks;
            $this->response_json->status = true;
            return $this->__json();
        }
    }

    /**
     * @todo return all year of ppk
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getyear() {
        if ($this->isPost()) {
            $years = PPK::all()->groupBy('year');
            $this->response_json->data = $years;
            $this->response_json->status = true;
            return $this->__json();
        }
    }
}