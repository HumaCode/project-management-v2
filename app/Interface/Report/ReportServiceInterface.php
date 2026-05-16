<?php

namespace App\Interface\Report;

use Illuminate\Http\Request;

interface ReportServiceInterface
{
    public function generateReport(Request $request);
    public function getHistory(Request $request);
    public function deleteReport(string $id);
}
