// http://download.oracle.com/docs/cd/E19963-01/html/821-1462/mpstat-1m.html
function solar_transform_mpstat(elem, cmd, rc, out) {
	var tbl = new TableTransformer()

	tbl.header("CPU", "processor ID")
	tbl.header("SET", "processor set ID")
	tbl.header("minf", "minor faults")
	tbl.header("mjf", "major faults")
	tbl.header("xcal", "inter-processor cross-calls")
	tbl.header("intr", "interrupts")
	tbl.header("ithr", "interrupts as threads (not counting clock interrupt)")
	tbl.header("csw", "context switches")
	tbl.header("icsw", "involuntary context switches")
	tbl.header("migr", "thread migrations (to another processor)")
	tbl.header("smtx", "spins on mutexes (lock not acquired on first try)")
	tbl.header("srw", "spins on readers/writer locks (lock not acquired on first try)")
	tbl.header("syscl", "system calls")
	tbl.header("usr", "percent user time")
	tbl.header("sys", "percent system time")
	tbl.header("wt", "the I/O wait time is no longer calculated as a percentage of CPU time, and this statistic will always return zero.")
	tbl.header("idl", "percent idle time")
	tbl.header("sze", "number of processors in the requested processor set")
	tbl.header("set", "processor set membership of each CPU")

	tbl.create(out, elem)
	
	return "Table"
}