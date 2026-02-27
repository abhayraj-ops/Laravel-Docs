# Laravel Excel Imports - Study Guide (Basics to Intermediate)

This guide covers how imports are implemented in this project using the **Maatwebsite Excel** package (`maatwebsite/excel` v3.1).

---

## Table of Contents

1. [Basic Import Concepts](#basic-import-concepts)
2. [ToModel - Direct Eloquent Import](#tomodel---direct-eloquent-import)
3. [ToCollection - Manual Processing](#tocollection---manual-processing)
4. [WithStartRow - Skip Header Rows](#withstartrow---skip-header-rows)
5. [WithHeadingRow - Named Headers](#withheadingrow---named-headers)
6. [WithMultipleSheets - Multiple Sheet Imports](#withmultiplesheets---multiple-sheet-imports)
7. [Import Events](#import-events)
8. [Batch Inserts](#batch-inserts)
9. [Validation with Imports](#validation-with-imports)
10. [Controller Integration](#controller-integration)
11. [Common Interfaces Summary](#common-interfaces-summary)
12. [Best Practices](#best-practices)

---

## Basic Import Concepts

Every import class implements one or more **Concerns** (interfaces) from `Maatwebsite\Excel\Concerns`:

| Concern | Purpose |
|---------|---------|
| `ToModel` | Import directly to Eloquent model |
| `ToCollection` | Import to Collection for manual processing |
| `WithStartRow` | Start reading from specific row |
| `WithHeadingRow` | Use row as array keys |
| `WithMultipleSheets` | Handle multiple worksheets |
| `OnEachRow` | Process each row individually |
| `Importable` | Enable chainable methods |

---

## ToModel - Direct Eloquent Import

**File:** `api/app/Imports/StudentsImport.php`

The simplest import method - directly creates Eloquent models from rows.

```php
<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;

class StudentsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'name' => $row[0],
            'email' => $row[1],
            'mobile' => $row[2],
            'class_id' => $row[3],
            'gpa' => $row[4],
        ]);
    }
}
```

### With Constructor Data

```php
class StudentsImport implements ToModel
{
    protected $classId;

    public function __construct($classId = null)
    {
        $this->classId = $classId;
    }

    public function model(array $row)
    {
        $classId = $this->classId;
        if (!$classId && isset($row[5])) {
            $class = Classes::where('name', $row[5])->first();
            $classId = $class?->id;
        }

        return new Student([
            'name' => $row[0],
            'email' => $row[1],
            'mobile' => $row[2],
            'class_id' => $classId,
            'gpa' => $row[3] ?? null,
            'created_by' => auth()->id(),
        ]);
    }
}
```

### Updating Existing Records

```php
public function model(array $row)
{
    $student = Student::where('email', $row[1])->first();

    if ($student) {
        $student->update([
            'name' => $row[0],
            'mobile' => $row[2],
            'gpa' => $row[3],
        ]);
        return null;
    }

    return new Student([
        'name' => $row[0],
        'email' => $row[1],
        'mobile' => $row[2],
        'gpa' => $row[3],
    ]);
}
```

---

## ToCollection - Manual Processing

**File:** `api/app/Imports/IssuanceExcelImport.php`

Use when you need full control over the import process.

```php
<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class IssuanceExcelImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            foreach ($row as $k => $r) {
                if ($r == '${examinations.exam_sessions_name}') {
                    $row[$k] = 'Test Exam Name';
                }
            }
        }
    }
}
```

### Basic Collection Processing

```php
public function collection(Collection $rows)
{
    // Skip header row
    $rows = $rows->skip(1);

    foreach ($rows as $row) {
        Student::updateOrCreate(
            ['email' => $row[1]],
            [
                'name' => $row[0],
                'mobile' => $row[2],
                'gpa' => $row[3],
            ]
        );
    }
}
```

---

## WithStartRow - Skip Header Rows

**File:** `api/app/Imports/DataSheetImport.php`

```php
<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class DataSheetImport implements ToCollection, WithStartRow
{
    public function collection(Collection $rows)
    {
        // Starts reading from row 2
        foreach ($rows as $row) {
            // Process row
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
```

---

## WithHeadingRow - Named Headers

```php
<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        // First row must have headers: Name, Email, Mobile, GPA
        
        $rows->each(function ($row) {
            Student::create([
                'name' => $row['name'],
                'email' => $row['email'],
                'mobile' => $row['mobile'],
                'gpa' => $row['gpa'] ?? null,
            ]);
        });
    }
}
```

---

## WithMultipleSheets - Multiple Sheet Imports

**File:** `api/app/Imports/CandidateExcelImport.php`

```php
<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class CandidateExcelImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            new DataSheetImport(),
        ];
    }
}
```

### With Multiple Different Sheets

```php
class MultiSheetImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        return [
            'Students' => new StudentsImport(),
            'Teachers' => new TeachersImport(),
            'Classes' => new ClassesImport(),
        ];
    }
}
```

### Skip Unknown Sheets

```php
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;

class MultiSheetImport implements WithMultipleSheets, SkipsUnknownSheets
{
    public function sheets(): array
    {
        return [
            'Students' => new StudentsImport(),
        ];
    }

    public function onUnknownSheet($sheetName)
    {
        info("Unknown sheet: {$sheetName}");
    }
}
```

---

## Import Events

```php
<?php

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;
use Maatwebsite\Excel\Events\BeforeImport;

class StudentsImport implements ToCollection, WithEvents
{
    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                // Before import starts
            },
            
            AfterImport::class => function (AfterImport $event) {
                // After import completes
            },
            
            ImportFailed::class => function (ImportFailed $event) {
                $exception = $event->getException();
                Log::error('Import failed: ' . $exception->getMessage());
            },
        ];
    }

    public function collection(Collection $rows)
    {
        // Import logic
    }
}
```

---

## Batch Inserts

For large imports, use chunking:

```php
<?php

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class StudentsImport implements ToCollection, WithChunkReading
{
    public function collection(Collection $rows)
    {
        $rows->chunk(1000)->each(function ($chunk) {
            $data = $chunk->map(function ($row) {
                return [
                    'name' => $row[0],
                    'email' => $row[1],
                    'mobile' => $row[2],
                    'gpa' => $row[3] ?? null,
                    'created_at' => now(),
                ];
            })->toArray();

            Student::insert($data);
        });
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
```

---

## Validation with Imports

### Using Form Request

```php
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportStudentsRequest extends FormRequest
{
    public function rules()
    {
        return [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ];
    }
}
```

### Validate During Import

```php
<?php

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Validator;

class StudentsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows->skip(1) as $row) {
            $validator = Validator::make($row->toArray(), [
                '0' => 'required|string|max:255',
                '1' => 'required|email|unique:students,email',
                '2' => 'nullable|string|max:20',
                '3' => 'nullable|numeric|min:0|max:10',
            ]);

            if ($validator->fails()) {
                Log::warning('Row validation failed', $validator->errors()->toArray());
                continue;
            }

            Student::create([
                'name' => $row[0],
                'email' => $row[1],
                'mobile' => $row[2],
                'gpa' => $row[3],
            ]);
        }
    }
}
```

---

## Controller Integration

### Basic Import

```php
<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        $file = $request->file('file');
        Excel::import(newfile);

        return StudentsImport(), $ back()->with('success', 'Students imported successfully!');
    }
}
```

### Import with Constructor Data

```php
public function import(Request $request)
{
    $file = $request->file('file');
    $classId = $request->input('class_id');

    Excel::import(new StudentsImport($classId), $file);

    return back()->with('success', 'Import completed!');
}
```

### Handle Validation Errors

```php
use Maatwebsite\Excel\Validators\Failure;

public function import(Request $request)
{
    try {
        Excel::import(new StudentsImport(), $request->file('file'));
        return back()->with('success', 'Import completed!');
    } catch (ValidationException $e) {
        $failures = $e->failures();
        foreach ($failures as $failure) {
            $failure->row();
            $failure->attribute();
            $failure->errors();
        }
        return back()->withErrors($failures);
    }
}
```

### Queue Import (Large Files)

```php
public function import(Request $request)
{
    Excel::queueImport(new StudentsImport(), $request->file('file'));
    return back()->with('info', 'Import queued for processing');
}
```

---

## Common Interfaces Summary

| Interface | Method | Use Case |
|-----------|--------|----------|
| `ToModel` | `model($row)` | Direct Eloquent insert |
| `ToCollection` | `collection($rows)` | Manual processing |
| `WithStartRow` | `startRow()` | Skip header rows |
| `WithHeadingRow` | `headingRow()` | Named column access |
| `WithMultipleSheets` | `sheets()` | Multiple worksheets |
| `WithChunkReading` | `chunkSize()` | Large file processing |
| `WithEvents` | `registerEvents()` | Lifecycle hooks |
| `SkipsUnknownSheets` | `onUnknownSheet()` | Handle unknown sheets |

---

## Best Practices

### 1. Use Transactions

```php
use Illuminate\Support\Facades\DB;

public function collection(Collection $rows)
{
    DB::transaction(function () use ($rows) {
        foreach ($rows->skip(1) as $row) {
            Student::create([
                'name' => $row[0],
                'email' => $row[1],
            ]);
        }
    });
}
```

### 2. Handle Duplicates

```php
public function model(array $row)
{
    return Student::updateOrCreate(
        ['email' => $row[1]],
        [
            'name' => $row[0],
            'mobile' => $row[2],
        ]
    );
}
```

### 3. Log Progress

```php
public function collection(Collection $rows)
{
    $total = $rows->count() - 1;
    $processed = 0;

    foreach ($rows->skip(1) as $row) {
        $processed++;
        if ($processed % 100 === 0) {
            Log::info("Processed {$processed}/{$total} rows");
        }
    }
}
```

---

## File Structure in This Project

```
api/app/Imports/
├── StudentsImport.php              # ToModel example
├── VerificationImport.php          # ToCollection example
├── DataSheetImport.php             # WithStartRow example
├── MarksComponentImport.php        # ToCollection example
├── IssuanceExcelImport.php         # ToCollection with processing
├── CollectionImport.php            # ToCollection placeholder
├── ExaminationSubjectImport.php     # ToCollection placeholder
├── ExaminationComponentImport.php   # ToCollection placeholder
├── DuplicateCertificateImport.php   # ToCollection placeholder
├── ItemImport.php                  # WithMultipleSheets
├── MultipleChoiceExcelImport.php   # WithMultipleSheets
├── CandidateExcelImport.php        # WithMultipleSheets
├── ExaminationCentreExcelImport.php
├── SyllabusImport.php
├── OptionImport.php
└── ... (more import files)
```

---

## Quick Reference

### Simple Model Import
```php
class SimpleImport implements ToModel
{
    public function model(array $row) {
        return new Model([
            'field1' => $row[0],
            'field2' => $row[1],
        ]);
    }
}
```

### Collection Import with Validation
```php
class ValidatedImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows) {
        $rows->each(function ($row) {
            Model::create($row->toArray());
        });
    }
}
```

### Multi-Sheet Import
```php
class MultiSheetImport implements WithMultipleSheets
{
    public function sheets() {
        return [
            'Data' => new DataImport(),
            'Meta' => new MetaImport(),
        ];
    }
}
```

---

## Related Guides

- See `exports-study-guide.md` for export implementations
- Combine exports and imports for data backup/restore features
