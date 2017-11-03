<?php
/**
 * @author David Bezalel Laoli <davidbezalel94@gmail.com>
 *
 * @since 8/29/17
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\FileSubmission;
use App\Model\LoanFile;
use App\Model\Paket;
use App\Model\ReLoanFile;
use App\Model\Report;
use App\Model\SubPaket;
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
    public function getmcreport ($paketid)
    {
        if ($this->isPost()) {
            $reportmodel = new Report();
            $reports = $reportmodel->join('subpaket', 'report.subpaket_id', '=', 'subpaket.id')
                ->join('reportparam', 'report.reportparam_id', '=', 'reportparam.id')
                ->leftjoin('filesubmission', 'report.id', '=', 'filesubmission.report_id')
                ->leftjoin('loanfile', 'filesubmission.id', '=', 'loanfile.filesubmission_id')
                ->where('subpaket.id', '=', $paketid)
                ->where('subpaket.reporttype_id', '=', 2)
                ->orderBy('report.id', 'asc')
                ->get(['report.*', 'reportparam.title as reportparamtitle', 'filesubmission.id as filesubmissionid', 'filesubmission.filepath as filepath', 'loanfile.id as loanfileid']);
            $this->response_json->status = true;
            $this->response_json->data = $reports;
            return $this->__json();
        }
    }

    public function getmainreport($paketid)
    {
        if ($this->isPost()) {
            $reportmodel = new Report();
            $reports = $reportmodel->join('subpaket', 'report.subpaket_id', '=', 'subpaket.id')
                ->join('reportparam', 'report.reportparam_id', '=', 'reportparam.id')
                ->leftjoin('filesubmission', 'report.id', '=', 'filesubmission.report_id')
                ->leftjoin('loanfile', 'filesubmission.id', '=', 'loanfile.filesubmission_id')
                ->where('subpaket.paket_id', '=', $paketid)
                ->where('subpaket.reporttype_id', '=', 1)
                ->orderBy('report.id', 'asc')
                ->get(['report.*', 'reportparam.title as reportparamtitle', 'filesubmission.id as filesubmissionid', 'filesubmission.filepath as filepath', 'loanfile.id as loanfileid']);
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
                $_file = $request->file('filesubmitted');
                if (!$_file->isValid()) {
                    $this->response_json->message = 'File is corrupted';
                    return $this->__json();
                }

                if ($_file->getClientOriginalExtension() != 'pdf') {
                    $this->response_json->message = 'File must be a .pdf file';
                    return $this->__json();
                }

                $reportmodel = new Report();
                $subpaketmodel = new SubPaket();
                $paketmodel = new Paket();

                $report = $reportmodel->find($request->reportid);
                $subpaket = $subpaketmodel->find($report->subpaket_id);
                $paket = $paketmodel->find($subpaket->paket_id);

                $_path = 'assets/arsips/' . $paket->title;
                $_file->move($_path, $_file->getClientOriginalName());

                $data = array();
                $data['report_id'] = (int)$request->reportid;
                $data['handledby'] = Auth::user()->id;
                $data['filepath'] = $_path . '/' . $_file->getClientOriginalName();
                FileSubmission::create($data);

                /**
                 * @todo report: update (is_reported = 1; is_available = 1)
                 */
                $report = Report::find((int)$request->reportid);
                $report['isfilesubmitted'] = 1;
                $report['isavailable'] = 1;
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
                if (!LoanFile::where('filesubmission_id', '=', $request['filesubmissionid'])->get()->first()) {
                    $data = array();
                    $data['filesubmission_id'] = $request['filesubmissionid'];
                    $data['handledby'] = Auth::user()->id;
                    LoanFile::create($data);
                }

                /**
                 * @todo report: update (is_reported = 1; is_available = 0)
                 */
                $report = Report::find((int)$request['report_id']);
                $report['isavailable'] = 0;
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
                if (!ReLoanFile::where('loanfile_id', '=', $request['loanfileid'])->get()->first()) {
                    $data = array();
                    $data['loanfile_id'] = $request['loanfileid'];
                    $data['handledby'] = Auth::user()->id;
                    ReLoanFile::create($data);
                }

                /**
                 * @todo report: update (is_reported = 1; is_available = 0)
                 */
                $report = Report::find((int)$request['report_id']);
                $report['isavailable'] = 1;
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