# Linux Lab 7: Process Management

**Subject:** Linux Lab  |  **Subject Code:** UAC02P  |  **Worksheet:** UAC028P-AS10.pdf

## Aim

To practice process inspection, foreground/background jobs, and safe process termination using ps, top, htop, kill, pkill, and shell job-control commands.

## Environment

Kali Linux / Ubuntu / other GNU/Linux terminal

## Notes before starting

Run each numbered task in a terminal. Job numbers such as %1 depend on the jobs currently present in your shell. Sample results are representative; PIDs and process lists will differ on each computer.

---

## Solutions

### Question 1. Display processes associated with the current user.

**Command(s):**

```bash
ps -u "$USER" -o pid,tty,stat,comm
```

**Expected result / observation:**

A table showing processes owned by your login, including PID, terminal (TTY), status (STAT), and command (COMMAND).

### Question 2. Display all processes and identify PID, user, and command.

**Command(s):**

```bash
ps -e -o pid,user,comm
```

**Expected result / observation:**

A system-wide process list. PID is the process ID, USER is its owner, and COMMAND is the executable name.

### Question 3. Show a detailed process listing and identify CPU/memory fields.

**Command(s):**

```bash
ps aux | head -20
```

**Expected result / observation:**

The header includes %CPU and %MEM. Other useful fields include USER, PID, %VSZ, RSS, STAT, START, TIME, and COMMAND.

### Question 4. Display the process hierarchy with parent-child relationships.

**Command(s):**

```bash
ps -ef --forest | less
# Press q to quit less
```

**Expected result / observation:**

Processes appear in an indented tree. The PPID column identifies each process parent.

### Question 5. Start top and observe process/resource usage.

**Command(s):**

```bash
top
# Press q to quit
```

**Expected result / observation:**

The interactive view shows load, CPU and memory summaries, PIDs, users, and per-process resource use.

### Question 6. Find a process with high CPU or memory use and record its PID.

**Command(s):**

```bash
ps -eo pid,user,comm,%cpu,%mem --sort=-%cpu | head -10
# For highest memory use instead:
ps -eo pid,user,comm,%cpu,%mem --sort=-%mem | head -10
```

**Expected result / observation:**

The first data rows show processes ranked by CPU (or memory) use. Record a PID only for observation; do not terminate an unfamiliar system process.

### Question 7. Start htop and inspect its interactive interface.

**Command(s):**

```bash
htop
# Press q to quit
# If it is not installed on Kali/Debian:
sudo apt update
sudo apt install htop
```

**Expected result / observation:**

htop displays a colour-coded process list and CPU/memory meters. Installation requires administrator permission and network access.

### Question 8. Run a long task in the foreground and stop it with Ctrl+C.

**Command(s):**

```bash
sleep 300
# Press Ctrl+C while sleep is running
```

**Expected result / observation:**

The command ends and the shell prompt returns. Ctrl+C sends an interrupt to the foreground process group.

### Question 9. Start a long task in the background and display job information.

**Command(s):**

```bash
sleep 300 &
echo "PID: $!"
jobs -l
```

**Expected result / observation:**

The shell prints a job number and usually a PID. $! expands to the PID of the most recently backgrounded job.

### Question 10. List jobs managed by the current shell.

**Command(s):**

```bash
jobs
```

**Expected result / observation:**

Any running, stopped, or completed background jobs launched from this shell are listed. An empty result means there are no current jobs.

### Question 11. Bring a background job to the foreground and terminate it safely.

**Command(s):**

```bash
sleep 300 &
jobs
fg %1
# Press Ctrl+C
```

**Expected result / observation:**

The selected job occupies the terminal until interrupted. Replace %1 with the actual job number shown by jobs.

### Question 12. Suspend a foreground command with Ctrl+Z and verify it.

**Command(s):**

```bash
sleep 300
# Press Ctrl+Z
jobs
```

**Expected result / observation:**

The shell reports that the job is stopped. Ctrl+Z suspends the foreground job; it does not terminate it.

