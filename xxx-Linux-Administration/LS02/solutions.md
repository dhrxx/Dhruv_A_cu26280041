# Lab Sheet 2 — Solutions

## Easy Exercises (Q1–Q20)

### Q1
```bash
touch notes.txt
ls -l notes.txt
```

### Q2
```bash
chmod 644 notes.txt
ls -l notes.txt
```

### Q3
```bash
chmod 600 notes.txt
ls -l notes.txt
```

### Q4
```bash
touch script.sh
chmod u+x script.sh
ls -l script.sh
```

### Q5
```bash
whoami
ls -l notes.txt
```

### Q6
```bash
ps
```

### Q7
```bash
ps aux | wc -l
```
The exact number varies with the system and time of execution.

### Q8
```bash
sleep 60 &
jobs
```

### Q9
First identify the PID:
```bash
jobs -l
```
Then terminate it:
```bash
kill <PID>
```

### Q10
Debian/Ubuntu:
```bash
apt search ^tree$
```

Fedora/RHEL:
```bash
dnf search tree
```

### Q11
```bash
umask
```

### Q12
```bash
touch document.txt
chmod g-w document.txt
```

### Q13
```bash
fg %1
```

### Q14
```bash
bg %1
```

### Q15
```bash
uname -a
```

### Q16
```bash
ps -u "$USER"
```

### Q17
Start a test process if needed:
```bash
sleep 60 &
```
Then:
```bash
pkill sleep
```

### Q18
```bash
sudo apt update
```

### Q19
```bash
apt list --installed
```

### Q20
```bash
sudo chown "$USER" script.sh
ls -l script.sh
```

---

## Medium Exercises (Q21–Q35)

### Q21
Owner `rwx`, group `r-x`, others `---`:
```bash
chmod 750 script.sh
```

### Q22
- `chmod 700 file` → owner has `rwx`; group and others have no permissions.
- `chmod 007 file` → owner and group have no permissions; others have `rwx`.

### Q23
```bash
sudo chown student:student file
```

### Q24
```bash
top
```
Press `P` to sort by CPU usage and `q` to exit.

### Q25
```bash
sleep 100 &
sleep 200 &
jobs
fg %1
```

### Q26
Example using the test `sleep` process:
```bash
sleep 60 &
pkill sleep
```
Use `pkill` only with a process name you are certain is safe to terminate.

### Q27
Debian/Ubuntu:
```bash
sudo apt update
sudo apt install tree
tree ~
```

Fedora/RHEL:
```bash
sudo dnf install tree
tree ~
```

### Q28
Debian/Ubuntu:
```bash
sudo apt remove tree
apt list --installed 2>/dev/null | grep '^tree/'
```

Fedora/RHEL:
```bash
sudo dnf remove tree
dnf list installed | grep '^tree'
```

### Q29
```bash
umask
touch test_mask.txt
ls -l test_mask.txt
```
For a regular file, the starting mode is normally `666`, with the umask removing permissions.

### Q30
```bash
ps aux | grep "$USER"
```

### Q31
```bash
sudo chgrp devteam project_dir
```

### Q32
```bash
nice -n 10 sleep 300 &
ps -o pid,ni,cmd -C sleep
```

### Q33
Find the PID:
```bash
ps -C sleep -o pid,ni,cmd
```
Then:
```bash
sudo renice 15 -p <PID>
```

### Q34
Debian/Ubuntu:
```bash
apt search apache2
sudo apt install apache2
systemctl status apache2
```

For nginx:
```bash
apt search nginx
sudo apt install nginx
systemctl status nginx
```

### Q35
`apt update` refreshes the local package index. On a fresh or stale system, the local index may not contain current package metadata, package versions, or repository information, so an installation can fail or use outdated information.

---

## Hard / Challenge Exercises (Q36–Q50)

### Q36
```bash
cat > script.sh <<'EOF'
#!/bin/bash
echo "Hello from script"
EOF

chmod +x script.sh
./script.sh
```

Without a valid executable shebang, direct execution may fail because the kernel cannot determine which interpreter should execute the script. Running `bash script.sh` can still explicitly select Bash.

### Q37
Symbolic:
```bash
chmod u=rw,g=r,o= shared.txt
```

Numeric:
```bash
chmod 640 shared.txt
```

### Q38
```bash
nice -n 15 sleep 300 &
ps -C sleep -o pid,ni,cmd
```
A higher nice value means lower scheduling priority relative to processes with lower nice values, so the process generally receives less CPU scheduling preference when competing for CPU time.

### Q39
First try a normal termination:
```bash
kill 1234
```
If it does not terminate, the escalating command is:
```bash
kill -9 1234
```
`SIGKILL` cannot be caught or handled by the target process, so it should be reserved for cases where normal termination is unsuccessful.

### Q40
```bash
ps aux --sort=-%cpu | head -5
```

### Q41
```bash
find . -type f -name "*.sh" -exec chmod +x {} +
```

### Q42
`chmod 777` gives read, write, and execute permissions to owner, group, and others. On a sensitive configuration file, this can allow unauthorized users or processes to modify it, potentially causing configuration tampering, privilege escalation, data exposure, or service compromise.

### Q43
Debian/Ubuntu example:
```bash
sudo apt install tree
dpkg -L tree
```

Fedora/RHEL:
```bash
sudo dnf install tree
rpm -ql tree
```

### Q44
- `SIGTERM` (15) requests graceful termination. The application can catch it, clean up resources, save state, and exit normally.
- `SIGKILL` (9) forces termination and cannot be caught or handled by the application.

### Q45
Create files owned by different users, for example:
```bash
sudo touch file_a.txt file_b.txt
sudo chown user1 file_a.txt
sudo chown user2 file_b.txt
```

Find files owned by `user1`:
```bash
find . -type f -user user1
```

### Q46
```bash
sudo chmod u+s custom_tool
ls -l custom_tool
```
SUID causes an executable to run with the effective user ID of the file owner. It can therefore grant elevated privileges during execution, so it must be used only on trusted programs.

### Q47
```bash
sudo mkdir -p /shared_dir
sudo chmod 2775 /shared_dir
ls -ld /shared_dir
```
The leading `2` sets SGID. New files/directories created inside inherit the directory's group ownership (subject to filesystem and permission behavior).

### Q48
Symbolic:
```bash
sudo chmod o+t /public_tmp
```

Octal:
```bash
sudo chmod 1777 /public_tmp
```
The sticky bit restricts deletion/renaming of entries in the directory so users generally can modify/remove only entries they own (or as permitted for privileged users).

### Q49
Run a long command:
```bash
sleep 300
```
Press `Ctrl+Z`, then:
```bash
jobs
bg %1
fg %1
```

### Q50
Identify the highest-memory process:
```bash
ps aux --sort=-%mem | head -2 | tail -1
```

Log PID and details, then request graceful termination:
```bash
ps aux --sort=-%mem | head -2 | tail -1 | tee top_memory.log
PID=$(ps aux --sort=-%mem | awk 'NR==2 {print $2}')
kill -TERM "$PID"
```

Use this carefully: the highest-memory process may be a critical system process. Verify the PID before sending a signal.
