# Linux Lab 8: Package Management

**Subject:** Linux Lab  |  **Subject Code:** UAC02P  |  **Worksheet:** UAC028P-AS11.pdf

## Aim

To inspect and manage software packages, repositories, updates, and transaction history with RPM and DNF.

## Environment

The supplied lab specifies Red Hat Enterprise Linux / Rocky Linux / AlmaLinux terminal.

## Notes before starting

Platform note: these questions explicitly require rpm and dnf, which are native to RPM-based distributions. The primary commands below answer the worksheet as written. Kali Linux normally uses dpkg/APT, so a Kali equivalent is included where helpful. For evidence of rpm/dnf output, use an RHEL-family VM; do not try to convert Kali into an RPM-based system for this lab.

---

## Solutions

### Question 1. Display the installed RPM version and verify rpm is available.

**Command(s):**

```bash
rpm --version
# Kali equivalent:
dpkg --version
```

**Expected result / observation:**

On an RPM-based system, rpm prints its installed version. Kali generally uses dpkg; the second command displays the dpkg version.

### Question 2. Display the installed DNF version and verify dnf is available.

**Command(s):**

```bash
dnf --version
# Kali equivalent:
apt --version
```

**Expected result / observation:**

DNF prints version and platform information on RHEL-family systems. APT is the native package manager on Kali.

### Question 3. Search the installed RPM database for a package and determine whether it is installed.

**Command(s):**

```bash
rpm -q bash
# Kali equivalent:
dpkg-query -W bash
```

**Expected result / observation:**

If installed, the command returns the package name and version; otherwise it reports that the package is not installed.

### Question 4. Display detailed information about an installed RPM package.

**Command(s):**

```bash
rpm -qi bash
# Kali equivalent:
apt show bash
```

**Expected result / observation:**

RPM displays name, version, release, architecture, install date, size, vendor, and description. APT show displays repository/package metadata, not exactly the RPM database fields.

### Question 5. List all files installed by a package.

**Command(s):**

```bash
rpm -ql bash
# Kali equivalent:
dpkg -L bash
```

**Expected result / observation:**

A list of package-owned paths is printed. Use a package known to be installed.

### Question 6. Identify the installed package that owns a specified file.

**Command(s):**

```bash
rpm -qf "$(command -v bash)"
# Kali equivalent:
dpkg -S "$(readlink -f "$(command -v bash)")"
```

**Expected result / observation:**

The output names the package owning the selected executable path. If a file is not package-managed, the query may report that no package owns it.

### Question 7. Display RPM installation date and other details for a package.

**Command(s):**

```bash
rpm -qi bash | grep -E '^(Name|Version|Release|Architecture|Install Date|Vendor)'
# Kali: dpkg does not keep the same install-date field in its package query; inspect logs if present:
grep -h " install bash " /var/log/dpkg.log* 2>/dev/null | tail -5
```

**Expected result / observation:**

On RPM, the filtered fields include Install Date when recorded. On Kali, dpkg logs may show install history if the relevant rotated log is still retained.

### Question 8. List configured DNF repositories and enabled/disabled status.

**Command(s):**

```bash
dnf repolist --all
# Kali equivalent: inspect configured APT sources
grep -R --no-filename -E "^[[:space:]]*deb " /etc/apt/sources.list /etc/apt/sources.list.d 2>/dev/null
```

**Expected result / observation:**

DNF labels repository IDs and status. Kali output lists configured APT source entries; APT sources are not the same structure as DNF repositories.

### Question 9. Display detailed information about a selected DNF repository.

**Command(s):**

```bash
dnf repoinfo appstream
# Replace appstream with a repository ID shown by `dnf repolist --all`.
# Kali equivalent:
apt-cache policy
```

**Expected result / observation:**

DNF shows base URL, enabled state, metadata details, and related repository information. Repository IDs vary by distribution.

### Question 10. Search configured repositories for a package by name/keyword.

**Command(s):**

```bash
dnf search htop
# Kali equivalent:
apt-cache search htop
```

**Expected result / observation:**

Matching package names and descriptions appear. Search results depend on enabled repositories and refreshed metadata.

### Question 11. Install a suitable package and verify it is installed.

**Command(s):**

