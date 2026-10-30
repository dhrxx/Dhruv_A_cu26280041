# Linux Lab 10: File Systems

**Subject:** Linux Lab  |  **Subject Code:** UAC02P  |  **Worksheet:** UAC028P-AS13.pdf

## Aim

To create, mount, verify, configure, and safely check a dedicated Linux filesystem using mkfs, mount, /etc/fstab, UUIDs, and filesystem-checking utilities.

## Environment

Kali Linux / Ubuntu / Red Hat Linux terminal

## Notes before starting

CRITICAL SAFETY RULE: Only use a disposable test partition or virtual disk that contains no needed data. /dev/sdX1 is a placeholder; identify the real test partition with lsblk -f and confirm its device/model/size first. mkfs and partition edits can destroy data. Never run a normal e2fsck check on a mounted ext4 filesystem. Back up /etc/fstab before editing it.

---

## Solutions

### Question 1. List block devices and select a dedicated test partition.

**Command(s):**

```bash
lsblk -o NAME,TYPE,SIZE,FSTYPE,UUID,MOUNTPOINTS,MODEL
```

**Expected result / observation:**

Choose a partition from a dedicated test disk only. Record its exact device name, filesystem, UUID, and mountpoint.

### Question 2. Display filesystem type, UUID, and mountpoint for the selected partition.

**Command(s):**

```bash
# Replace /dev/sdX1 with the verified test partition.
sudo blkid /dev/sdX1
lsblk -f /dev/sdX1
```

**Expected result / observation:**

The output shows TYPE, UUID, and any mountpoint. A blank TYPE/UUID may indicate that no filesystem has been created.

### Question 3. Create ext4 on a dedicated test partition and verify it.

**Command(s):**

```bash
# DESTRUCTIVE: only on a disposable test partition with no needed data.
sudo mkfs.ext4 /dev/sdX1
sudo blkid /dev/sdX1
```

**Expected result / observation:**

mkfs reports the ext4 creation process; blkid should then show TYPE="ext4" and a new UUID.

### Question 4. Display detailed information about the new filesystem and its UUID.

**Command(s):**

```bash
sudo blkid /dev/sdX1
sudo tune2fs -l /dev/sdX1 | head -25
```

**Expected result / observation:**

blkid shows the UUID and filesystem type; tune2fs -l prints ext filesystem metadata, including volume name, UUID, block counts, and feature information.

### Question 5. Create /mnt/labfs as a mountpoint.

**Command(s):**

```bash
sudo mkdir -p /mnt/labfs
ls -ld /mnt/labfs
```

**Expected result / observation:**

The directory exists. Its existence alone does not indicate that a filesystem is mounted there.

### Question 6. Mount the new filesystem manually and verify it.

**Command(s):**

```bash
sudo mount /dev/sdX1 /mnt/labfs
findmnt -M /mnt/labfs
lsblk -f
```

**Expected result / observation:**

findmnt should show /dev/sdX1 mounted at /mnt/labfs with ext4. Replace the placeholder with the real device.

### Question 7. Show total, used, and available space for the mounted filesystem.

**Command(s):**

```bash
df -hT /mnt/labfs
```

**Expected result / observation:**

The row for /mnt/labfs shows filesystem type, total size, used space, available space, and use percentage.

### Question 8. Create a test file and verify it is on the test filesystem.

**Command(s):**

```bash
printf "Filesystem lab test\n" | sudo tee /mnt/labfs/lab-test.txt
cat /mnt/labfs/lab-test.txt
findmnt -T /mnt/labfs/lab-test.txt
```

**Expected result / observation:**

The file content is displayed, and findmnt confirms which mounted filesystem contains the file.

### Question 9. Unmount the filesystem and verify it is no longer mounted.

**Command(s):**

```bash
cd ~
sudo umount /mnt/labfs
findmnt -M /mnt/labfs || echo "Not mounted at /mnt/labfs"
```

**Expected result / observation:**

The mountpoint query should find no exact mount at /mnt/labfs. If it is busy, close shells/apps using it before retrying.

### Question 10. Display /etc/fstab and identify its fields.

**Command(s):**

```bash
cat /etc/fstab
```

**Expected result / observation:**

Each active line normally contains: device/UUID, mount point, filesystem type, mount options, dump flag, and fsck pass number. Lines beginning with # are comments.

### Question 11. Back up /etc/fstab and add a UUID-based entry for the test filesystem.

**Command(s):**

```bash
sudo cp -a /etc/fstab "/etc/fstab.backup-$(date +%F-%H%M%S)"
sudo blkid /dev/sdX1
sudoedit /etc/fstab
# Add one line, substituting the actual UUID:
# UUID=ACTUAL-UUID-HERE /mnt/labfs ext4 defaults,nofail 0 2
```

