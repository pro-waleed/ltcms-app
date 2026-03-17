<?php

use App\Models\Department;
use App\Models\Employee;
use App\Models\Mission;
use App\Models\Nomination;
use App\Models\Opportunity;
use App\Models\OpportunityType;
use App\Models\Partner;
use App\Models\TrainingHistory;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('import:responses {path?}', function () {
    $basePath = 'E:\\pro\\data';
    $path = $this->argument('path') ?? ($basePath . '\\responses.csv');
    $employeesCsv = $basePath . '\\employees.csv';
    $coursesCsv = $basePath . '\\courses.csv';

    if (!file_exists($path)) {
        $this->error("File not found: {$path}");
        return 1;
    }

    $normalize = function (string $value): string {
        $value = trim($value);
        $value = preg_replace('/\s+/u', ' ', $value);
        $value = str_replace(['ـ', '،'], ['', ''], $value);
        $value = preg_replace('/[\x{064B}-\x{065F}]/u', '', $value); // Arabic diacritics
        return $value;
    };

    // Build employee map from existing DB (email and name)
    $employeeByEmail = [];
    $employeeByName = [];
    foreach (Employee::all(['id', 'full_name', 'notes']) as $emp) {
        $employeeByName[$normalize($emp->full_name)] = $emp->id;
        if ($emp->notes && preg_match('/email=([^;\s]+)/', $emp->notes, $m)) {
            $employeeByEmail[strtolower(trim($m[1]))] = $emp->id;
        }
    }

    // Build department map
    $departmentByName = [];
    foreach (Department::all(['id', 'name']) as $dep) {
        $departmentByName[$normalize($dep->name)] = $dep->id;
    }

    // Build mission map
    $missionByName = [];
    foreach (Mission::all(['id', 'name']) as $mis) {
        $missionByName[$normalize($mis->name)] = $mis->id;
    }

    // Read employees.csv (if exists) and enrich map
    $employeeCsvMap = [];
    if (file_exists($employeesCsv)) {
        $rows = array_map('str_getcsv', file($employeesCsv));
        $currentEmail = null;
        foreach ($rows as $r) {
            $key = trim($r[0] ?? '');
            $val = trim($r[1] ?? '');
            if ($key === 'اكتب بريد الموظف هنا' && $val !== '') {
                $currentEmail = strtolower(str_replace('#', '@', $val));
            }
            if ($key === 'اسم الموظف' && $val !== '' && $currentEmail) {
                $employeeCsvMap[$currentEmail] = $val;
                $currentEmail = null;
            }
        }
    }

    // Build name->department map from courses.csv
    $nameDepartment = [];
    if (file_exists($coursesCsv)) {
        $rows = array_map('str_getcsv', file($coursesCsv));
        if (count($rows) > 0) {
            $header = $rows[0];
            $idxName = array_search('الاسم', $header);
            $idxDept = array_search('الادارة', $header);
            foreach (array_slice($rows, 1) as $r) {
                if ($idxName === false || $idxDept === false) {
                    break;
                }
                $n = trim($r[$idxName] ?? '');
                $d = trim($r[$idxDept] ?? '');
                if ($n !== '' && $d !== '') {
                    $nameDepartment[$normalize($n)] = $d;
                }
            }
        }
    }

    // Build course -> partner map from courses.csv
    $coursePartner = [];
    if (file_exists($coursesCsv)) {
        $rows = array_map('str_getcsv', file($coursesCsv));
        if (count($rows) > 0) {
            $header = $rows[0];
            $idxPick = array_search('اختر اسم الدورة', $header);
            $idxList = array_search('الدورات', $header);
            foreach (array_slice($rows, 1) as $r) {
                $candidates = [];
                if ($idxPick !== false && !empty($r[$idxPick])) {
                    $candidates[] = trim($r[$idxPick]);
                }
                if ($idxList !== false && !empty($r[$idxList])) {
                    $candidates[] = trim($r[$idxList]);
                }
                foreach ($candidates as $course) {
                    if ($course === '') {
                        continue;
                    }
                    $partnerName = null;
                    if (Str::contains($course, '-')) {
                        $parts = explode('-', $course);
                        $partnerName = trim(end($parts));
                    }
                    if (!$partnerName) {
                        $partnerName = 'جهة دولية';
                    }
                    $partner = Partner::firstOrCreate(
                        ['name' => $partnerName],
                        ['partner_type' => 'جهة مانحة', 'status' => 'active']
                    );
                    $coursePartner[$course] = $partner->id;
                }
            }
        }
    }

    // Build opportunity title map to avoid duplicates
    $opportunityByTitle = [];
    foreach (Opportunity::all(['id', 'title']) as $opp) {
        $opportunityByTitle[$normalize(Str::lower($opp->title))] = $opp->id;
    }

    $handle = fopen($path, 'r');
    if (!$handle) {
        $this->error('Unable to open file.');
        return 1;
    }

    $header = fgetcsv($handle);
    if (!$header) {
        $this->error('Empty file.');
        return 1;
    }

    // Normalize headers (trim BOM and spaces)
    $header = array_map(function ($h) {
        $h = trim($h);
        $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
        return $h;
    }, $header);

    $types = OpportunityType::pluck('id', 'name');
    $typeMap = [
        'ورشة عمل' => $types['ورشة عمل'] ?? null,
        'دورة قصيرة' => $types['دورة قصيرة'] ?? null,
        'منحة تدريبية' => $types['منحة تدريبية'] ?? null,
        'دبلوم' => $types['دبلوم'] ?? null,
    ];

    $count = 0;
    while (($row = fgetcsv($handle)) !== false) {
        $data = array_combine($header, $row);
        if (!$data) {
            continue;
        }

        $email = trim($data['البريد الإلكتروني'] ?? $data['عنوان البريد الإلكتروني'] ?? '');
        $email = strtolower(str_replace('#', '@', $email));
        $fullName = trim($data['الاسم الكامل'] ?? '');
        $phone = trim($data['رقم التلفون'] ?? '');

        if ($fullName === '' && $email === '') {
            continue;
        }

        // Prefer employee from CSV by email
        if ($fullName === '' && $email !== '' && isset($employeeCsvMap[$email])) {
            $fullName = $employeeCsvMap[$email];
        }

        $fullNameNorm = $normalize($fullName);

        // Find employee by email or name
        $employeeId = null;
        if ($email !== '' && isset($employeeByEmail[$email])) {
            $employeeId = $employeeByEmail[$email];
        } elseif ($fullNameNorm !== '' && isset($employeeByName[$fullNameNorm])) {
            $employeeId = $employeeByName[$fullNameNorm];
        }

        $departmentId = null;
        if ($fullNameNorm !== '' && isset($nameDepartment[$fullNameNorm])) {
            $depName = $nameDepartment[$fullNameNorm];
            $depKey = $normalize($depName);
            if (!isset($departmentByName[$depKey])) {
                $dep = Department::create(['name' => $depName]);
                $departmentByName[$depKey] = $dep->id;
            }
            $departmentId = $departmentByName[$depKey];
        }

        // Try to map mission from residence if it looks like a mission
        $missionId = null;
        $location = trim($data['محل الاقامة الحالي'] ?? '');
        if ($location !== '' && Str::contains($location, ['بعثة', 'سفارة', 'قنصلية'])) {
            $locKey = $normalize($location);
            if (!isset($missionByName[$locKey])) {
                $mis = Mission::create(['name' => $location]);
                $missionByName[$locKey] = $mis->id;
            }
            $missionId = $missionByName[$locKey];
        }

        if (!$employeeId) {
            $nextEmpNo = 'EMP-' . str_pad((string) (Employee::max('id') + 1), 4, '0', STR_PAD_LEFT);
            $employee = Employee::create([
                'employee_no' => $nextEmpNo,
                'full_name' => $fullName !== '' ? $fullName : 'غير محدد',
                'department_id' => $departmentId,
                'mission_id' => $missionId,
                'job_title' => $data['الوظيفة الحالية'] ?? null,
                'job_grade' => $data['الدرجة الحالية'] ?? null,
                'education_level' => $data['المؤهل الدراسي'] ?? null,
                'languages' => 'الانجليزية',
                'language_level' => $data['مستوى اللغة الانجليزية'] ?? null,
                'work_location' => $data['محل الاقامة الحالي'] ?? null,
                'notes' => $email !== '' ? "email={$email}; phone={$phone}" : "phone={$phone}",
            ]);
            $employeeId = $employee->id;
            $employeeByName[$normalize($employee->full_name)] = $employeeId;
            if ($email !== '') {
                $employeeByEmail[$email] = $employeeId;
            }
        } else {
            // Update department/mission if found and missing
            if ($departmentId) {
                Employee::whereKey($employeeId)->whereNull('department_id')->update(['department_id' => $departmentId]);
            }
            if ($missionId) {
                Employee::whereKey($employeeId)->whereNull('mission_id')->update(['mission_id' => $missionId]);
            }
        }

        $title = trim($data['اسم الدورة/ورشة العمل وتاريخها'] ?? '');
        if ($title === '') {
            $title = trim($data['عنوان اخر دورة/ ورشة عمل شاركت فيها'] ?? '');
        }
        if ($title === '') {
            $title = 'فرصة تدريبية';
        }

        $titleLower = Str::lower($title);
        $typeId = $typeMap['دورة قصيرة'];
        if (Str::contains($titleLower, 'ورشة')) {
            $typeId = $typeMap['ورشة عمل'] ?? $typeId;
        } elseif (Str::contains($titleLower, 'منحة')) {
            $typeId = $typeMap['منحة تدريبية'] ?? $typeId;
        } elseif (Str::contains($titleLower, 'دبلوم')) {
            $typeId = $typeMap['دبلوم'] ?? $typeId;
        }

        $delivery = 'onsite';
        if (Str::contains($titleLower, ['online', 'أونلاين', 'اونلاين'])) {
            $delivery = 'online';
        }

        // Partner mapping from courses list or from title
        $partnerId = null;
        foreach ($coursePartner as $course => $pid) {
            if (Str::contains($title, $course)) {
                $partnerId = $pid;
                break;
            }
        }
        if (!$partnerId && Str::contains($title, '-')) {
            $parts = explode('-', $title);
            $partnerName = trim(end($parts));
            if ($partnerName !== '') {
                $partner = Partner::firstOrCreate(
                    ['name' => $partnerName],
                    ['partner_type' => 'جهة مانحة', 'status' => 'active']
                );
                $partnerId = $partner->id;
            }
        }

        $refPrefix = 'TR-' . date('Y') . '-';
        $lastRef = Opportunity::where('reference_no', 'like', $refPrefix . '%')
            ->orderByDesc('reference_no')
            ->value('reference_no');
        $nextNumber = 1;
        if ($lastRef && preg_match('/(\d+)$/', $lastRef, $m)) {
            $nextNumber = intval($m[1]) + 1;
        }

        $titleKey = $normalize(Str::lower($title));
        if (isset($opportunityByTitle[$titleKey])) {
            $opportunityId = $opportunityByTitle[$titleKey];
        } else {
            $opportunity = Opportunity::create([
                'reference_no' => $refPrefix . str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT),
                'title' => $title,
                'opportunity_type_id' => $typeId,
                'delivery_mode' => $delivery,
                'summary' => $data['المتطلبات أو المؤهلات المرتبطة بالدورة'] ?? null,
                'target_group' => $data['الهدف من التسجيل'] ?? null,
                'status' => 'received',
                'partner_id' => $partnerId,
            ]);
            $opportunityId = $opportunity->id;
            $opportunityByTitle[$titleKey] = $opportunityId;
        }

        $statusRaw = trim($data['حالة الطلب'] ?? '');
        $resultRaw = trim($data['النتيجة'] ?? '');
        $status = 'nominated';
        $map = [
            'قيد المراجعة' => 'under_review',
            'معتمد' => 'approved',
            'مقبول' => 'approved',
            'احتياطي' => 'reserve',
            'مرفوض' => 'rejected',
            'معتذر' => 'declined',
            'شارك' => 'attended',
            'لم يشارك' => 'not_attended',
            'مكتمل' => 'completed',
            'مغلق' => 'closed',
        ];
        if (isset($map[$statusRaw])) {
            $status = $map[$statusRaw];
        }

        $nominationDate = $data['طابع زمني'] ?? null;
        $nominationDate = $nominationDate ? Carbon::parse($nominationDate)->format('Y-m-d') : null;

        $existing = Nomination::where('opportunity_id', $opportunityId)
            ->where('employee_id', $employeeId)
            ->where('nomination_date', $nominationDate)
            ->first();

        if ($existing) {
            $existing->update([
                'nomination_type' => $data['الهدف من التسجيل'] ?? null,
                'status' => $status,
                'accepted' => Str::contains($resultRaw, ['مقبول', 'معتمد']) ? 1 : null,
                'declined' => Str::contains($resultRaw, ['مرفوض']) ? 1 : null,
                'notes' => trim(($data['ملاحظات الادارة'] ?? '') . ' | ' . ($data['رفع المستندات المطلوبة'] ?? '')),
            ]);
        } else {
            $nextNomNo = 'NOM-' . date('Y') . '-' . str_pad((string) (Nomination::max('id') + 1), 3, '0', STR_PAD_LEFT);
            Nomination::create([
                'nomination_no' => $nextNomNo,
                'opportunity_id' => $opportunityId,
                'employee_id' => $employeeId,
                'nomination_date' => $nominationDate,
                'nomination_type' => $data['الهدف من التسجيل'] ?? null,
                'status' => $status,
                'accepted' => Str::contains($resultRaw, ['مقبول', 'معتمد']) ? 1 : null,
                'declined' => Str::contains($resultRaw, ['مرفوض']) ? 1 : null,
                'notes' => trim(($data['ملاحظات الادارة'] ?? '') . ' | ' . ($data['رفع المستندات المطلوبة'] ?? '')),
            ]);
        }

        $count++;
    }

    fclose($handle);

    $this->info("Imported {$count} responses.");
    return 0;
})->purpose('Import responses from CSV into LTCMS');

