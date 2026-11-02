# Linux Lab 11: Archive & Compression

**Subject:** Linux Lab\
**Subject Code:** UAC02P\
**Lab:** 11 --- Archive & Compression\
**Aim:** To understand and practice file archiving and compression using
`tar`, `gzip`, `gunzip`, `zip`, and `unzip` commands in Linux.\
**Environment:** Ubuntu Linux / Kali Linux / Red Hat Linux (Terminal)

------------------------------------------------------------------------

## 1. Create a directory and three text files

``` bash
mkdir -p archive_lab
echo "This is the first sample file." > archive_lab/file1.txt
echo "This is the second sample file." > archive_lab/file2.txt
echo "This is the third sample file." > archive_lab/file3.txt
```

Verify:

``` bash
ls -l archive_lab
```

## 2. Create `files.tar` and verify it

``` bash
tar -cvf files.tar archive_lab/file1.txt archive_lab/file2.txt archive_lab/file3.txt
ls -lh files.tar
```

## 3. List the contents of `files.tar` without extracting

``` bash
tar -tvf files.tar
```

## 4. Extract `files.tar` into `extracted_tar`

``` bash
mkdir -p extracted_tar
tar -xvf files.tar -C extracted_tar
ls -R extracted_tar
```

## 5. Create a gzip-compressed file

``` bash
gzip -k archive_lab/file1.txt
ls -l archive_lab
```

The `-k` option keeps the original file and creates `file1.txt.gz`.
Without `-k`, `gzip` normally replaces the original file with the
compressed file.

## 6. Decompress the gzip file using `gunzip`

``` bash
gunzip -k archive_lab/file1.txt.gz
ls -l archive_lab
```

The `-k` option keeps the compressed file as well as restoring the
original file.

## 7. Create `archive.tar.gz` containing the complete directory

``` bash
tar -czvf archive.tar.gz archive_lab
ls -lh archive.tar.gz
```

## 8. List the contents of `archive.tar.gz`

``` bash
tar -tzvf archive.tar.gz
```

## 9. Extract `archive.tar.gz` into a separate directory

``` bash
mkdir -p extracted_tar_gz
tar -xzvf archive.tar.gz -C extracted_tar_gz
ls -R extracted_tar_gz
```

## 10. Create `files.zip` containing three selected files

``` bash
zip files.zip archive_lab/file1.txt archive_lab/file2.txt archive_lab/file3.txt
ls -lh files.zip
```

## 11. List the contents of `files.zip`

``` bash
unzip -l files.zip
```

## 12. Extract `files.zip` into `extracted_zip`

``` bash
mkdir -p extracted_zip
unzip files.zip -d extracted_zip
ls -R extracted_zip
```

## 13. Extract only one selected file from a ZIP archive

``` bash
mkdir -p selected_file
unzip files.zip archive_lab/file2.txt -d selected_file
ls -R selected_file
```

Only `archive_lab/file2.txt` is extracted.

## 14. Create a ZIP archive containing an entire directory

``` bash
zip -r archive_lab.zip archive_lab
unzip -l archive_lab.zip
```

The `-r` option includes the directory and its contents recursively.

## 15. Create a gzip-compressed tar archive and explain the difference

``` bash
tar -czvf complete_backup.tar.gz archive_lab
```

**Difference:**

-   `tar` combines multiple files and directories into one archive. By
    itself, it does not compress the data.
-   `gzip` compresses a file. It is commonly used with `tar` to compress
    the archive.
-   In `tar -czvf`, `-c` creates an archive, `-z` applies gzip
    compression, `-v` displays progress, and `-f` specifies the archive
    filename.

## 16. Create a tar archive, add another file, and verify it

``` bash
tar -cvf update_archive.tar -C archive_lab file1.txt file2.txt

echo "This file was added later." > archive_lab/file4.txt
tar -rvf update_archive.tar -C archive_lab file4.txt

tar -tvf update_archive.tar
```

The `-r` option appends files to an existing, uncompressed tar archive.
It is not used to append directly to a `.tar.gz` archive.

