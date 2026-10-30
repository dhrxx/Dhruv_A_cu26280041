# Linux Lab 9: Disk & Storage Management

**Subject:** Linux Lab  |  **Subject Code:** UAC02P  |  **Worksheet:** UAC028P-AS12.pdf

## Aim

To inspect disk usage and block devices, and practice mounting/unmounting a dedicated storage device or test partition.

## Environment

Kali Linux / Ubuntu / Red Hat Linux terminal

## Notes before starting

CRITICAL SAFETY RULE: Never run partitioning or mkfs commands on your MacBook system disk or a drive containing needed data. The /dev/sdX and /dev/sdX1 names below are placeholders, not literal device names. First identify a dedicated disposable USB drive or virtual disk with lsblk; confirm its size and model before any write operation. Back up data before modifying any storage device.

---

## Solutions

### Question 1. Display mounted filesystems and disk-space usage.

**Command(s):**

```bash
df
```

**Expected result / observation:**

A table lists filesystem, 1K blocks, used, available, use percentage, and mount point.

### Question 2. Show disk usage in human-readable form and identify key fields.

**Command(s):**

```bash
df -hT
```

**Expected result / observation:**

Human-readable units such as GiB are shown, along with filesystem type (Type), Size, Used, Avail, Use%, and Mounted on.

### Question 3. Show the size of the current directory and contents.

**Command(s):**

```bash
du -sh .
```

**Expected result / observation:**

One summarized size is printed for the current directory. -s summarizes and -h makes the size human-readable.

### Question 4. List immediate directory sizes and identify large directories.

**Command(s):**

```bash
du -h --max-depth=1 . 2>/dev/null | sort -h
# Largest first:
du -h --max-depth=1 . 2>/dev/null | sort -hr | head -10
```

**Expected result / observation:**

The summary shows each immediate subdirectory. Permission-denied messages are redirected away in this example.

### Question 5. Display the total size of a selected directory including subdirectories.

**Command(s):**

```bash
du -sh /var/log
```

**Expected result / observation:**

The command reports the combined size of /var/log. Replace it with a directory you are allowed to inspect.

### Question 6. List available block devices, disks, and partitions.

**Command(s):**

```bash
lsblk
```

**Expected result / observation:**

The tree shows block-device names, sizes, types, and mount points, helping distinguish disks (disk) from partitions (part).

### Question 7. Identify device names, sizes, types, and mount points.

**Command(s):**

```bash
lsblk -o NAME,TYPE,SIZE,FSTYPE,MOUNTPOINTS,MODEL
```

**Expected result / observation:**

Check NAME, TYPE, SIZE, filesystem type, mountpoints, and model. Use this information to identify only a dedicated test device.

### Question 8. Display extra filesystem information for block devices.

**Command(s):**

```bash
lsblk -f
```

**Expected result / observation:**

The output includes FSTYPE, LABEL, UUID, and mount point when available.

### Question 9. Show current mounts and their mount points.

**Command(s):**

```bash
findmnt
```

**Expected result / observation:**

findmnt lists mounted filesystems and their targets. Use findmnt -T /path to identify the filesystem containing a particular path.

### Question 10. Create /mnt/labdisk and verify its existence/permissions.

**Command(s):**

```bash
sudo mkdir -p /mnt/labdisk
ls -ld /mnt/labdisk
```

**Expected result / observation:**

The directory exists and its permission/owner details are shown. Creating a mountpoint alone does not mount a device.

### Question 11. Mount a dedicated test partition at /mnt/labdisk and verify.

**Command(s):**

```bash
# Replace /dev/sdX1 with the verified dedicated test partition only.
sudo mount /dev/sdX1 /mnt/labdisk
findmnt -M /mnt/labdisk
lsblk -f
```

**Expected result / observation:**

findmnt should show the selected device mounted at /mnt/labdisk. If the partition has no filesystem, stop and create one only if it is disposable and intended for practice.

### Question 12. Show space on the mounted test filesystem.

**Command(s):**

```bash
df -hT /mnt/labdisk
```

**Expected result / observation:**

The filesystem mounted at /mnt/labdisk is displayed with total, used, available space, type, and use percentage.

### Question 13. Create a test file and verify it resides on the mounted filesystem.

