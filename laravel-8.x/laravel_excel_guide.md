# Laravel Excel (Maatwebsite) Comprehensive Guide

## Overview

This guide explains how to build advanced Excel exports in Laravel using
**Maatwebsite Excel**.

Covers: - Basic exports - Grouped tables (Batch → Class) - Images -
Column widths - Charts - Events - Best practices

------------------------------------------------------------------------

## Installation

``` bash
composer require maatwebsite/excel
```

Publish config:

``` bash
php artisan vendor:publish --provider="Maatwebsite\Excel\ExcelServiceProvider"
```

Enable charts in `config/excel.php`:

``` php
'charts' => true,
```

------------------------------------------------------------------------

## Basic Export

### Export Class

``` php
use Maatwebsite\Excel\Concerns\FromCollection;

class StudentExport implements FromCollection
{
    public function collection()
    {
        return Student::all();
    }
}
```

### Controller

``` php
return Excel::download(new StudentExport, 'students.xlsx');
```

------------------------------------------------------------------------

## Export Interfaces

### FromCollection

Exports Laravel Collection.

``` php
implements FromCollection
```

------------------------------------------------------------------------

### WithMapping

Formats rows.

``` php
implements WithMapping
```

Example:

``` php
public function map($student): array
{
    return [
        $student->id,
        $student->user->name,
        $student->gpa
    ];
}
```

------------------------------------------------------------------------

### WithHeadings

Adds headers.

``` php
implements WithHeadings
```

``` php
public function headings(): array
{
    return [
        'ID',
        'Name',
        'GPA'
    ];
}
```

------------------------------------------------------------------------

## Auto Column Width

### ShouldAutoSize

``` php
implements ShouldAutoSize
```

Automatically resizes columns.

------------------------------------------------------------------------

## Manual Column Width

### WithColumnWidths

``` php
implements WithColumnWidths
```

``` php
public function columnWidths(): array
{
    return [
        'A' => 15,
        'B' => 25,
        'C' => 10
    ];
}
```

------------------------------------------------------------------------

## Grouped Tables (Batch → Class)

Do NOT use SQL groupBy.

Use Collection grouping:

``` php
$students = Student::with(['user','classes'])
->get()
->groupBy('classes.batch')
->map(fn($batch)=>$batch->groupBy('classes.name'));
```

### Build Rows

``` php
$rows = collect();

foreach($students as $batch=>$classes){

    $rows->push(["Batch $batch"]);

    foreach($classes as $class=>$list){

        $rows->push(["Class $class"]);

        $rows->push([
            'ID',
            'Name',
            'Email'
        ]);

        foreach($list as $student){

            $rows->push([
                $student->id,
                $student->user->name,
                $student->user->email
            ]);
        }

        $rows->push([]);
    }

    $rows->push([]);
}
```

------------------------------------------------------------------------

## Images (Logos)

### WithDrawings

``` php
implements WithDrawings
```

``` php
public function drawings()
{
    $drawing = new Drawing();

    $drawing->setPath(public_path('logo.png'));
    $drawing->setHeight(80);
    $drawing->setCoordinates('A1');

    return $drawing;
}
```

------------------------------------------------------------------------

## Start Cell

### WithCustomStartCell

``` php
implements WithCustomStartCell
```

``` php
public function startCell(): string
{
    return 'A5';
}
```

------------------------------------------------------------------------

## Styling

### WithEvents

Best way to style Excel.

``` php
implements WithEvents
```

``` php
public static function afterSheet(AfterSheet $event)
{
    $event->sheet->getDelegate()
        ->getColumnDimension('A')
        ->setAutoSize(true);
}
```

------------------------------------------------------------------------

## Pie Charts

### WithCharts

``` php
implements WithCharts
```

Steps:

1 Fetch attendance totals

2 Insert values

    Present 50
    Absent 10

3 Create chart

Charts use PhpSpreadsheet.

------------------------------------------------------------------------

## Full Example

    class StudentReportExport implements

    FromCollection,
    ShouldAutoSize,
    WithDrawings,
    WithCharts,
    WithEvents

------------------------------------------------------------------------

## Best Practices

### Use Collection grouping

Correct:

    ->get()->groupBy()

Wrong:

    groupBy() SQL

------------------------------------------------------------------------

### Use Events for Styling

Better than ShouldAutoSize.

------------------------------------------------------------------------

### Use Eager Loading

    with(['user','classes'])

Avoids N+1 queries.

------------------------------------------------------------------------

### Use Small Queries

Avoid loading unnecessary columns.

    select('id','user_id','class_id')

------------------------------------------------------------------------

## Recommended Structure

Export Class:

    StudentReportExport.php

Responsibilities:

-   Fetch Data
-   Format Data
-   Charts
-   Images
-   Styling

------------------------------------------------------------------------

## Debugging

### Missing Rows

Cause:

    groupBy SQL

Fix:

    Collection groupBy

------------------------------------------------------------------------

### Images Not Showing

Check:

    public_path()

------------------------------------------------------------------------

### Charts Not Showing

Check:

    'charts'=>true

------------------------------------------------------------------------

## Performance Tips

### Large Data

Use:

    FromQuery
    WithChunkReading

------------------------------------------------------------------------

### Memory Safe Export

    implements FromQuery

------------------------------------------------------------------------

## Useful Interfaces

  Interface             Purpose
  --------------------- ---------------
  FromCollection        Export data
  FromQuery             Large exports
  WithMapping           Format rows
  WithHeadings          Headers
  ShouldAutoSize        Auto width
  WithColumnWidths      Fixed width
  WithDrawings          Images
  WithCharts            Charts
  WithEvents            Styling
  WithCustomStartCell   Offset

------------------------------------------------------------------------

## Recommended Production Setup

    implements

    FromCollection,
    ShouldAutoSize,
    WithDrawings,
    WithCharts,
    WithEvents

------------------------------------------------------------------------

## End
