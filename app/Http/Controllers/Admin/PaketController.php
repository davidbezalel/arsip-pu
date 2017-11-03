<?php
/**
 * @author David Bezalel Laoli <davidbezalel94@gmail.com>
 *
 * @since 8/29/17
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Admin;
use App\Model\PPKAppointment;
use App\Model\Paket;
use App\Model\Report;
use App\Model\ReportParam;
use App\Model\ReportType;
use App\Model\SubPaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaketController extends Controller
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
            $paketModel = new Paket();
            $subpaketmodel = new SubPaket();
            $adminid = Auth::user()->id;
            $admin = Admin::find($adminid);

            $columns = ['no', 'title', 'startyear', 'paket.created_at'];
            $where = array(
                ['title', 'LIKE', '%' . $request['search']['value'] . '%'],
                ['admin.satker_id', '=', $admin->satker_id]
            );

            $join = array(
                ['admin', 'paket.admin_id', '=', 'admin.id']
            );

            if ($request['year'] > 0) {
                $where[] = ['startyear', '<=', (int)$request['year']];
                $where[] = ['endyear', '>=', (int)$request['year']];
            }
            $pakets = $paketModel->find_v2($where, true, ['*'], intval($request['length']), intval($request['start']), $columns[intval($request['order'][0]['column'])], $request['order'][0]['dir'], $join);
            $number = intval($request['start']) + 1;
            foreach ($pakets as &$item) {
                $item['no'] = $number;
                $item['subpaket'] = SubPaket::where('paket_id', $item['id'])->get();
                $number++;
            }
            $response_json = array();
            $response_json['draw'] = $request['draw'];
            $response_json['data'] = $pakets;
            $response_json['recordsTotal'] = $paketModel->getTableCount($where, $join);
            $response_json['recordsFiltered'] = $paketModel->getTableCount($where, $join);
            return $this->__json($response_json);
        }
        $styles = array();
        $scripts = array();
        $scripts[] = 'paket.js';
        $this->data['styles'] = $styles;
        $this->data['scripts'] = $scripts;
        $this->data['controller'] = 'paket';
        $this->data['title'] = 'Paket';
        return view('admin.paket.index')->with('data', $this->data);
    }

    /**
     * @todo insert paket
     *
     * validate request
     * @rules: all required
     *
     * paket: insert
     * subpaket: insert
     * report: insert
     *
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        if ($this->isPost()) {
            $paketModel = new Paket();

            /**
             * @todo validate request
             */
            $rules = array(
                'title' => 'required',
                'startyear' => 'required',
            );

            if (null !== $this->validate_v2($request, $rules)) {
                $this->response_json->message = $this->validate_V2($request, $rules);
                return $this->__json();
            }

            /**
             * @todo paket: insert
             */
            try {
                DB::beginTransaction();
                $data = $request->all();
                $data['ismultiyears'] = isset($request['ismultiyears']) ? 1 : 0;
                $data['yearsofwork'] = ($request['yearsofwork'] == "" || $data['ismultiyears'] == 0) ? 1 : $request['yearsofwork'];
                $data['endyear'] = $data['startyear'] + $data['yearsofwork'] - 1;
                $data['admin_id'] = Auth::user()->id;
                $paket = $paketModel::create($data);

                /**
                 * @todo subpaket: insert
                 *
                 * we will create 2 subpaket depend on reporttype ['Utama', 'MC']
                 */
                $data = array();
                $data['paket_id'] = $paket['id'];
                $data['title'] = $paket['title'];
                $data['reporttype_id'] = ReportType::getutamaid();
                SubPaket::create($data);

                if (isset($request['subpakettitle'])) {
                    $data['reporttype_id'] = ReportType::getmcid();
                    foreach ($request['subpakettitle'] as $key => $value) {
                        $data['title'] = $value;
                        SubPaket::create($data);
                    }
                } else {
                    $data['reporttype_id'] = ReportType::getmcid();
                    $data['title'] = $paket['title'];
                    SubPaket::create($data);
                }


                /**
                 * @todo report: insert
                 *
                 * create report depend on created subpaket
                 * for each subpaket we have to make a compatible report depend on report type
                 */
                $subpakets = SubPaket::where('paket_id', '=', $paket['id'])->get();
                $reportparams = ReportParam::all();
                $isutamaoncecreated = false;
                for ($i = 1; $i <= ($paket['endyear'] - $paket['startyear'] + 1) * 12; $i++) {
                    $isutamacreated = $isutamaoncecreated;
                    foreach ($subpakets as $subpaket) {
                        $report['subpaket_id'] = $subpaket['id'];
                        foreach ($reportparams as $reportparam) {
                            $report['reportparam_id'] = $reportparam['id'];
                            if ($subpaket['reporttype_id'] == $reportparam['reporttype_id']) {
                                if ($reportparam['reporttype_id'] == ReportType::getutamaid() && !$isutamacreated) {
                                    $report['title'] = 'Utama';
                                    $isutamaoncecreated = true;
                                    Report::create($report);
                                } else if ($reportparam['reporttype_id'] == ReportType::getmcid()) {
                                    $report['title'] = 'MC' . $i;
                                    Report::create($report);
                                }
                            }
                        }
                    }
                }

                DB::commit();
                $this->response_json->status = true;
                $this->response_json->message = 'Paket added.';
            } catch
            (\Exception $e) {
                DB::rollback();
                $this->response_json->message = $e->getMessage();
            }
            return $this->__json();
        }
    }

    /**
     * @todo update specific paket
     *
     * validate request body
     * @rules: all required
     *
     * paket: update
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function update(Request $request)
    {
        if ($this->isPost()) {

            $paketModel = new Paket();

            /**
             * @todo validate request
             */
            $rules = array(
                'title' => 'required',
                'year' => 'required'
            );

            if (null !== $this->validate_v2($request, $rules)) {
                $this->response_json->message = $this->validate_V2($request, $rules);
                return $this->__json();
            }

            /**
             * @todo ppk: update
             */
            try {
                $paket = $paketModel->find($request['id']);
                foreach ($paketModel->getFillable() as $field) {
                    $paket[$field] = $request[$field];
                }

                $paket->update();
                $this->response_json->status = true;
                $this->response_json->message = 'Paket updated.';
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
    public
    function delete(Request $request)
    {
        if ($this->isPost()) {
            try {
                Paket::find($request['id'])->delete();
                $this->response_json->status = true;
                $this->response_json->message = 'Paket deleted.';
            } catch (\Exception $e) {
                $this->response_json->message = $this->getServerError();
            }
            return $this->__json();
        }
    }

    /**
     * @todo return all paket
     * '
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function get()
    {
        if ($this->isPost()) {
            $paketmodel = new Paket();
            $adminid = Auth::user()->id;
            $admin = Admin::find($adminid);
            $pakets = $paketmodel->join('admin', 'admin.id', '=', 'paket.admin_id')
                ->join('admin', 'admin.id', '=', 'paket.admin_id')
                ->where('admin.satker_id', '=', $admin->satker_id)
                ->get(['*']);
            $this->response_json->status = true;
            $this->response_json->data = $pakets;
            return $this->__json();
        }
    }

    /**
     * @todo return all paket depend on ppk
     *
     * @param int $ppk_id
     * @return \Illuminate\Http\JsonResponse
     */
    public
    function getbyppk($ppk_id)
    {
        if ($this->isPost()) {
            $kontrakmodel = new PPKAppointment();
            $paketmodel = new Paket();
            $adminid = Auth::user()->id;
            $admin = Admin::find($adminid);
            $kontraks = $kontrakmodel->join('admin', 'admin.id', '=', 'paket.admin_id')
                ->where('ppk_id', '=', $ppk_id)
                ->where('admin.satker_id', '=', $admin->satker_id)
                ->get();
            foreach ($kontraks as $index => $value) {
                $paket = $paketmodel->find($value['paket_id']);
                $value['paket'] = $paket;
            }
            $this->response_json->data = $kontraks;
            $this->response_json->status = true;
            return $this->__json();
        }
    }

    public
    function getsubpaket($paket_id)
    {
        if ($this->isPost()) {
            $subpakets = SubPaket::where('paket_id', '=', $paket_id)->get();
            $this->response_json->data = $subpakets;
            $this->response_json->status = true;
            return $this->__json();
        }
    }

    public
    function getyear()
    {
        if ($this->isPost()) {
            $paketmodel = new Paket();
            $adminid = Auth::user()->id;
            $admin = Admin::find($adminid);
            $startyear = $paketmodel->join('admin', 'admin.id', '=', 'paket.admin_id')
                ->where('admin.satker_id', '=', $admin->satker_id)
                ->orderBy('startyear', 'asc')->get(['startyear'])->first();
            $endyear = $paketmodel->join('admin', 'admin.id', '=', 'paket.admin_id')
                ->where('admin.satker_id', '=', $admin->satker_id)
                ->orderBy('endyear', 'desc')->get(['endyear'])->first();
            $years['startyear'] = $startyear['startyear'];
            $years['endyear'] = $endyear['endyear'];
            $this->response_json->data = $years;
            $this->response_json->status = true;
            return $this->__json();
        }
    }

    public
    function getppkbyyear($year)
    {
        if ($this->isPost()) {
            $paketmodel = new Paket();
            $adminid = Auth::user()->id;
            $admin = Admin::find($adminid);
            $pakets = $paketmodel->join('admin', 'admin.id', '=', 'paket.admin_id')
                ->where('admin.satker_id', '=', $admin->satker_id)
                ->where('startyear', '<=', $year)->where('endyear', '>=', $year)->get(['paket.*', 'admin.id as adminid']);
            $this->response_json->data = $pakets;
            $this->response_json->status = true;
            return $this->__json();
        }
    }
}