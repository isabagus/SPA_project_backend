<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Report - {{ $report->student->name_student }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #465FFF; padding-bottom: 10px; }
        .header h1 { margin: 0; color: #465FFF; font-size: 24px; }
        .header p { margin: 5px 0; font-size: 12px; color: #666; }
        
        .student-info { margin-bottom: 20px; width: 100%; }
        .student-info td { font-size: 12px; vertical-align: top; }
        .label { font-weight: bold; color: #555; width: 120px; }

        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #f8f9fa; color: #465FFF; font-size: 10px; text-transform: uppercase; padding: 10px; border: 1px solid #eee; text-align: left; }
        td { padding: 10px; border: 1px solid #eee; font-size: 11px; }
        
        .score { font-weight: bold; text-align: center; font-size: 14px; color: #465FFF; }
        .category-name { font-weight: bold; color: #465FFF; font-size: 9px; text-transform: uppercase; margin-bottom: 2px; }
        .criteria-name { font-weight: bold; font-size: 11px; }
        
        .mentor-section { margin-top: 30px; padding: 15px; background-color: #f0f4ff; border-radius: 8px; }
        .mentor-section h3 { margin: 0 0 10px 0; font-size: 14px; color: #465FFF; }
        .mentor-note { font-style: italic; font-size: 11px; }

        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 9px; color: #999; padding: 10px 0; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SINGAPORE PIAGET ACADEMY</h1>
        <p>Student Academic Achievement Report</p>
    </div>

    <table class="student-info">
        <tr>
            <td class="label">Student Name</td>
            <td>: {{ $report->student->name_student }}</td>
            <td class="label">Class Level</td>
            <td>: {{ $report->level_class }}</td>
        </tr>
        <tr>
            <td class="label">Subject</td>
            <td>: {{ $report->subject->category_subject }}</td>
            <td class="label">Academic Year</td>
            <td>: {{ $report->academic_year }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th width="40%">Criteria & Indicators</th>
                <th width="15%" style="text-align: center;">Score</th>
                <th width="45%">Teacher's Evaluation</th>
            </tr>
        </thead>
        <tbody>
            @foreach($report->reportDetails as $detail)
            <tr>
                <td>
                    <div class="category-name">{{ $detail->rubric->rubric_name }}</div>
                    <div class="criteria-name">{{ $detail->criteria->criteria_name }}</div>
                </td>
                <td class="score">{{ number_format($detail->score, 2) }}</td>
                <td>{{ $detail->description_subject ?: 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f8f9fa;">
                <td style="font-weight: bold; text-align: right;">OVERALL SUBJECT AVERAGE</td>
                <td class="score" style="color: #465FFF;">{{ number_format($report->average_value, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="mentor-section">
        <h3>Mentor's Notes</h3>
        <p class="mentor-note">"{{ $report->mentor_note ?: 'No notes provided for this period.' }}"</p>
    </div>

    <div class="footer">
        Generated on {{ date('d F Y H:i') }} | Singapore Piaget Academy E-Report System
    </div>
</body>
</html>
