# Lifecycle | Laravel Excel

**URL:** https://docs.laravel-excel.com/3.1/architecture

**Description:** Supercharged Excel exports and imports in Laravel

# #Lifecycle

ID: `lifecycle`

- Introduction
- Exports Lifecycle OverviewExport ObjectPassing on the Export objectHandling the Export objectPassing on to PhpSpreadsheetCreating a Response
- Imports Lifecycle OverviewImport ObjectPassing on the Import objectHandling the Import object

- Export Object
- Passing on the Export object
- Handling the Export object
- Passing on to PhpSpreadsheet
- Creating a Response

- Import Object
- Passing on the Import object
- Handling the Import object

## #Introduction

ID: `introduction`

When using a package in your application, it's good to understand how the package functions behind the scenes.
Understanding the behind-the-scenes will make you feel more comfortable and confident using the maximum potential of the tool.

The goal of this page is to give you a high-level overview of how Laravel Excel works.

## #Exports Lifecycle Overview

ID: `exports-lifecycle-overview`

This section will try to give you an overview of how the export works behind the scenes.

### #Export Object

ID: `export-object`

Everything starts with theExportobject. This objectencapsulatesyour entire export logic.
It both configures and handles the export logic.

A simple example of an export object is:

```
<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    public function collection()
    {
        return User::all();
    }
}

```

If you want to read more about export objects, go to the architecture page ofexport objects.

### #Passing on the Export object

ID: `passing-on-the-export-object`

Next the Export object will be passed on to the Laravel Excel package. The main entry point for this is theMaatwebsite/Excel/Excelclass. This class can be called in multiple ways.

#### #Facade

ID: `facade`

The easiest way to work with theExcelclass is to use theMaatwebsite\Excel\Facades\Excelfacade.
If you useauto-discovery(opens new window), you can use the alias\Exceldirectly instead of using the fully qualified namespace.

[SVG Diagram: ]

#### #Dependency injection

ID: `dependency-injection`

You can inject theMaatwebsite/Excel/Excelmanager class into your class, either via constructor injection
or method injection in case of a controller.

```
<?php
use App\Exports\UsersExport;
use Maatwebsite\Excel\Excel;

class ExportController
{
    private $excel;

    public function __construct(Excel $excel)
    {
        $this->excel = $excel;
    }
    
    public function exportViaConstructorInjection()
    {
        return $this->excel->download(new UsersExport, 'users.xlsx');
    }
    
    public function exportViaMethodInjection(Excel $excel)
    {
        return $excel->download(new UsersExport, 'users.xlsx');
    }
}

```

#### #Contract

ID: `contract`

You can also use theMaatwebsite\Excel\Exporterinterface to decouple more from the concrete Excel manager implementation. The contract offers the same methods as theExcelclass.
It will make it easier to e.g. stub out the Exporter in your unit tests. TheExportercontract can be either injected via the constructor or a method in a controller.

```
use App\Exports\UsersExport;
use Maatwebsite\Excel\Exporter;

class ExportsController
{
    private $exporter;

    public function __construct(Exporter $exporter)
    {
        $this->exporter = $exporter;
    }
    
    public function export()
    {
        return $this->exporter->download(new UsersExport, 'users.xlsx');
    }
}

```

#### #Container binding

ID: `container-binding`

If you want to bind theMaatwebsite\Excel\Excelmanager to your own class via a container binding, you can use theexcelcontainer binding.

```
$this->app->bind(YourCustomExporter::class, function() {
    return new YourCustomExporter($this->app['excel']);
});

```

#### #Exportable trait

ID: `exportable-trait`

If you prefer a sprinkle of magic, you can use theMaatwebsite\Excel\Concerns\Exportabletrait in yourExportobject. This trait will expose a couple of methods that will make it possible to directly export anExportobject.

```
namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromCollection
{
    use Exportable;

    public function collection()
    {
        return User::all();
    }
}

```

You can now download the export without the need for thefacadeorExcelmanager.

```
return (new UsersExport)->download('users.xlsx');

```

Read more about the exportable trait in theexportablesdocs.

### #Handling the Export object

ID: `handling-the-export-object`

#### #Writer type detection

ID: `writer-type-detection`

After using one of the above methods to pass on theExportobject to theExcelmanager, it will try to figure out what export it needs to be generated to.
This will be either based on the extension of the file, the explicitly passed writer type. You can find the extension to writer type mapping in theexcel.phpconfig, in theextension_detectorsection.
In case no writer type can be detected, aMaatwebsite\Excel\Exceptions\NoTypeDetectedExceptionexception is thrown and the export process will be stopped.

#### #Starting the Writing process

ID: `starting-the-writing-process`

TheExcelmanager will then delegate the handling to theMaatwebsite\Excel\Writer. The first action of theWriteris to register the event listeners that are registered.
Next it will create a newPhpOffice\PhpSpreadsheet\Spreadsheetinstance that we will use to convert ourExportobject to.

The first event raised is theBeforeExportevent. This is raised just after theSpreadsheetinstance is created and allows early access to it.

#### #Multiple sheets

ID: `multiple-sheets`

Next theWriterwill determine if multiple sheets are configured, by checking for theMaatwebsite\Excel\Concerns\WithMultipleSheetsconcern.

Then it will delegate the further handling of each sheet to theMaatwebsite\Excel\Sheetclass.

#### #Processing the sheets

ID: `processing-the-sheets`

In theSheetclass, the most heavy lifting happens. It first will create aPhpOffice\PhpSpreadsheet\Worksheet\Worksheetinstance. Then it will raise theBeforeSheetevent which allows you to hook into the moment just before the sheet handling starts.

