<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Print Student Report - {{ $report->student->name_student }}</title>
    <style>
        /* Reset default browser margins */
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #000000;
            line-height: 1.3;
        }

        /* Layout simulasi kertas A4 di layar monitor (screen) */
        @media screen {
            body {
                background-color: #f3f4f6;
                padding: 40px 0;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .print-wrapper {
                background-color: #ffffff;
                width: 210mm;
                min-height: 297mm;
                padding: 20mm;
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
                box-sizing: border-box;
            }
            .control-panel {
                width: 210mm;
                margin: 0 auto 20px auto;
                display: flex;
                justify-content: space-between;
                align-items: center;
                box-sizing: border-box;
            }
            .btn {
                display: inline-flex;
                align-items: center;
                padding: 10px 20px;
                border-radius: 8px;
                font-size: 13px;
                font-weight: bold;
                text-decoration: none;
                cursor: pointer;
                border: none;
                transition: all 0.2s;
            }
            .btn-secondary {
                background-color: #e5e7eb;
                color: #374151;
            }
            .btn-secondary:hover {
                background-color: #d1d5db;
            }
            .btn-primary {
                background-color: #059669;
                color: #ffffff;
            }
            .btn-primary:hover {
                background-color: #047857;
            }
        }

        /* Layout saat cetak (print) */
        @media print {
            .no-print {
                display: none !important;
            }
            @page {
                margin: 0; /* Menghilangkan header dan footer bawaan browser (URL, Halaman, Tanggal) */
                size: A4;
            }
            body {
                background-color: #ffffff;
            }
            .print-wrapper {
                width: 100%;
                padding-top: 150px; /* Spasi atas untuk pre-printed header kertas fisik */
                padding-bottom: 120px; /* Spasi bawah untuk pre-printed footer kertas fisik */
                padding-left: 20mm;
                padding-right: 20mm;
                box-sizing: border-box;
            }
        }

        /* Core table styles dengan sinkronisasi lebar kolom */
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .header-table td {
            font-size: 11px;
            padding: 2px 0;
            vertical-align: top;
        }

        .assessment-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            table-layout: fixed; /* Menjamin lebar kolom persis mengikuti persentase */
        }
        .assessment-table td {
            font-size: 10px;
            padding: 6px 8px;
            vertical-align: middle;
            box-sizing: border-box;
        }
        .criteria-col-width {
            width: 80%; /* Sinkronisasi lebar kolom pertama */
        }
        .score-col-width {
            width: 20%; /* Sinkronisasi lebar kolom kedua */
        }
        
        .level-header-col {
            text-align: center;
            font-weight: bold;
            font-size: 9px;
            padding-bottom: 4px;
        }
        
        .rubric-row td {
            font-weight: bold;
            font-style: italic;
            text-transform: uppercase;
            padding-top: 12px;
            padding-bottom: 4px;
            padding-left: 0;
        }
        
        .criteria-row td {
            border: 1px solid #000000;
        }
        .criteria-cell {
            text-align: left;
        }
        .score-cell {
            text-align: center;
        }
        
        .average-row td {
            border: 1px solid #000000;
            font-weight: bold;
            padding: 8px;
        }
        .avg-label {
            font-style: italic;
            text-transform: uppercase;
        }
        .avg-score {
            text-align: center;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .footer-table td {
            vertical-align: top;
            padding: 0;
        }
        .guide-title, .teachers-title {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 5px;
        }
        .teachers-title {
            font-style: italic;
            text-align: right;
            margin-right: 15px;
        }
        .guide-text {
            font-size: 10px;
            line-height: 1.4;
        }
        .teachers-list-table {
            border-collapse: collapse;
            float: right;
            margin-right: 15px;
        }
        .teachers-list-table td {
            padding: 2px 0;
            text-align: right;
            font-size: 10px;
            font-weight: bold;
            font-style: italic;
        }
        
        .feedback-section {
            margin-top: 20px;
            font-size: 10px;
        }
        .feedback-title {
            font-weight: bold;
            margin-bottom: 4px;
        }
        .feedback-box {
            border: 1px solid #000000;
            padding: 8px;
            font-style: italic;
            line-height: 1.4;
        }
    </style>
</head>
<body>

    <div class="control-panel no-print" style="margin-top: 10px;">
        <button onclick="if(window.opener || window.history.length <= 1) { window.close(); } else { history.back(); }" class="btn btn-secondary">
            Back
        </button>
        <button onclick="window.print()" class="btn btn-primary" style="margin-left: 10px;">
            Print Now
        </button>
    </div>

    <div class="print-wrapper">
        <table class="header-table">
            <tr>
                <td style="font-weight: bold; font-style: italic; font-size: 12px; text-transform: uppercase;">
                    {{ strtoupper($report->subject->category_subject) }}
                </td>
                <td style="text-align: right; font-weight: bold; font-size: 11px; text-transform: uppercase;">
                    {{ strtoupper($report->level_class ?? $report->student->level_class) }}
                </td>
            </tr>
            <tr>
                <td style="font-style: italic; padding-top: 4px;" colspan="2">
                    Name of Student : {{ $report->student->name_student }}
                </td>
            </tr>
        </table>

        <table class="assessment-table">
            <colgroup>
                <col class="criteria-col-width">
                <col class="score-col-width">
            </colgroup>
            <thead>
                <tr>
                    <td style="border: none;"></td>
                    <td class="level-header-col">LEVEL</td>
                </tr>
            </thead>
            <tbody>
                @php
                    $teachersList = [];
                    // Kumpulkan semua data guru unik dari reportDetails dengan fallback
                    foreach($report->reportDetails as $detail) {
                        $rubric = $detail->rubric ?? ($detail->criteria->category ?? null);
                        if ($rubric) {
                            $teacher = $rubric->teacher;
                            if ($teacher) {
                                $rubricName = $rubric->rubric_name ?? 'General Rubric';
                                
                                if (isset($teachersList[$teacher->teacher_id])) {
                                    if (!in_array($rubricName, $teachersList[$teacher->teacher_id]['rubrics'])) {
                                        $teachersList[$teacher->teacher_id]['rubrics'][] = $rubricName;
                                    }
                                } else {
                                    $teachersList[$teacher->teacher_id] = [
                                        'name' => $teacher->name,
                                        'rubrics' => [$rubricName]
                                    ];
                                }
                            }
                        }
                    }

                    // Kelompokkan detail berdasarkan rubric_id dengan fallback
                    $groupedDetails = $report->reportDetails->groupBy(function($detail) {
                        return $detail->rubric_id ?? ($detail->criteria->rubric_id ?? 0);
                    });
                @endphp

                @foreach($groupedDetails as $rubricId => $details)
                    @php
                        $firstDetail = $details->first();
                        $rubric = $firstDetail->rubric ?? ($firstDetail->criteria->category ?? null);
                        $rubricName = $rubric->rubric_name ?? 'General Rubric';
                    @endphp
                    <tr class="rubric-row">
                        <td colspan="2" style="border: none;">
                            {{ strtoupper($rubricName) }}
                        </td>
                    </tr>
                    @foreach($details as $detail)
                    <tr class="criteria-row">
                        <td class="criteria-cell">
                            {{ $detail->criteria->criteria_name ?? 'Criteria' }}
                        </td>
                        <td class="score-cell">
                            {{ number_format($detail->score, 2, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                @endforeach

                <!-- Average Row -->
                <tr class="average-row">
                    <td class="avg-label">AVERAGE</td>
                    <td class="avg-score">
                        {{ number_format($report->average_value ?? $report->reportDetails->avg('score'), 2, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="footer-table">
            <tr>
                <td style="width: 50%;">
                    <div class="guide-title">LEVEL</div>
                    <div class="guide-text">
                        [1.00 - 1.99] <em>Improving</em><br>
                        [2.00 - 2.49] <em>Meeting expectations</em><br>
                        [2.50 - 3.00] <em>Exceeding expectations</em>
                    </div>
                </td>
                <td style="width: 50%;">
                    <div class="teachers-title">Teachers:</div>
                    <table class="teachers-list-table">
                        @foreach($teachersList as $tId => $tData)
                        <tr>
                            <td>
                                {{ $tData['name'] }} <span style="font-weight: normal;">[{{ implode(', ', $tData['rubrics']) }}]</span>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </td>
            </tr>
        </table>

        @if($report->mentor_note)
        <div class="feedback-section">
            <div class="feedback-title">MENTOR FEEDBACK:</div>
            <div class="feedback-box">
                "{{ $report->mentor_note }}"
            </div>
        </div>
        @endif
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 600);
        }
    </script>
</body>
</html>
