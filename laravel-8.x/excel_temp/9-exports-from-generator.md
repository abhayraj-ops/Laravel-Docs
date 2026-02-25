# From Generator | Laravel Excel

**URL:** https://docs.laravel-excel.com/3.1/exports/from-generator.html

**Description:** Supercharged Excel exports and imports in Laravel

---

# From Generator

Exports can be created from a PHP [generator (opens new window)](https://www.php.net/manual/en/class.generator.php) class, by using the `FromGenerator` concern.

*[SVG Diagram: Diagram]*

A generator allows you to write code that uses foreach to iterate over a set of data without needing to build an array in memory.

```php
namespace App\Exports;

use Generator;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromGenerator;

class DataExport implements FromGenerator
{
    use Exportable;

    public function generator(): Generator
    {
        for ($i = 1; $i <= 100; $i++) {
            yield [$i, $i+1, $i+2];
        }
    }
}
```

You can download the export in your controller:

```php
public function export() 
{
    return (new DataExport)->download('data.xlsx');
}
```
