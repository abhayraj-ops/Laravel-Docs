# Laravel Excel Exports - Study Guide (Basics to Intermediate)

This guide covers how exports are implemented in this project using the **Maatwebsite Excel** package (`maatwebsite/excel` v3.1).

---

## Table of Contents

1. [Installation & Setup](#installation--setup)
2. [Basic Export Concepts](#basic-export-concepts)
3. [FromCollection - Export from Eloquent/Collection](#fromcollection---export-from-eloquentcollection)
4. [FromArray - Export from Array](#fromarray---export-from-array)
5. [WithHeadings - Adding Headers](#withheadings---adding-headers)
6. [WithMapping - Data Transformation](#withmapping---data-transformation)
7. [WithTitle - Sheet Naming](#withtitle---sheet-naming)
8. [WithCustomStartCell - Start Data at Specific Cell](#withcustomstartcell---start-data-at-specific-cell)
9. [WithEvents - Advanced Formatting](#withevents---advanced-formatting)
10. [WithDrawings - Adding Images](#withdrawings---adding-images)
11. [ShouldAutoSize - Auto Column Width](#shouldautosize---auto-column-width)
12. [WithStrictNullComparison - Handle Nulls](#withstrictnullcomparison---handle-nulls)
13. [WithChunkReading - Large Data Exports](#withchunkreading---large-data-exports)
14. [WithMultipleSheets - Multiple Sheets](#withmultiplesheets---multiple-sheets)
15. [Data Validation - Dropdown Lists](#data-validation---dropdown-lists)
16. [Exportable Trait](#exportable-trait)
17. [Controller Integration](#controller-integration)
18. [Traits for Reusable Code](#traits-for-reusable-code)
19. [Common Interfaces Summary](#common-interfaces-summary)

---

## Installation & Setup

```json
// composer.json
"maatwebsite/excel": "^3.1"
```

```bash
composer require maatwebsite/excel
```

Publish config (optional):
```bash
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
```

---

## Basic Export Concepts

Every export class implements one or more **Concerns** (interfaces) from `Maatwebsite\Excel\Concerns`:

| Concern | Purpose |
|---------|---------|
| `FromCollection` | Export data from Laravel Collection/Eloquent |
| `FromArray` | Export data from PHP array |
| `WithHeadings` | Add header row |
| `WithMapping` | Transform data before export |
| `WithTitle` | Set sheet name |
| `WithEvents` | Hook into export events |
| `WithDrawings` | Add images to spreadsheet |
| `ShouldAutoSize` | Auto-size columns |
| `WithStrictNullComparison` | Proper null handling |
| `WithChunkReading` | Process large datasets in chunks |
| `WithMultipleSheets` | Create multiple sheets |
| `WithCustomStartCell` | Define start cell for data |

---

## FromCollection - Export from Eloquent/Collection

**File:** [`api/app/Exports/UsersExport.php`](api/app/Exports/UsersExport.php)

```php
<?php

namespace App\Exports;

use App\Models\AcademicPeriod;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        // Increase execution time for large exports
        ini_set('max_execution_time', 300);
        ini_set('memory_limit', '256M');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $requestData = $this->data;
        $academicPeriod = AcademicPeriod::where('id', $requestData['academic_period_id'])
            ->select('id', 'start_year')
            ->get();
        return $academicPeriod;
    }

    public function headings(): array
    {
        return [
            'id',
            'start_year',
        ];
    }
}
```

**Key Points:**
- `FromCollection` requires a `collection()` method returning a Laravel Collection
- Data can come from Eloquent models, queries, or manual arrays wrapped in `collect()`
- Constructor can accept parameters to filter/control data

---

## FromArray - Export from Array

**File:** [`api/app/Exports/MarkerExport.php`](api/app/Exports/MarkerExport.php)

```php
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class MarkerExport implements FromArray, WithHeadings, WithEvents
{
    protected $params;

    public function __construct($params)
    {
        $this->params = is_array($params) ? $params : $params->toArray();
    }

    /**
    * @return array
    */
    public function array(): array
    {
        return $this->params;
    }

    public function headings(): array
    {
        return [
            'Academic Period', 'Examination', 'OpenEMIS ID', 'Marker ID', 'Classification',
            'First Name', 'Middle Name', 'Third Name', 'Last Name', 'Gender',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $rowCount = $event->sheet->getHighestRow();
                $customText = 'Examination Marker Report: ' . date('Y-m-d H:i:s');
                $event->sheet->setCellValue('A' . ($rowCount + 2), $customText);
            },
        ];
    }
}
```

**Key Points:**
- `FromArray` uses `array()` method instead of `collection()`
- Data is already an array (often processed/prepared elsewhere)
- Commonly used when data comes from complex queries or calculations

---

## WithHeadings - Adding Headers

**File:** [`api/app/Exports/CandidatePerCentreExport.php`](api/app/Exports/CandidatePerCentreExport.php)

```php
class CandidatePerCentreExport implements FromArray, WithHeadings, WithStrictNullComparison, ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'Exam Code',
            'Centre Code',
            'Centre Name',
            'Centre Area',
            'Ownership',
            'Female Candidates',
            'Male Candidates',
            'Part-time Candidates',
            'Full-time Candidates',
            'Total Candidates',
        ];
    }
}
```

**Key Points:**
- Headers appear in the first row of the export
- Array must match the number of columns in your data

---

## WithMapping - Data Transformation

**File:** [`api/app/Exports/StudentsExport.php`](api/app/Exports/StudentsExport.php)

```php
class StudentsExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Student::with(['user', 'classes'])
            ->has('user')
            ->get();
    }

    public function map($student): array
    {
        return [
            $student->id,
            $student->user->name,
            $student->user->mobile,
            $student->user->email,
            $student->class_id,
            $student->gpa,
            $student->classes->name,
            $student->classes->batch,
            $student->attendencePercentage($student->id),
            $student->created_by,
            $student->created,
        ];
    }

    public function headings(): array
    {
        return [
            'Student ID',
            'Name',
            'Mobile',
            'Email',
            'Class ID',
            'GPA',
            'Class',
            'Batch',
            'Attendance',
            'Created By',
            'Created',
        ];
    }
}
```

**Key Points:**
- `WithMapping` transforms each row before export
- Useful for accessing relationships, computed values, formatting
- Runs for each row in the collection

---

## WithTitle - Sheet Naming

```php
class StudentsExport implements FromCollection, WithTitle
{
    public function title(): string
    {
        return "Student List";
    }
}
```

**Key Points:**
- Sets the name of the worksheet/tab
- Only one sheet per export by default

---

## WithCustomStartCell - Start Data at Specific Cell

```php
class StudentsExport implements FromCollection, WithCustomStartCell
{
    public function startCell(): string
    {
        return 'A5';  // Data starts at row 5, leaving space for headers/logo
    }
}
```

**Key Points:**
- Useful when you want to add content above the data (logos, titles, etc.)
- Works with `WithEvents` to format the space above

---

## WithEvents - Advanced Formatting

### AfterSheet Event - Styling After Data is Written

```php
use Maatwebsite\Excel\Events\AfterSheet;

class StudentsExport implements FromCollection, WithEvents
{
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ($event) {
                $sheet = $event->sheet->getDelegate();

                // Get the underlying PhpSpreadsheet sheet
                $sheet->mergeCells('A1:D4');
                $sheet->setCellValue('E2', 'BOINT - 32');
                $sheet->setCellValue('E3', 'Student Export');

                // Style the title
                $sheet->getStyle('E2')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 18,
                        'color' => ['rgb' => '538dd5'],
                    ],
                    'alignment' => ['horizontal' => 'center'],
                ]);

                // Auto-size columns
                foreach (range('A', 'K') as $column) {
                    $sheet->getColumnDimension($column)->setAutoSize(true);
                }

                // Get last row with data
                $lastRow = $sheet->getHighestRow();
            },
        ];
    }
}
```

### BeforeWriting Event - Modify Before Save

**File:** [`api/app/Exports/TemplateExport.php`](api/app/Exports/TemplateExport.php)

```php
use Maatwebsite\Excel\Events\BeforeWriting;

class TemplateExport implements WithMultipleSheets, WithEvents
{
    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $spreadsheet = $event->writer->getDelegate();
                $dataSheet = $spreadsheet->getSheetByName('Data');
                $fillSheet = $spreadsheet->getSheetByName('Fill Data');

                if (!$dataSheet || !$fillSheet)
                    return;

                // Add Named Range for dropdown
                $spreadsheet->addNamedRange(
                    new \PhpOffice\PhpSpreadsheet\NamedRange(
                        'ClassBatchList',
                        $dataSheet,
                        "\$A\$7:\$A\$" . ($this->classes->count() + 6)
                    )
                );

                // Apply data validation
                $validation = $fillSheet->getDataValidation('E6:E100');
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setFormula1('ClassBatchList');
            },
        ];
    }
}
```

**Key Points:**
- `AfterSheet` - Runs after all data is written
- `BeforeWriting` - Runs before file is saved (good for validation setup)
- `BeforeExport` - Runs before export starts
- `AfterExport` - Runs after export is complete
- Access the underlying PhpSpreadsheet object via `$event->sheet->getDelegate()`

---

## WithDrawings - Adding Images

```php
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;

class StudentsExport implements FromCollection, WithDrawings
{
    // Method 1: From file path
    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Bespoke OpenEMIS Interns');
        $drawing->setPath(public_path('/images/logo.png'));
        $drawing->setHeight(100);
        $drawing->setCoordinates('B1');

        return $drawing;
    }
}
```

### Adding Image from Memory (in AfterSheet)

```php
AfterSheet::class => function ($event) {
    $sheet = $event->sheet->getDelegate();
    
    $imageResource = imagecreatefrompng(public_path('/images/logo.png'));
    
    if ($imageResource) {
        $drawing = new MemoryDrawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Bespoke OpenEMIS Interns');
        $drawing->setImageResource($imageResource);
        $drawing->setRenderingFunction(MemoryDrawing::RENDERING_PNG);
        $drawing->setMimeType(MemoryDrawing::MIMETYPE_PNG);
        $drawing->setHeight(100);
        $drawing->setCoordinates('A1');
        $drawing->setWorksheet($sheet);
    }
}
```

---

## ShouldAutoSize - Auto Column Width

```php
class CandidatePerCentreExport implements FromArray, WithHeadings, ShouldAutoSize
{
    // Simply implementing the interface enables auto-sizing
}
```

**Key Points:**
- Automatically adjusts column width based on content
- Can be combined with manual sizing in `AfterSheet`

---

## WithStrictNullComparison - Handle Nulls

```php
class CandidatePerCentreExport implements FromArray, WithHeadings, WithStrictNullComparison
{
    // Properly handles null values in the export
}
```

**Key Points:**
- Without this, null values might appear as empty strings or cause comparison issues
- Recommended for reports with potentially null data

---

## WithChunkReading - Large Data Exports

**File:** [`api/app/Exports/RegistrationCandidatesExport.php`](api/app/Exports/RegistrationCandidatesExport.php)

```php
class RegistrationCandidatesExport implements FromArray, WithHeadings, WithChunkReading
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function chunkSize(): int
    {
        return 1000;  // Process 1000 rows at a time
    }
}
```

**Key Points:**
- Processes data in chunks to reduce memory usage
- Essential for exports with thousands of rows
- Works with both `FromCollection` and `FromArray`

---

## WithMultipleSheets - Multiple Sheets

**File:** [`api/app/Exports/TemplateExport.php`](api/app/Exports/TemplateExport.php)

```php
<?php

namespace App\Exports;

use App\Models\Classes;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;

class TemplateExport implements WithMultipleSheets, WithEvents
{
    protected $classes;

    public function __construct()
    {
        $this->classes = Classes::select('id', 'name', 'batch')->get();
    }

    public function sheets(): array
    {
        return [
            new FillSheet($this->classes->count()),  // First sheet
            new DataSheet($this->classes),            // Second sheet
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                // Can access all sheets here
                $spreadsheet = $event->writer->getDelegate();
                $dataSheet = $spreadsheet->getSheetByName('Data');
                $fillSheet = $spreadsheet->getSheetByName('Fill Data');
                // Add named ranges, validations, etc.
            },
        ];
    }
}
```

**Key Points:**
- Returns an array of sheet objects
- Each sheet is a separate export class
- Use `WithTitle` in each sheet class to name them
- `BeforeWriting` event can access all sheets

---

## Data Validation - Dropdown Lists

**File:** [`api/app/Exports/FillSheet.php`](api/app/Exports/FillSheet.php)

### Creating a Template with Dropdowns

```php
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class FillSheet implements FromArray, WithHeadings, WithEvents
{
    private const DATA_START_ROW = 6;
    private const DATA_END_ROW = 100;

    public function headings(): array
    {
        return [
            'Name',           // col A
            'Mobile',         // col B
            'Email',          // col C
            'GPA (0-10)',    // col D
            'Class - Batch', // col E - dropdown
            'Class ID',       // col F - auto filled via VLOOKUP
            'Gender',         // col G - dropdown
        ];
    }

    public function array(): array
    {
        return [];  // Empty for template
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ($event) {
                $sheet = $event->sheet->getDelegate();
                
                // VLOOKUP formulas for auto-fill
                for ($row = self::DATA_START_ROW; $row <= self::DATA_END_ROW; $row++) {
                    $sheet->setCellValue(
                        "F{$row}",
                        "=IFERROR(VLOOKUP(E{$row},'Data'!\$A\$7:\$B\$20,2,0),\"\")"
                    );
                }
            },
        ];
    }
}
```

### In TemplateExport (BeforeWriting) - Apply Validation

```php
BeforeWriting::class => function (BeforeWriting $event) {
    $spreadsheet = $event->writer->getDelegate();
    
    // Apply dropdown validation
    $validation = $fillSheet->getDataValidation('E6:E100');
    $validation->setType(DataValidation::TYPE_LIST);
    $validation->setErrorStyle(DataValidation::STYLE_STOP);
    $validation->setAllowBlank(true);
    $validation->setShowErrorMessage(true);
    $validation->setErrorTitle('Invalid Input');
    $validation->setError('Please select a valid option.');
    $validation->setFormula1('ClassBatchList');  // Named range
    $validation->setShowDropDown(false);

    // Simple dropdown with hardcoded values
    $genderValidation = $fillSheet->getDataValidation('G6:G100');
    $genderValidation->setType(DataValidation::TYPE_LIST);
    $genderValidation->setFormula1('"Male,Female"');
    $genderValidation->setShowDropDown(false);
}
```

---

## Exportable Trait

**File:** [`api/app/Exports/CertificateExport.php`](api/app/Exports/CertificateExport.php)

```php
<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\Exportable;

class CertificateExport implements FromArray
{
    use Exportable;  // Enables chainable methods

    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        // ... return data
    }
}
```

**Usage with Exportable:**
```php
// Download directly
return (new CertificateExport($data))->download('certificates.xlsx');

// Store to disk
return (new CertificateExport($data))->store('exports/certs.xlsx');

// Queue (requires queue setup)
return (new CertificateExport($data))->queue('exports/certs.xlsx');
```

---

## Controller Integration

**File:** [`api/app/Http/Controllers/StudentController.php`](api/app/Http/Controllers/StudentController.php)

```php
<?php

namespace App\Http\Controllers;

use App\Exports\StudentsExport;
use App\Exports\TemplateExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function export(Request $request)
    {
        if ($request->input('type') == 'xlsx') {
            return Excel::download(
                new StudentsExport,
                'students.xlsx',
                ExcelWriter::XLSX,
            );
        } else {
            return Excel::download(
                new StudentsExport,
                'students.ods',
                ExcelWriter::ODS,
            );
        }
    }

    public function template()
    {
        return Excel::download(new TemplateExport(), 'students_template.xlsx'); 
    }
}
```

### Export with Constructor Data

```php
public function generateReport(Request $request)
{
    $data = $request->all();
    
    // Pass data to export constructor
    return Excel::download(
        new MarkerExport($data),
        'markers.xlsx'
    );
}
```

---

## Traits for Reusable Code

**File:** [`api/app/Traits/HasSheetHeader.php`](api/app/Traits/HasSheetHeader.php)

```php
<?php

namespace App\Traits;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

trait HasSheetHeader
{
    private function setupHeader($sheet, string $subtitle): void
    {
        $sheet->mergeCells('A1:D4');
        $sheet->setCellValue('E2', 'BOINT - 32');
        $sheet->setCellValue('E3', $subtitle);

        $sheet->getStyle('E2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 18, 'color' => ['rgb' => '538dd5']],
            'alignment' => ['horizontal' => 'center'],
        ]);
    }

    private function setupColumnWidths($sheet, $start, $end): void
    {
        foreach (range($start, $end) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
    }

    private function setupTable($sheet, string $range, string $tableName): void
    {
        // Parse range
        [$start, $end] = explode(':', $range);
        
        // Style header row
        $sheet->getStyle($range)->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
        ]);

        // Add Excel table
        $table = new \PhpOffice\PhpSpreadsheet\Worksheet\Table($range, $tableName);
        $style = new \PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle();
        $style->setTheme(\PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle::TABLE_STYLE_MEDIUM2);
        $table->setStyle($style);
        $sheet->addTable($table);
    }
}
```

### Using the Trait

```php
use App\Traits\HasSheetHeader;

class StudentsExport implements FromCollection, WithEvents
{
    use HasSheetHeader;

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function ($event) {
                $sheet = $event->sheet->getDelegate();
                $this->setupHeader($sheet, 'Student Export');
                $this->setupColumnWidths($sheet, 'A', 'K');
                $lastRow = $sheet->getHighestRow();
                $this->setupTable($sheet, 'A5:K' . $lastRow, 'StudentsTable');
            },
        ];
    }
}
```

---

## Common Interfaces Summary

| Interface | Method | Use Case |
|-----------|--------|----------|
| `FromCollection` | `collection()` | Eloquent models, queries |
| `FromArray` | `array()` | Pre-processed arrays |
| `WithHeadings` | `headings()` | Header row |
| `WithMapping` | `map($row)` | Transform each row |
| `WithTitle` | `title()` | Sheet name |
| `WithCustomStartCell` | `startCell()` | Data start position |
| `WithEvents` | `registerEvents()` | Hook into lifecycle |
| `WithDrawings` | `drawings()` | Add images |
| `ShouldAutoSize` | - | Auto-size columns |
| `WithStrictNullComparison` | - | Proper null handling |
| `WithChunkReading` | `chunkSize()` | Large datasets |
| `WithMultipleSheets` | `sheets()` | Multiple worksheets |
| `Exportable` | - | Chainable methods |

---

## File Structure in This Project

```
api/app/Exports/
├── StudentsExport.php         # Basic FromCollection example
├── UsersExport.php           # FromCollection with constructor
├── MarkerExport.php         # FromArray with events
├── CandidatePerCentreExport.php  # Complex query-based export
├── TemplateExport.php       # WithMultipleSheets
├── FillSheet.php            # Template with validation
├── DataSheet.php            # Data reference sheet
├── CertificateExport.php    # Exportable trait example
├── RegistrationCandidatesExport.php  # WithChunkReading
└── ... (60+ more export files)
```

---

## Quick Reference - Creating an Export

### 1. Simple Export
```php
class SimpleExport implements FromCollection, WithHeadings
{
    public function collection() { return Model::all(); }
    public function headings() { return ['Column1', 'Column2']; }
}
```

### 2. Export with Transformation
```php
class TransformedExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection() { return Model::with('relation')->get(); }
    public function map($row) { return [$row->id, $row->relation->name]; }
    public function headings() { return ['ID', 'Name']; }
}
```

### 3. Styled Export
```php
class StyledExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection() { return Model::all(); }
    public function headings() { return ['Col1', 'Col2']; }
    public function registerEvents() {
        return [AfterSheet::class => function($e) {
            // Style code
        }];
    }
}
```

### 4. Template with Dropdowns
```php
class MyTemplate implements WithMultipleSheets
{
    public function sheets() {
        return [new DataSheet(), new FillSheet()];
    }
}
```

---

## Next Steps

- Explore the Imports documentation (coming next)
- Practice by modifying existing exports
- Try creating a new export from scratch
- Experiment with PhpSpreadsheet styling options