Then it will determine what kind of export we are dealing with:FromQuery,FromArray,FromCollectionorFromView. Based on that it will start the connected export process.

- FromViewwill pass on the renderedBladeview to PhpSpreadsheet'sHtmlReader. That Reader will turn the table html into Excel cells. It also handles some inline styles (color and background color) and col/rowspans.
- TheQuerypassed with theFromQuerywill automatically be chunked and each chunk will be appended to the Sheet. The chunking is done to limit the amount of Eloquent objects it needs to keep in memory. It greatly reduces memory usage.
- The entire array of Collection will directly be appended to the Sheet.

When theSheetstarts appending records, it will first call themap()method if theWithMappingconcern is used. This allows theExportobject to format the data before it is inserted.

Then it will handle column formatting (WithColumnFormattingconcern) and cell autosizing (ShouldAutoSize).

To close off the Sheet processing, it will raise aAfterSheetevent.

### #Passing on to PhpSpreadsheet

ID: `passing-on-to-phpspreadsheet`

After the sheets are processed, the writing process will start. The writing process is started by raising theBeforeWritingevent; this allows you to hook into the process of writing.
Next we will create a newPhpSpreadsheet Writerbased on the writer type that was determined. Then it will save it to a temporary file and return that filepath to theExcelmanager.

### #Creating a Response

ID: `creating-a-response`

TheExcelmanager basically has 2 types of responses, it either starts thedownloadprocess or it willstorethe file to disk.

#### #Download the file

ID: `download-the-file`

We will take the temporary file that was returned by theWriterand use Laravel'sResponseFactoryto create aSymfony\Component\HttpFoundation\BinaryFileResponse. When returning this response in your controller, it will start a download.

#### #Storing the file

ID: `storing-the-file`

The storing of the file will be handled by Laravel'sFilesystem. By default the file will be stored on yourdefaultdisk, but you can also pass a custom disk via theExcel::store()method.

## #Imports Lifecycle Overview

ID: `imports-lifecycle-overview`

This section will try to give you an overview of how the import works behind the scenes.

### #Import Object

ID: `import-object`

Everything starts with theImportobject. This objectencapsulatesyour entire import logic.
It both configures and handles the import logic.

A simple example of an import object is:

```
namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            User::create([
                'name' => $row[0],
            ]);
        }
    }
}

```

If you want to read more about imports objects, go to the architecture page ofimports objects.

### #Passing on the Import object

ID: `passing-on-the-import-object`

Next the Import object will be passed on to the Laravel Excel package. The main entry point for this is theMaatwebsite/Excel/Excelclass. This class can be called in the same way as outlined in the Export lifecycle.

#### #Contract

ID: `contract-2`

You can also use theMaatwebsite\Excel\Importerinterface to decouple more from the concrete Excel manager implementation. The contract offers the same methods as theExcelclass.
It will make it easier to e.g. stub out the Importer in your unit tests. TheImportercontract can be either injected via the constructor or the method of a controller.

```
use App\Imports\UsersImport;
use Maatwebsite\Excel\Importer;

class ImportsController
{
    private $importer;

    public function __construct(Importer $importer)
    {
        $this->importer = $importer;
    }
    
    public function import()
    {
        return $this->importer->import(new UsersImport, 'users.xlsx');
    }
}

```

#### #Importable trait

ID: `importable-trait`

If you prefer a sprinkle of magic, you can use theMaatwebsite\Excel\Concerns\Importabletrait in yourImportobject. This trait will expose a couple of methods that will make it possible to directly import anImportobject.

```
namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;

class UsersImport implements ToCollection
{
    use Importable;

    ...
}

```

You can now import without the need for thefacadeorExcelmanager.

```
(new UsersImport)->import('users.xlsx');

```

Read more about the importable trait in theimportablesdocs.

### #Handling the Import object

ID: `handling-the-import-object`

#### #Reader type detection

ID: `reader-type-detection`

After using one of the above methods to pass on theImportobject to theExcelmanager, it will try to figure out what reader type it is .
This will be either based on the extension of the file or the explicitly passed reader type. You can find the extension to reader type mapping in theexcel.phpconfig, in theextension_detectorsection.
In case no reader type can be detected, aMaatwebsite\Excel\Exceptions\NoTypeDetectedExceptionexception is thrown and the import process will be stopped.

#### #Starting the Reading process

ID: `starting-the-reading-process`

TheExcelmanager will then delegate the handling to theMaatwebsite\Excel\Reader. The first action of theReaderis to register the event listeners that are registered.
It will copy the file from Laravel'sFilesystemto the local filesystem, so PhpSpreadsheet can read it.
Next it will create a PhpSpreadsheetReaderbased on the reader type that was given and load the file into aPhpOffice\PhpSpreadsheet\Spreadsheetinstance.

Next it will create a newPhpOffice\PhpSpreadsheet\Spreadsheetinstance that we will use to read ourImportobject from.

The first event that is raised, is theBeforeImportevent. This is raised just after theSpreadsheetinstance is loaded and allows early access to it.

#### #Multiple sheets

ID: `multiple-sheets-2`

Next we will determine if we are dealing with multiple sheets. This is done based on theWithMultipleSheetsconcern.

#### #Processing the sheets

ID: `processing-the-sheets-2`

Then each Sheet gets processed. This process gets started off by raising theBeforeSheetevent.
Then it will either import it to a Collection, an array or handle each row as an Eloquent model.

- When usingToModel, each returned model will be persisted via Eloquent. When using this in combination withWithBatchInserts, it will defer the persistence till the batch is complete and then insert them as one batch in the database.
- When usingToCollectionorToArray, the entire dataset will be passed to the Import method and the user can determine itself how to use it.

The sheet handling is ended by raising theAfterSheetevent.


---

