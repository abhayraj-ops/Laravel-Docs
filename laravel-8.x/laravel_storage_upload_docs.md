# Laravel File Upload & Storage (Profile Photo System)

## Overview
This guide explains how to upload profile photos, store them using Laravel Storage, and save file paths in a polymorphic `attachments` table for Students and Teachers.

---

## Storage Basics

Laravel uses disks defined in `config/filesystems.php`.

**Public disk root:**
```
storage/app/public
```

Run once:
```
php artisan storage:link
```

This creates:
```
public/storage -> storage/app/public
```

---

## Uploading Files (Recommended)

### Using Uploaded File
```php
$path = $request->file('photo')->store('profile_photos', 'public');
```

or

```php
$path = Storage::disk('public')->putFile('profile_photos', $file);
```

**Returned value stored in DB:**
```
profile_photos/filename.jpg
```

---

## Custom Filename
```php
$filename = Str::uuid().'.jpg';

$path = Storage::disk('public')->putFileAs(
    'profile_photos',
    $file,
    $filename
);
```

---

## Saving to attachments table
```php
Attachment::create([
    'file_path' => $path,
    'attachment_type' => 'profile_photo',
    'attachmentable_id' => $student->id,
    'attachmentable_type' => Student::class,
    'created_by' => auth()->id(),
    'created' => now()
]);
```

---

## Delete Old Profile Photo
```php
$old = $student->attachments()
    ->where('attachment_type','profile_photo')
    ->first();

if ($old) {
    Storage::disk('public')->delete($old->file_path);
    $old->delete();
}
```

---

## Show Image URL
```php
$url = Storage::url($path);
```

Result:
```
http://localhost/storage/profile_photos/abc.jpg
```

---

## Error Handling (Best Practice)

### Enable exceptions
`config/filesystems.php`
```php
'public' => [
    'driver' => 'local',
    'throw' => true,
],
```

### Try/Catch Example
```php
DB::beginTransaction();

try {
    $path = Storage::disk('public')->putFile('profile_photos', $file);

    Attachment::create([...]);

    DB::commit();
} catch (\Exception $e) {
    DB::rollBack();

    if (isset($path)) {
        Storage::disk('public')->delete($path);
    }

    throw $e;
}
```

---

## Useful Methods

### Check file exists
```php
Storage::disk('public')->exists($path);
```

### Delete file
```php
Storage::disk('public')->delete($path);
```

### Download file
```php
return Storage::download($path);
```

---

## Best Practices

- Always store **relative path** in DB
- Use `disk('public')`
- Delete old profile photo
- Use UUID filenames
- Wrap DB + file in transaction
- Validate file type & size
- Never store full URL in DB

---

## Final Architecture

```
Controller
   ↓
Repository
   ↓
Storage::putFile()
   ↓
attachments table (polymorphic)
```