### Question 13. Resume a stopped job in the background and verify status.

**Command(s):**

```bash
jobs
bg %1
jobs
```

**Expected result / observation:**

The job should be reported as Running. Replace %1 with the relevant job number.

### Question 14. Show job number and process ID for background/stopped jobs.

**Command(s):**

```bash
sleep 300 &
sleep 300
# Press Ctrl+Z
jobs -l
# Clean up the two test jobs afterwards (use the job IDs displayed):
kill %1 %2 2>/dev/null || true
```

**Expected result / observation:**

jobs -l shows shell job numbers and associated PIDs. The final cleanup line is illustrative: use only the job numbers actually shown.

### Question 15. Terminate a selected test process with kill and verify it ended.

**Command(s):**

```bash
sleep 600 &
pid=$!
echo "Test PID: $pid"
kill "$pid"
sleep 1
ps -p "$pid" -o pid,stat,comm
```

**Expected result / observation:**

kill sends SIGTERM by default. If the process has ended, ps prints only its header or reports no matching process.

### Question 16. Use SIGTERM and explain why it is preferred first.

**Command(s):**

```bash
sleep 600 &
pid=$!
kill -TERM "$pid"
sleep 1
ps -p "$pid" -o pid,stat,comm
```

**Expected result / observation:**

SIGTERM requests normal termination, allowing a process to clean up files or release resources. Use it before forceful SIGKILL.

### Question 17. Use SIGKILL on a disposable test process only, then verify.

**Command(s):**

```bash
sleep 600 &
pid=$!
kill -KILL "$pid"
sleep 1
ps -p "$pid" -o pid,stat,comm
```

**Expected result / observation:**

SIGKILL cannot be caught or ignored, so the test sleep process should disappear. Do not use SIGKILL casually on system services or important applications.

### Question 18. Terminate a selected test process by name with pkill.

**Command(s):**

```bash
bash -c 'exec -a lab_test_sleep sleep 600' &
sleep 0.2
pgrep -af '^lab_test_sleep 600$'
pkill -f '^lab_test_sleep 600$'
sleep 1
pgrep -af '^lab_test_sleep 600$' || echo 'Test process ended'
```

**Expected result / observation:**

The unique test command line helps target only the lab process. Avoid a broad command such as pkill sleep on a real system because it could stop unrelated tasks.

### Question 19. Identify and manage multiple test processes safely.

**Command(s):**

```bash
sleep 500 & p1=$!
sleep 600 & p2=$!
printf "Test PIDs: %s %s\n" "$p1" "$p2"
ps -p "$p1,$p2" -o pid,stat,comm
kill -TERM "$p1" "$p2"
wait "$p1" "$p2" 2>/dev/null || true
ps -p "$p1,$p2" -o pid,stat,comm
```

**Expected result / observation:**

The first ps lists only the test PIDs. After SIGTERM and wait, those processes should no longer be running.

### Question 20. Complete workflow: inspect, background, suspend/resume, terminate, and clean up test work.

**Command(s):**

```bash
ps -u "$USER" -o pid,stat,comm | head
# Start a foreground test command, then press Ctrl+Z when it is running:
sleep 700
# After Ctrl+Z, verify the stopped job and resume it in the background:
jobs -l
bg %1
jobs -l
# Bring it back to the foreground and press Ctrl+Z again:
fg %1
# After Ctrl+Z, resume it in the background and capture its PID:
bg %1
jobs -l
pid=$(jobs -p %1)
kill -TERM "$pid"
wait "$pid" 2>/dev/null || true
jobs -l
```

**Expected result / observation:**

This demonstrates inspection, foreground execution, suspension, background resumption, returning to the foreground, another suspend/resume cycle, and safe termination. Job IDs vary; use the actual stopped/running job number shown by jobs.

---

## Result

All 20 questions in Linux Lab 7: Process Management have been addressed with commands and expected observations. Record your actual terminal output and screenshots separately if your instructor requires evidence.
