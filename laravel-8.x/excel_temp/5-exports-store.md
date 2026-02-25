# Storing exports on disk | Laravel Excel

**URL:** https://docs.laravel-excel.com/3.1/exports/store.html

**Description:** Supercharged Excel exports and imports in Laravel

---

# Storing exports on disk

  - Default disk
  - Custom disks
  - Disk options
  - Note about queuing

Exports can easily be stored on any [filesystem (opens new window)](https://laravel.com/docs/master/filesystem) that Laravel supports.

*[SVG Diagram: Diagram]*

## Default disk

```php
public function storeExcel() 
{
    // Store on default disk
    Excel::store(new InvoicesExport(2018), 'invoices.xlsx');
}
```

## Custom disks

```php
public function storeExcel() 
{
    // Store on a different disk (e.g. s3)
    Excel::store(new InvoicesExport(2018), 'invoices.xlsx', 's3');
    
    // Store on a different disk with a defined writer type. 
    Excel::store(new InvoicesExport(2018), 'invoices.xlsx', 's3', Excel::XLSX);
}
```

## Disk options

If you want to pass some options to the disk, pass them to Excel::store() as the fifth parameter.

```php
public function storeExcel() 
{
    Excel::store(new InvoicesExport(2018), 'invoices.xlsx', 's3', null, [
        'visibility' => 'private',
    ]);
}
```

Laravel has a shortcut for private files:

```php
public function storeExcel() 
{
    Excel::store(new InvoicesExport(2018), 'invoices.xlsx', 's3', null, 'private');
}
```

File names cannot include certain characters:

  - < (less than)
  - > (greater than)
  - : (colon)
  - " (double quote)
  - / (forward slash)
  - \ (backslash)
  - | (vertical bar or pipe)
  - ? (question mark)
  - * (asterisk)

## Note about queuing

If you are storing the export using `Excel::queue()` or using the `ShouldQueue` interface, make sure to have a look at the [queuing docs (opens new window)](https://docs.laravel-excel.com/3.1/exports/queued.html)

*[SVG Diagram: Diagram]*
