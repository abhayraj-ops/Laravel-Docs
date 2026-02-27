# Import concerns | Laravel Excel

**URL:** https://docs.laravel-excel.com/3.1/imports/concerns.html

**Description:** Supercharged Excel exports and imports in Laravel

---

# Import concerns

| Interface | Explanation | Documentation |
| --- | --- | --- |
| Maatwebsite\Excel\Concerns\ToCollection | Import to a collection. | Importing to collections |
| Maatwebsite\Excel\Concerns\ToArray | Import to an array. |  |
| Maatwebsite\Excel\Concerns\ToModel | Import each row to a model. | Importing to models |
| Maatwebsite\Excel\Concerns\OnEachRow | Handle each row manually. |  |
| Maatwebsite\Excel\Concerns\WithBatchInserts | Insert models in batches. | Batch inserts |
| Maatwebsite\Excel\Concerns\WithChunkReading | Read the sheet in chunks. | Chunk reading |
| Maatwebsite\Excel\Concerns\WithHeadingRow | Define a row as heading row. | Heading row |
| Maatwebsite\Excel\Concerns\WithGroupedHeadingRow | Allows columns sharing the same header key to group values in array | Heading row |
| Maatwebsite\Excel\Concerns\WithLimit | Define a limit of the amount of rows that need to be imported. |  |
| Maatwebsite\Excel\Concerns\WithCustomValueBinder | Define a custom value binder. | Custom Formatting Values |
| Maatwebsite\Excel\Concerns\WithMappedCells | Define a custom cell mapping. | Mapped Cells |
| Maatwebsite\Excel\Concerns\WithMapping | Map the row before being called in ToModel/ToCollection. |  |
| Maatwebsite\Excel\Concerns\WithMultipleSheets | Enable multi-sheet support. Each sheet can have its own concerns (except this one). | Multiple Sheets |
| Maatwebsite\Excel\Concerns\WithCalculatedFormulas | Calculates the formulas when importing. By default this is disabled. |  |
| Maatwebsite\Excel\Concerns\WithEvents | Register events to hook into the PhpSpreadsheet process. | Events |
| Maatwebsite\Excel\Concerns\WithCustomCsvSettings | Allows to run custom Csv settings for this specific importable. | Custom CSV Settings |
| Maatwebsite\Excel\Concerns\WithStartRow | Define a custom start row. |  |
| Maatwebsite\Excel\Concerns\WithProgressBar | Shows a progress bar when uploading via the console. | Progress Bar |
| Maatwebsite\Excel\Concerns\WithUpserts | Allows to upsert models. | Upserting models |
| Maatwebsite\Excel\Concerns\WithUpsertColumns | Allows upsert columns definition. | Upserting with specific columns |
| Maatwebsite\Excel\Concerns\WithValidation | Validates each row against a set of rules. | Row Validation |
| Maatwebsite\Excel\Concerns\SkipsEmptyRows | Skips empty rows. | Row Validation |
| Maatwebsite\Excel\Concerns\SkipsOnFailure | Skips on validation errors. | Row Validation |
| Maatwebsite\Excel\Concerns\SkipsOnError | Skips on database exceptions. | Row Validation |
| Maatwebsite\Excel\Concerns\WithColumnLimit | Allows setting an end column |  |
| Maatwebsite\Excel\Concerns\WithReadFilter | Allows defining a custom read filter |  |

### Traits

| Trait | Explanation | Documentation |
| --- | --- | --- |
| Maatwebsite\Excel\Concerns\Importable | Add import/queue abilities right on the import class itself. | Importables |
| Maatwebsite\Excel\Concerns\RegistersEventListeners | Auto-register the available event listeners. | Auto register event listeners |