## 17. Compare the contents and sizes of TAR and TAR.GZ archives

``` bash
tar -cvf comparison.tar archive_lab
tar -czvf comparison.tar.gz archive_lab

echo "TAR archive:"
tar -tf comparison.tar

echo "Compressed TAR archive:"
tar -tzf comparison.tar.gz

echo "File sizes:"
ls -lh comparison.tar comparison.tar.gz
```

Both archives contain the same directory contents. The `.tar.gz` file is
usually smaller because gzip compresses the tar archive. The actual size
difference depends on the data.

## 18. Decompress and extract TAR.GZ and ZIP archives separately

``` bash
mkdir -p restore_tar_gz restore_zip

tar -xzvf archive.tar.gz -C restore_tar_gz
unzip files.zip -d restore_zip

ls -R restore_tar_gz
ls -R restore_zip
```

## 19. Create a complete backup and restore it

``` bash
mkdir -p practice_directory
echo "Project notes" > practice_directory/notes.txt
echo "Backup demonstration" > practice_directory/info.txt

tar -czvf practice_backup.tar.gz practice_directory

mkdir -p restored_backup
tar -xzvf practice_backup.tar.gz -C restored_backup

diff -r practice_directory restored_backup/practice_directory
```

If `diff` produces no output, the restored directory contents match the
original directory.

## 20. Complete archive and compression workflow

``` bash
# Create sample directory and files
mkdir -p workflow_lab
echo "Linux archive practice" > workflow_lab/one.txt
echo "Learning gzip and gunzip" > workflow_lab/two.txt
echo "Learning zip and unzip" > workflow_lab/three.txt

# Create and verify a TAR archive
tar -cvf workflow.tar workflow_lab
tar -tvf workflow.tar

# Extract TAR archive
mkdir -p workflow_tar_extract
tar -xvf workflow.tar -C workflow_tar_extract
ls -R workflow_tar_extract

# Compress and decompress a text file
gzip -k workflow_lab/one.txt
ls -l workflow_lab/one.txt*
gunzip -k workflow_lab/one.txt.gz
ls -l workflow_lab/one.txt*

# Create and verify a compressed TAR archive
tar -czvf workflow.tar.gz workflow_lab
tar -tzvf workflow.tar.gz

# Extract compressed TAR archive
mkdir -p workflow_targz_extract
tar -xzvf workflow.tar.gz -C workflow_targz_extract
ls -R workflow_targz_extract

# Create and verify a ZIP archive
zip -r workflow.zip workflow_lab
unzip -l workflow.zip

# Extract ZIP archive
mkdir -p workflow_zip_extract
unzip workflow.zip -d workflow_zip_extract
ls -R workflow_zip_extract
```

------------------------------------------------------------------------

## Important command reference

  Command       Purpose
  ------------- ---------------------------------------------
  `tar -cvf`    Create an uncompressed TAR archive
  `tar -tvf`    List TAR archive contents
  `tar -xvf`    Extract a TAR archive
  `tar -czvf`   Create a gzip-compressed TAR archive
  `tar -tzf`    List a `.tar.gz` archive
  `tar -xzvf`   Extract a `.tar.gz` archive
  `tar -rvf`    Append files to an uncompressed TAR archive
  `gzip`        Compress a file using gzip
  `gunzip`      Decompress a gzip file
  `zip -r`      Create a ZIP archive recursively
  `unzip -l`    List ZIP archive contents
  `unzip`       Extract ZIP archive contents

## Result

All 20 Linux Lab 11 tasks were performed using `tar`, `gzip`, `gunzip`,
`zip`, and `unzip`. Archive creation, listing, extraction, compression,
decompression, backup, and restoration were verified using Linux
terminal commands.

------------------------------------------------------------------------

**Note:** Run the commands from a working directory where you have write
permission. If `zip` or `unzip` is not installed, install it using your
distribution's package manager. On Kali Linux or Ubuntu:

``` bash
sudo apt update
sudo apt install zip unzip
```
