# Export concerns | Laravel Excel

**URL:** https://docs.laravel-excel.com/3.1/exports/concerns.html

**Description:** Supercharged Excel exports and imports in Laravel

---

# Export concerns

| Interface | Explanation | Documentation |
| --- | --- | --- |
| Maatwebsite\Excel\Concerns\FromArray | Use an array to populate the export. | Exporting collections |
| Maatwebsite\Excel\Concerns\FromCollection | Use a Laravel Collection to populate the export. | Exporting collections |
| Maatwebsite\Excel\Concerns\FromGenerator | Use a generator to populate the export. | From Generator |
| Maatwebsite\Excel\Concerns\FromIterator | Use an iterator to populate the export. |  |
| Maatwebsite\Excel\Concerns\FromQuery | Use an Eloquent query to populate the export. | From Query |
| Maatwebsite\Excel\Concerns\FromView | Use a (Blade) view to to populate the export. | From View |
| Maatwebsite\Excel\Concerns\HasReferencesToOtherSheets | Allows precalculated values where one sheet has references to another sheet. | References to other sheets |
| Maatwebsite\Excel\Concerns\ShouldAutoSize | Auto-size the columns in the worksheet. | Auto size |
| Maatwebsite\Excel\Concerns\WithCharts | Allows to run one or multiple PhpSpreadsheet Chart instances. | Charts |
| Maatwebsite\Excel\Concerns\WithColumnFormatting | Format certain columns. | Formatting columns |
| Maatwebsite\Excel\Concerns\WithColumnWidths | Set Column widths. | Column widths |
| Maatwebsite\Excel\Concerns\WithCustomChunkSize | Allows Exportables to define their chunk size. |  |
| Maatwebsite\Excel\Concerns\WithCustomCsvSettings | Allows to run custom Csv settings for this specific exportable. | Custom CSV Settings |
| Maatwebsite\Excel\Concerns\WithCustomQuerySize | Allows Exportables that implement the FromQuery concern to provide their own custom query size. | Custom Query Size |
| Maatwebsite\Excel\Concerns\WithCustomStartCell | Allows to specify a custom start cell. Do note that this is only supported for FromCollection exports. | Custom start cell |
| Maatwebsite\Excel\Concerns\WithCustomValueBinder | Allows to specify a custom value binder. | Custom Value Binder |
| Maatwebsite\Excel\Concerns\WithDrawings | Allows to run one or multiple PhpSpreadsheet (Base)Drawing instances. | Drawings |
| Maatwebsite\Excel\Concerns\WithEvents | Register events to hook into the PhpSpreadsheet process. | Events |
| Maatwebsite\Excel\Concerns\WithHeadings | Prepend a heading row. | Adding a heading row |
| Maatwebsite\Excel\Concerns\WithMapping | Format the row before it's written to the file. | Mapping data |
| Maatwebsite\Excel\Concerns\WithMultipleSheets | Enable multi-sheet support. Each sheet can have its own concerns (except this one). | Multiple Sheets |
| Maatwebsite\Excel\Concerns\WithPreCalculateFormulas | Forces PhpSpreadsheet to recalculate all formulae in a workbook when saving, so that the pre-calculated values are immediately available to MS Excel or other office spreadsheet viewer when opening the file. |  |
| Maatwebsite\Excel\Concerns\WithProperties | Allows setting properties on the document. | Properties |
| Maatwebsite\Excel\Concerns\WithStrictNullComparison | Uses strict comparisons when testing cells for null value. | Strict null comparisons |
| Maatwebsite\Excel\Concerns\WithStyles | Allows setting styles on worksheets. | Styles |
| Maatwebsite\Excel\Concerns\WithTitle | Set the Workbook or Worksheet title. | Multiple Sheets |

### Traits

| Trait | Explanation | Documentation |
| --- | --- | --- |
| Maatwebsite\Excel\Concerns\Exportable | Add download/store abilities right on the export class itself. | Exportables |
| Maatwebsite\Excel\Concerns\RegistersEventListeners | Auto-register the available event listeners. | Auto register event listeners |