Artisan::command('merge:employees', function () {
    $normalize = function (string $value): string {
        $value = trim($value);
        $value = preg_replace('/\s+/u', ' ', $value);
        $value = str_replace(['ـ', '،'], ['', ''], $value);
        $value = preg_replace('/[\x{064B}-\x{065F}]/u', '', $value);
        return $value;
    };

    $byEmail = [];
    $byName = [];
    $merged = 0;

    $employees = Employee::all();
    foreach ($employees as $emp) {
        $email = null;
        if ($emp->notes && preg_match('/email=([^;\s]+)/', $emp->notes, $m)) {
            $email = strtolower(trim($m[1]));
        }
        $nameKey = $normalize($emp->full_name ?? '');

        $key = $email ?: $nameKey;
        if ($key === '') {
            continue;
        }

        if (isset($byEmail[$key]) || isset($byName[$key])) {
            $targetId = $byEmail[$key] ?? $byName[$key];
            $sourceId = $emp->id;

            if ($targetId === $sourceId) {
                continue;
            }

            DB::transaction(function () use ($targetId, $sourceId, &$merged) {
                Nomination::where('employee_id', $sourceId)->update(['employee_id' => $targetId]);
                TrainingHistory::where('employee_id', $sourceId)->update(['employee_id' => $targetId]);
                Employee::where('id', $sourceId)->delete();
                $merged++;
            });
        } else {
            if ($email) {
                $byEmail[$key] = $emp->id;
            } else {
                $byName[$key] = $emp->id;
            }
        }
    }

    $this->info("Merged duplicates: {$merged}");
    return 0;
})->purpose('Merge duplicate employees by email or name');

