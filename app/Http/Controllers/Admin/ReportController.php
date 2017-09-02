<?php
/**
 * @author David Bezalel Laoli <davidbezalel94@gmail.com>
 *
 * @since 8/29/17
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\DocumentReport;
use App\Model\PeminjamanBerkas;
use App\Model\PengembalianBerkas;
use App\Model\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class ReportController extends Controller
{

    /**
     * @todo display all report based on ppk and paket
     *
     * @param Request $request
     * @return $this|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $styles = array();
        $scripts = array();
        $scripts[] = 'report.js';
        $this->data['styles'] = $styles;
        $this->data['scripts'] = $scripts;
        $this->data['controller'] = 'report';
        $this->data['title'] = 'Laporan';
        return view('admin.report.index')->with('data', $this->data);
    }

    /**
     * @todo get report depend on ppk_id and paket_id
     */
    public function get(Request $request)
    {
        if ($this->isPost()) {
            $reportmodel = new Report();
            $reports = $reportmodel->join('kontrakdetail', 'report.kontrakdetail_id', '=', 'kontrakdetail.id')
                ->join('subpaket', 'kontrakdetail.subpaket_id', '=', 'subpaket.id')
                ->join('kontrak', 'kontrakdetail.kontrak_id', '=', 'kontrak.id')
                ->join('report_classification', 'report.report_classification_id', '=', 'report_classification.id')
                ->join('report_param', 'report.report_param_id', '=', 'report_param.id')
                ->leftjoin('document_report', 'document_report.report_id', '=', 'report.id')
                ->leftjoin('peminjaman_berkas', 'peminjaman_berkas.document_report_id', '=', 'document_report.id')
                ->where('ppk_id', '=', $request['ppk_id'])->where('kontrak.paket_id', '=', $request['paket_id'])->get(['report.*', 'report_classification.name as report_classification_name', 'report_param.name as report_param_name', 'document_report.id as document_report_id', 'peminjaman_berkas.id as peminjaman_berkas_id', 'subpaket.id as subpaketid']);
            $this->response_json->status = true;
            $this->response_json->data = $reports;
            return $this->__json();
        }
    }

    /**
     * @todo handle ppk report their document to admin
     *
     * document_report: insert
     * report: update (is_reported = 1; is_available = 1)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function serahkanberkas(Request $request)
    {
        if ($this->isPost()) {
            try {
                DB::beginTransaction();

                /**
                 * @todo document_report: insert
                 */
                $data = array();
                $data['report_id'] = (int)$request['report_id'];
                $data['handled_by'] = Auth::user()->id;
                DocumentReport::create($data);

                /**
                 * @todo report: update (is_reported = 1; is_available = 1)
                 */
                $report = Report::find((int)$request['report_id']);
                $report['is_reported'] = 1;
                $report['is_available'] = 1;
                $report->update();

                DB::commit();

                $this->response_json->status = true;

            } catch (Exception $e) {
                DB::rollback();
                $this->response_json->message = $this->getServerError();
            }
            return $this->__json();
        }
    }


    /**
     * @todo handle peminjaman berkas
     *
     * peminjaman_berkas: insert
     * report: update (is_available = 0);
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pinjamberkas (Request $request) {
        if ($this->isPost()) {
            try {
                DB::beginTransaction();

                /**
                 * @todo peminjaman_berkas: insert
                 */
                $data = array();
                $data['document_report_id'] = $request['document_report_id'];
                $data['handled_by'] = Auth::user()->id;
                PeminjamanBerkas::create($data);

                /**
                 * @todo report: update (is_reported = 1; is_available = 0)
                 */
                $report = Report::find((int)$request['report_id']);
                $report['is_available'] = 0;
                $report->update();

                DB::commit();

                $this->response_json->status = true;

            } catch (Exception $e) {
                DB::rollback();
                $this->response_json->message = $this->getServerError();
            }
            return $this->__json();
        }
    }


    /**
     * @todo handle pengembalian berkas
     *
     * pengembalian_berkas: insert
     * report: update (is_available = 1)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function kembalikanberkas (Request $request) {
        if ($this->isPost()) {
            try {
                DB::beginTransaction();

                /**
                 * @todo pengembalian_berkas: insert
                 */
                $data = array();
                $data['peminjaman_berkas_id'] = $request['peminjaman_berkas_id'];
                $data['handled_by'] = Auth::user()->id;
                PengembalianBerkas::create($data);

                /**
                 * @todo report: update (is_reported = 1; is_available = 0)
                 */
                $report = Report::find((int)$request['report_id']);
                $report['is_available'] = 1;
                $report->update();

                DB::commit();

                $this->response_json->status = true;

            } catch (Exception $e) {
                DB::rollback();
                $this->response_json->message = $this->getServerError();
            }
            return $this->__json();
        }
    }
}