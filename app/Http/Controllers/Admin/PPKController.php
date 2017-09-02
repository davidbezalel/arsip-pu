<?php
/**
 * @author David Bezalel Laoli <davidbezalel94@gmail.com>
 *
 * @since 8/29/17
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PPK;
use Illuminate\Http\Request;

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

            $columns = ['no', 'ppkname', 'companyname', 'companyleader', 'created_at'];
            $where = array(
                ['companyname', 'LIKE', '%' . $request['search']['value'] . '%'],
                ['ppkname', 'LIKE', '%' . $request['search']['value'] . '%', 'OR'],
                ['companyleader', 'LIKE', '%' . $request['search']['value'] . '%', 'OR']
            );
            $ppks = $ppkModel->find_v2($where, true, ['*'], intval($request['length']), intval($request['start']), $columns[intval($request['order'][0]['column'])], $request['order'][0]['dir']);
            $number = intval($request['start']) + 1;
            foreach ($ppks as &$item) {
                $item['no'] = $number;
                $number++;
            }
            $response_json = array();
            $response_json['draw'] = $request['draw'];
            $response_json['data'] = $ppks;
            $response_json['recordsTotal'] = $ppkModel->getTableCount($where);
            $response_json['recordsFiltered'] = $ppkModel->getTableCount($where);
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
                'ppkname' => 'required|unique:ppk',
                'companyname' => 'required',
                'companyleader' => 'required|unique:ppk'
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
                $ppkModel::create($data);
                $this->response_json->status = true;
                $this->response_json->message = 'PPK added.';
            } catch (\Exception $e) {
                $this->response_json->message = $this->getServerError();
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
                'ppkname' => 'required',
                'companyname' => 'required',
                'companyleader' => 'required'
            );

            if (null !== $this->validate_v2($request, $rules)) {
                $this->response_json->message = $this->validate_V2($request, $rules);
                return $this->__json();
            }

            $where = array(
                ['ppkname', '=', $request['ppkname']],
                ['id', '<>', $request['id']]
            );
            if ($ppkModel->find_v2($where)) {
                $this->response_json->message = 'PPK name already taken';
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
}