Artisan::command('dedupe:opportunities', function () {
    $normalize = function (string $value): string {
        $value = trim($value);
        $value = preg_replace('/\s+/u', ' ', $value);
        $value = str_replace(['ـ', '،'], ['', ''], $value);
        $value = preg_replace('/[\x{064B}-\x{065F}]/u', '', $value);
        return Str::lower($value);
    };

    $seen = [];
    $merged = 0;

    $opps = Opportunity::orderBy('id')->get();
    foreach ($opps as $opp) {
        $key = $normalize($opp->title);
        if (!isset($seen[$key])) {
            $seen[$key] = $opp->id;
            continue;
        }

        $targetId = $seen[$key];
        $sourceId = $opp->id;

        DB::transaction(function () use ($targetId, $sourceId, &$merged) {
            Nomination::where('opportunity_id', $sourceId)->update(['opportunity_id' => $targetId]);
            TrainingHistory::where('opportunity_id', $sourceId)->update(['opportunity_id' => $targetId]);
            Opportunity::where('id', $sourceId)->delete();
            $merged++;
        });
    }

    $this->info("Merged duplicate opportunities: {$merged}");
    return 0;
})->purpose('Merge duplicate opportunities by normalized title');

Artisan::command('report:unmatched', function () {
    $basePath = 'E:\\pro\\data';
    $path = $basePath . '\\responses.csv';
    $out = $basePath . '\\unmatched_report.csv';
    if (!file_exists($path)) {
        $this->error("File not found: {$path}");
        return 1;
    }

    $handle = fopen($path, 'r');
    $header = fgetcsv($handle);
    $header = array_map(function ($h) {
        $h = trim($h);
        $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
        return $h;
    }, $header);

    $outHandle = fopen($out, 'w');
    fputcsv($outHandle, ['reason', 'email', 'name', 'course_title']);

    while (($row = fgetcsv($handle)) !== false) {
        $data = array_combine($header, $row);
        if (!$data) {
            continue;
        }
        $email = trim($data['البريد الإلكتروني'] ?? $data['عنوان البريد الإلكتروني'] ?? '');
        $name = trim($data['الاسم الكامل'] ?? '');
        $course = trim($data['اسم الدورة/ورشة العمل وتاريخها'] ?? $data['عنوان اخر دورة/ ورشة عمل شاركت فيها'] ?? '');
        if ($email === '' && $name === '') {
            fputcsv($outHandle, ['missing_identity', $email, $name, $course]);
        }
        if ($course === '') {
            fputcsv($outHandle, ['missing_course', $email, $name, $course]);
        }
    }

    fclose($handle);
    fclose($outHandle);

    $this->info("Report written to {$out}");
    return 0;
})->purpose('Generate unmatched rows report');
