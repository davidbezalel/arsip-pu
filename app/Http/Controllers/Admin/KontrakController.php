<?php
/**
 * @author David Bezalel Laoli <davidbezalel94@gmail.com>
 *
 * @since 8/29/17
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Kontrak;
use App\Model\Report;
use App\Model\ReportClassification;
use App\Model\ReportParam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KontrakController extends Controller
{

    /**
     * @todo display a index view and return a json response of kontrak data
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($this->isPost()) {
            $kontrakModel = new Kontrak();

            $columns = ['no', 'ppk_id', 'paket_id', 'kontrak.created_at'];
            $where = array(
                ['ppk.ppkname', 'LIKE', '%' . $request['search']['value'] . '%'],
                ['ppk.companyname', 'LIKE', '%' . $request['search']['value'] . '%', 'OR'],
                ['ppk.companyleader', 'LIKE', '%' . $request['search']['value'] . '%', 'OR'],
                ['paket.title', 'LIKE', '%' . $request['search']['value'] . '%', 'OR'],
            );

            $join = array(
                ['ppk', 'ppk.id', '=', 'kontrak.ppk_id'],
                ['paket', 'paket.id', '=', 'kontrak.paket_id']
            );

            $kontraks = $kontrakModel->find_v2($where, true, ['kontrak.*', 'paket.*', 'ppk.*'], intval($request['length']), intval($request['start']), $columns[intval($request['order'][0]['column'])], $request['order'][0]['dir'], $join);
            $number = intval($request['start']) + 1;
            foreach ($kontraks as &$item) {
                $item['no'] = $number;
                $number++;
            }
            $response_json = array();
            $response_json['draw'] = $request['draw'];
            $response_json['data'] = $kontraks;
            $response_json['recordsTotal'] = $kontrakModel->getTableCount($where, $join);
            $response_json['recordsFiltered'] = $kontrakModel->getTableCount($where, $join);
            return $this->__json($response_json);
        }
        $styles = array();
        $scripts = array();
        $scripts[] = 'kontrak.js';
        $this->data['styles'] = $styles;
        $this->data['scripts'] = $scripts;
        $this->data['controller'] = 'kontrak';
        $this->data['title'] = 'Kontrak';
        return view('admin.kontrak.index')->with('data', $this->data);
    }

    /**
     * @todo insert kontrak
     *
     * validate request
     * @rules: all required
     *
     * kontrak: insert

     * get all classification
     * get all param depend on classification
     * report: insert
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        if ($this->isPost()) {

            $kontrakModel = new Kontrak();

            /**
             * @todo validate request
             */
            $rules = array(
                'ppk_id' => 'required',
                'paket_id' => 'required|unique:kontrak'
            );

            if (null !== $this->validate_v2($request, $rules)) {
                $this->response_json->message = $this->validate_V2($request, $rules);
                return $this->__json();
            }

            /**
             * @todo kontrak: insert
             */
            try {
                DB::beginTransaction();

                $data = $request->all();
                $kontrak = $kontrakModel::create($data);

                $data = array(
                    'kontrak_id'=> $kontrak['id'],
                );

                $reportclassification = ReportClassification::all();
                $reportparam = ReportParam::all();

                foreach ($reportclassification as $index => $value) {
                    foreach ($reportparam as $_index => $_value) {
                        $valid = false;
                        if ($value['name'] == ReportClassification::$pokok && $_value['type'] == ReportParam::$pokok) {
                            $data['report_classification_id'] = $value['id'];
                            $data['report_param_id'] = $_value['id'];
                            $valid = true;
                        } else if ($value['name'] != ReportClassification::$pokok && $_value['type'] == ReportParam::$bulanan) {
                            $data['report_classification_id'] = $value['id'];
                            $data['report_param_id'] = $_value['id'];
                            $valid = true;
                        }
                        if ($valid) {
                            Report::create($data);
                        }
                    }
                }

                DB::commit();

                $this->response_json->status = true;
                $this->response_json->message = 'Kontrak added.';
            } catch (\Exception $e) {
                DB::rollback();
                $this->response_json->message = $this->getServerError();
            }
            return $this->__json();
        }
    }

    /**
     * @todo update specific kontrak
     *
     * validate request body
     * @rules: all required
     *
     * kontrak: update
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        if ($this->isPost()) {

            $kontrakModel = new PPK();

            /**
             * @todo validate request
             */
            $rules = array(
                'kontrakname' => 'required',
                'companyname' => 'required',
                'companyleader' => 'required'
            );

            if (null !== $this->validate_v2($request, $rules)) {
                $this->response_json->message = $this->validate_V2($request, $rules);
                return $this->__json();
            }

            $where = array(
                ['kontrakname', '=', $request['kontrakname']],
                ['id', '<>', $request['id']]
            );
            if ($kontrakModel->find_v2($where)) {
                $this->response_json->message = 'PPK name already taken';
                return $this->__json();
            }

            /**
             * @todo kontrak: update
             */
            try {
                $kontrak = $kontrakModel->find($request['id']);
                foreach ($kontrakModel->getFillable() as $field) {
                    $kontrak[$field] = $request[$field];
                }

                $kontrak->update();
                $this->response_json->status = true;
                $this->response_json->message = 'PPK updated.';
            } catch (\Exception $e) {
                $this->response_json->message = $this->getServerError();
            }
            return $this->__json();
        }
    }

    /**
     * @todo delete specific kontrak
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
}