**Command(s):**

```bash
printf "Storage lab test\n" | sudo tee /mnt/labdisk/lab-test.txt
ls -l /mnt/labdisk/lab-test.txt
findmnt -T /mnt/labdisk/lab-test.txt
```

**Expected result / observation:**

The test file is listed, and findmnt identifies the filesystem holding it. Verify the mount is active before writing.

### Question 14. Unmount /mnt/labdisk and verify the mount is gone.

**Command(s):**

```bash
cd ~
sudo umount /mnt/labdisk
findmnt -M /mnt/labdisk || echo "Not mounted at /mnt/labdisk"
lsblk -f
```

**Expected result / observation:**

The mount is gone. The directory remains as an empty mount point; files on the underlying root filesystem may become visible after unmounting.

### Question 15. Explain and safely resolve a busy mount when unmounting.

**Command(s):**

```bash
sudo fuser -vm /mnt/labdisk
# Close shells/apps using the mount; in any shell inside it run:
cd ~
# Then retry:
sudo umount /mnt/labdisk
```

**Expected result / observation:**

A busy mount means a process has its current directory or an open file on that filesystem. Identify and close the relevant process/application; do not force-unmount as the first step.

### Question 16. Display a selected disk partition table.

**Command(s):**

```bash
# Replace /dev/sdX with the verified dedicated test disk, not a partition.
sudo fdisk -l /dev/sdX
```

**Expected result / observation:**

fdisk prints the disk size, sector information, and partition table. Confirm the device model and capacity match your disposable test disk.

### Question 17. Create a practice partition on a dedicated test disk and verify it.

**Command(s):**

```bash
# DESTRUCTIVE IF THE WRONG DISK IS SELECTED. Use only an empty disposable disk.
lsblk -o NAME,SIZE,TYPE,FSTYPE,MOUNTPOINTS,MODEL
sudo fdisk /dev/sdX
# Inside fdisk: p (print), n (new), accept the safe defaults for a practice partition, p (print), w (write).
sudo partprobe /dev/sdX
lsblk /dev/sdX
```

**Expected result / observation:**

After writing the table, lsblk should show the new partition (for example /dev/sdX1). Device naming may differ. Do not use this workflow on the system disk.

### Question 18. Create a filesystem on a dedicated practice partition, mount and verify it.

**Command(s):**

```bash
# DESTRUCTIVE: this erases filesystem metadata on the target partition. Confirm the target is a disposable partition.
sudo mkfs.ext4 /dev/sdX1
sudo mkdir -p /mnt/labdisk
sudo mount /dev/sdX1 /mnt/labdisk
df -hT /mnt/labdisk
lsblk -f
findmnt -M /mnt/labdisk
```

**Expected result / observation:**

All three inspection commands should agree on the mounted device and ext4 filesystem. Never run mkfs against a partition containing required data.

### Question 19. Unmount and verify mountpoint/device information afterward.

**Command(s):**

```bash
cd ~
sudo umount /mnt/labdisk
findmnt -M /mnt/labdisk || echo "Mountpoint is not mounted"
lsblk -f
```

**Expected result / observation:**

The test partition remains visible in lsblk but no longer has /mnt/labdisk as a mount point.

### Question 20. Complete storage workflow from inspection through cleanup.

**Command(s):**

```bash
lsblk -o NAME,SIZE,TYPE,FSTYPE,MOUNTPOINTS,MODEL
df -hT
# After identifying a dedicated test partition only:
sudo mount /dev/sdX1 /mnt/labdisk
findmnt -M /mnt/labdisk
df -hT /mnt/labdisk
printf "Lab data\n" | sudo tee /mnt/labdisk/lab-test.txt
cat /mnt/labdisk/lab-test.txt
cd ~
sudo umount /mnt/labdisk
findmnt -M /mnt/labdisk || echo "Not mounted"
lsblk -f
```

**Expected result / observation:**

The workflow checks the storage state before and after mounting. Keep or delete the test partition only according to your lab instructions; do not remove or format any system partition.

---

## Result

All 20 questions in Linux Lab 9: Disk & Storage Management have been addressed with commands and expected observations. Record your actual terminal output and screenshots separately if your instructor requires evidence.
