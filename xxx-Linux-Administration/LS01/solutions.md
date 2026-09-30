# Lab Sheet 1 — Solutions

## Easy Exercises (Q1–Q20)

### Q1
```bash
pwd
```

### Q2
```bash
ls
```

### Q3
```bash
ls -la ~
```

### Q4
```bash
ls -lh
```

### Q5
```bash
mkdir myfirstlab
```

### Q6
```bash
cd myfirstlab
```

### Q7
```bash
touch a.txt b.txt c.txt
```

### Q8
```bash
cat /etc/os-release
```

### Q9
```bash
whoami
```

### Q10
```bash
hostname
```

### Q11
```bash
df -h
```

### Q12
```bash
free -h
```

### Q13
```bash
uptime
```

### Q14
```bash
uname -a
```

### Q15
```bash
man ls
```
Press `q` to quit.

### Q16
```bash
cp --help
```

### Q17
```bash
whatis mv
```

### Q18
```bash
cd ~
```

### Q19
```bash
mkdir -p project/src/data
```

### Q20
```bash
rm myfirstlab/c.txt
```

---

## Medium Exercises (Q21–Q35)

### Q21
```bash
touch students.txt
echo "Amit" >> students.txt
echo "Neha" >> students.txt
echo "Ravi" >> students.txt
echo "Zara" >> students.txt
echo "Priya" >> students.txt
```

### Q22
```bash
head -n 2 students.txt
```

### Q23
```bash
tail -n 2 students.txt
```

### Q24
```bash
mkdir -p backup
cp students.txt backup/
```

### Q25
```bash
mv students.txt class_list.txt
```

### Q26
Example: search for `a`:
```bash
grep "a" class_list.txt
```

### Q27
```bash
grep -n "a" class_list.txt
```

### Q28
```bash
chmod 600 class_list.txt
ls -l class_list.txt
```

### Q29
```bash
find ~ -type f -name "*.txt"
```

### Q30
```bash
wc -l class_list.txt
```

### Q31
```bash
head -n 5 /etc/passwd
```

### Q32
On Debian/Ubuntu:
```bash
tail -n 5 /var/log/dpkg.log
```

If the system uses syslog:
```bash
tail -n 5 /var/log/syslog
```

### Q33
```bash
cp -r project project_backup
```

### Q34
```bash
less large_file.txt
```
Press `q` to quit.

### Q35
```bash
mkdir -p archive
mv a.txt b.txt archive/
```

---

## Hard / Challenge Exercises (Q36–Q50)

### Q36
```bash
mkdir -p logs
for i in {1..10}; do echo "Dummy log line $i" >> logs/app.log; done
tail -n 3 logs/app.log
```

### Q37
```bash
find . -type f -name "*.log" -exec cat {} +
```

### Q38
```bash
grep -rni "error" .
```

### Q39
```bash
touch test.sh
chmod +x test.sh
printf '%s\n' '#!/bin/bash' '# rwxr-xr-- means owner=rwx, group=r-x, others=r--' > test.sh
```

### Q40
```bash
mkdir demo && cd demo && ls
```

### Q41
```bash
find ~ -type f -name "*.txt" | wc -l
```

### Q42
```bash
find /var/log -type d
```

### Q43
Example configuration search, ignoring case:
```bash
grep -i "fstab" /etc/fstab
```

If you need a common filesystem entry, for example `UUID`:
```bash
grep -i "uuid" /etc/fstab
```

### Q44
```bash
du -sh /var/log
```

### Q45
```bash
ls -lhS | head -n 4
```
The first line is normally the header, so the next three entries are the three largest files/directories shown.

### Q46
`rm -rf *` is dangerous because it recursively deletes matching files/directories without asking for confirmation. Always verify the current path first:
```bash
pwd
ls -la
```
Then run deletion only after confirming the location and targets.

### Q47
```bash
touch existing_file.txt
```

### Q48
```bash
printf '%s\n' apple banana apple orange banana apple > duplicates.txt
sort duplicates.txt | uniq
```

### Q49
```bash
uname -a > sys_info.txt
cat sys_info.txt
```

### Q50
```bash
find /var/log -type f -mtime -7
```
