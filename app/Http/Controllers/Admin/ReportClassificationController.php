<?php
/**
 * @author David Bezalel Laoli <davidbezalel94@gmail.com>
 *
 * @since 8/29/17
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PPKAppointment;
use App\Model\Paket;
use App\Model\ReportClassification;
use Illuminate\Http\Request;

class ReportClassificationController extends Controller
{
    /**
     * @todo return all report classification
     */
    public function get() {
        if ($this->isPost()) {
            $reportclassifications = ReportClassification::all();
            $this->response_json->status = true;
            $this->response_json->data = $reportclassifications;
            return $this->__json();
        }
    }
}