```bash
sudo dnf install htop
rpm -q htop
# Kali equivalent:
sudo apt update
sudo apt install htop
dpkg-query -W htop
```

**Expected result / observation:**

The package manager resolves dependencies and asks for confirmation unless configured otherwise. Verification returns the installed version.

### Question 12. Install a package and verify its installed version using rpm and dnf.

**Command(s):**

```bash
sudo dnf install htop
rpm -q htop
dnf info installed htop
# Kali equivalent:
sudo apt install htop
dpkg-query -W -f="${Package} ${Version}\n" htop
apt-cache policy htop
```

**Expected result / observation:**

RPM queries the installed version, and DNF displays package details. Kali uses dpkg-query and APT instead.

### Question 13. Display available updates without applying them.

**Command(s):**

```bash
dnf check-update
# This command can return exit code 100 when updates are available; that is expected.
# Kali equivalent:
sudo apt update
apt list --upgradable
```

**Expected result / observation:**

The commands report available updates but do not upgrade installed packages. On Kali, apt update refreshes index files; apt list --upgradable lists candidates.

### Question 14. Update a selected installed package and verify its new version.

**Command(s):**

```bash
sudo dnf upgrade htop
rpm -q htop
# Kali equivalent:
sudo apt update
sudo apt install --only-upgrade htop
dpkg-query -W htop
```

**Expected result / observation:**

The package manager upgrades the selected package if a newer version is available. If already current, the package may remain unchanged.

### Question 15. Update all packages for which updates are available and verify the result.

**Command(s):**

```bash
sudo dnf upgrade --refresh
dnf check-update
# Kali equivalent:
sudo apt update
sudo apt upgrade
```

**Expected result / observation:**

All eligible updates are applied after dependency review. Read the proposed changes before confirming, especially on a daily-use machine.

### Question 16. Remove a selected test package and verify it has been removed.

**Command(s):**

```bash
sudo dnf remove htop
rpm -q htop
# Kali equivalent:
sudo apt remove htop
dpkg-query -W htop
```

**Expected result / observation:**

After removal the RPM/dpkg query should say the package is not installed. Pick a nonessential test package and review the list of dependent packages before confirming.

### Question 17. Identify the repository that provides a selected package/file.

**Command(s):**

```bash
dnf provides /usr/bin/htop
# Kali equivalent: show candidate package/source metadata
apt-cache policy htop
```

**Expected result / observation:**

DNF reports a package and repository providing the requested file. APT policy shows the installed version and repository candidates; it is not an exact DNF provides replacement.

### Question 18. Display DNF transaction history and inspect a recent transaction.

**Command(s):**

```bash
dnf history
dnf history info last
# Kali equivalent: review retained dpkg logs
grep -hE " (install|upgrade|remove) " /var/log/dpkg.log* 2>/dev/null | tail -20
```

**Expected result / observation:**

DNF lists transaction IDs, dates, actions, and summaries. On Kali, package actions can often be reconstructed from dpkg logs while retained.

### Question 19. Demonstrate install, verify, update/inspect, and remove as one workflow.

**Command(s):**

```bash
sudo dnf install htop
rpm -q htop
dnf info installed htop
sudo dnf remove htop
rpm -q htop
# Kali equivalent:
sudo apt install htop
dpkg-query -W htop
sudo apt remove htop
dpkg-query -W htop
```

**Expected result / observation:**

The package is installed, queried, and then removed. The final query should report that it is not installed; omit or adapt removal if htop is a package you need.

### Question 20. Clean up lab package/repository changes and verify system configuration.

**Command(s):**

```bash
dnf repolist --all
dnf history
sudo dnf clean all
# Kali equivalent:
grep -R --no-filename -E "^[[:space:]]*deb " /etc/apt/sources.list /etc/apt/sources.list.d 2>/dev/null
sudo apt clean
```

**Expected result / observation:**

Confirm the original repositories remain configured. These clean commands remove cached package metadata/files, not installed packages. If you did not add a repository, there should be no lab-specific repository entry to remove.

---

## Result

All 20 questions in Linux Lab 8: Package Management have been addressed with commands and expected observations. Record your actual terminal output and screenshots separately if your instructor requires evidence.