**Expected result / observation:**

Use the actual UUID from blkid, not the placeholder. sudoedit opens the file for careful editing. defaults,nofail is useful for an optional test device; the final 0 2 disables dump and requests a filesystem check order of 2.

### Question 12. Validate /etc/fstab syntax and test without rebooting.

**Command(s):**

```bash
sudo findmnt --verify --verbose
sudo mount -av
findmnt -M /mnt/labfs
```

**Expected result / observation:**

Correct entries should verify and mount without errors. Read any warnings carefully. Do not reboot until mount -a/findmnt tests succeed.

### Question 13. Verify automatic mounting from /etc/fstab.

**Command(s):**

```bash
sudo umount /mnt/labfs
sudo mount -av
findmnt -M /mnt/labfs
```

**Expected result / observation:**

If the fstab entry is correct, mount -av mounts the filesystem again without a reboot. A reboot is not necessary for this verification.

### Question 14. Use findmnt and df to verify device, mountpoint, type, and usage.

**Command(s):**

```bash
findmnt -M /mnt/labfs
df -hT /mnt/labfs
lsblk -f
```

**Expected result / observation:**

The device/UUID, mountpoint, filesystem type, and usage should be consistent across the three commands.

### Question 15. Compare the filesystem UUID against the UUID in /etc/fstab.

**Command(s):**

```bash
sudo blkid /dev/sdX1
grep -v "^[[:space:]]*#" /etc/fstab | grep -F "/mnt/labfs"
```

**Expected result / observation:**

The UUID in the active /etc/fstab entry must match the UUID reported by blkid exactly.

### Question 16. Check the ext4 filesystem after safely unmounting it.

**Command(s):**

```bash
cd ~
sudo umount /mnt/labfs
sudo e2fsck -f /dev/sdX1
```

**Expected result / observation:**

Run e2fsck only when the test filesystem is unmounted. Review any proposed repairs before agreeing; use this only on the disposable test partition.

### Question 17. Demonstrate mounted vs. unmounted checking and explain precautions.

**Command(s):**

```bash
findmnt -M /mnt/labfs
# Unmount before a real filesystem check:
cd ~
sudo umount /mnt/labfs
sudo e2fsck -f /dev/sdX1
```

**Expected result / observation:**

A normal e2fsck check should be performed on an unmounted ext filesystem. Checking a live mounted filesystem can produce unreliable results or damage; a read-only check is not a substitute for the proper offline check.

### Question 18. Correct an intentionally incorrect fstab entry and verify the corrected one.

**Command(s):**

```bash
sudo blkid /dev/sdX1
sudoedit /etc/fstab
# Correct UUID, mountpoint, filesystem type, and options for /mnt/labfs.
sudo findmnt --verify --verbose
sudo mount -av
findmnt -M /mnt/labfs
```

**Expected result / observation:**

Fix the relevant test entry using the actual UUID and correct mountpoint. Do not alter unrelated system entries. Both verification and mount should succeed before reboot.

### Question 19. Unmount the test filesystem, remove the lab fstab entry, and verify cleanup.

**Command(s):**

```bash
cd ~
sudo umount /mnt/labfs
sudoedit /etc/fstab
# Remove/comment only the lab-specific /mnt/labfs line.
sudo findmnt --verify --verbose
findmnt -M /mnt/labfs || echo "Not mounted at /mnt/labfs"
```

**Expected result / observation:**

Only the lab-specific line should be removed. Keep the backup until cleanup is verified. The test filesystem remains on its partition unless you separately choose to delete it.

### Question 20. Complete the full filesystem workflow and clean up safely.

**Command(s):**

```bash
lsblk -f
# On a disposable test partition only:
sudo mkfs.ext4 /dev/sdX1
sudo blkid /dev/sdX1
sudo mkdir -p /mnt/labfs
sudo mount /dev/sdX1 /mnt/labfs
df -hT /mnt/labfs
# Back up /etc/fstab, add actual UUID entry, then test:
sudo findmnt --verify --verbose
sudo mount -av
findmnt -M /mnt/labfs
cd ~
sudo umount /mnt/labfs
sudo e2fsck -f /dev/sdX1
# Remove/comment only the lab-specific fstab entry and verify it.
```

**Expected result / observation:**

The full sequence creates and verifies an ext4 filesystem, mounts it, tests a UUID-based persistent configuration, checks it offline, unmounts it, and removes only the lab-specific fstab entry.

---

## Result

All 20 questions in Linux Lab 10: File Systems have been addressed with commands and expected observations. Record your actual terminal output and screenshots separately if your instructor requires evidence.
